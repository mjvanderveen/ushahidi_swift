<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Twitter Scheduler Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Twitter Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
*/

class Twitter_Search_API
{
	private $username;
	private $password;

	public function __construct()
    {
		// Grabbing tweets requires cURL so we will check for that here.
		if (!function_exists('curl_exec'))
		{
			throw new Kohana_Exception('twitter.cURL_not_installed');
			return false;
		}

        // Retrieve Current Settings
		$settings = ORM::factory('settings', 1);

		//Perform Direct Reports Search
		$this->username = $settings->twitter_username;
		$this->password = $settings->twitter_password;
	}

	public function get_replies()
	{
		if (!empty($this->username) && !empty($this->password))
		{
			$twitter_url = 'http://twitter.com/statuses/replies.json'; //XXX '?.$last_tweet_id;
			$curl_handle = curl_init();
			curl_setopt($curl_handle,CURLOPT_URL,$twitter_url);
			curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,4);
			curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl_handle,CURLOPT_USERPWD,$this->username.":".$this->password); //Authenticate!
			$buffer = curl_exec($curl_handle);
			curl_close($curl_handle);
			return $this->parse_tweets($buffer);
		}
		else
		{
			throw new Kohana_Exception('Twitter login details missing');
			return false;
		}
	}

	public function get_user($name)
	{
		if(empty($name))
		{
			throw new Kohana_Exception('Twitter username missing in search');
			return false;
		}

		if (!empty($this->username) && !empty($this->password))
		{
			$twitter_url = 'http://api.twitter.com/1/users/show.json?screen_name='.urlencode($name); //XXX '?.$last_tweet_id;
			$curl_handle = curl_init();
			curl_setopt($curl_handle,CURLOPT_URL,$twitter_url);
			curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,4);
			curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl_handle,CURLOPT_USERPWD,$this->username.":".$this->password); //Authenticate!
			$buffer = curl_exec($curl_handle);
			curl_close($curl_handle);

			return $this->parse_user($buffer, $name);
		}
		else
		{
			throw new Kohana_Exception('Twitter login details missing');
			return false;
		}
	}


	private function parse_tweets($data)
	{
		$tweets = json_decode($data, false);
		if (!$tweets) {
			return;
		}
		if (isset($tweets->{'error'})) {
			throw new Kohana_Exception($tweets->{'error'});
			return;
		}

		return $tweets;
	}

	private function parse_user($data, $name)
	{
		$tweets = json_decode($data, false);
		if (!$tweets) {
			throw new Kohana_Exception('User not found: '.$name);
			return;
		}
		if (isset($tweets->{'error'})) {
			throw new Kohana_Exception('Error when searching for user '.$name.': '.$tweets->{'error'});
			return;
		}

		return $tweets;
	}
}
