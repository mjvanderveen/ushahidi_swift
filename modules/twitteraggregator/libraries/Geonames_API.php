<?php
/*
 * Created on 7-mrt-2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Geonames_API
 {

 	public function __construct()
    {
		// Grabbing tweets requires cURL so we will check for that here.
		if (!function_exists('curl_exec'))
		{
			throw new Kohana_Exception('twitter.cURL_not_installed');
			return false;
		}
 	}

 	public function get_location($placename)
	{

		$api_url = 'http://ws.geonames.org/search?type=json&q='.urlencode(utf8_encode($placename)) ; //XXX '?.$last_tweet_id;
		$curl_handle = curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$api_url);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,4);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);

		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);

		return $this->parse_location($buffer, $placename);

	}


	private function parse_location($data, $name)
	{
		$location = json_decode($data, true);
		if (!$location) {
			throw new Kohana_Exception('Location not found: '.$name);
			return;
		}
		if (isset($location->{'error'})) {
			throw new Kohana_Exception('Error when searching for user '.$name.': '.$location->{'error'});
			return;
		}

		if(isset($location['geonames']))
		{
			return array_shift($location['geonames']);
		}
		else
		{
			return NULL;
		}

	}
 }
?>
