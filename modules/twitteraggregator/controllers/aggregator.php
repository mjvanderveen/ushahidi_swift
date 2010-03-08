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

class Aggregator_Controller extends Controller
{
	public function __construct()
    {
    	if (PHP_SAPI != 'cli') { die('Not authorized'); }
        parent::__construct();
	}

	public function index()
	{
		// Retrieve Current Settings
		$settings = ORM::factory('settings', 1);

		// Start main search
		// TODO: in the future, we can add multiple searches, but have to find a way to seperate
		// the results from the firehose.
		$search_model = new Twitter_Search_Model(1);

 		// Start streaming/collecting
		$sc = new Collector($settings->twitter_username, $settings->twitter_password, Kohana::config('config.tmp_directory'));

		// Set keywords
		$k = $search_model->get_keywords();
		$keywords = array();
		foreach($k as $keyword)
		{
			array_push($keywords, $keyword->keyword);
		}

		$sc->setTrack($keywords);

		// Set users
		$u = $search_model->get_users();
		$users = array();
		foreach($u as $user)
		{
			array_push($users, $user->user_id);
		}

		$sc->setFollow($users);

		// Set locations
		$l = $search_model->get_locations();
		$locations = array();
		foreach($l as $location)
		{
			array_push($locations, array($location->longitude, $location->lattitude, $location->radius));
		}

		$sc->setLocationsByCircle($locations);

		$sc->consume();
	}
}
?>
