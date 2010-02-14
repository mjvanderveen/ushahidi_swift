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

class Twitter_Controller extends Controller
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

		//Get hashtags
		$hashtags = explode(',',$settings->twitter_hashtags);

		$username = $settings->twitter_username;
		$password = $settings->twitter_password;

 		// Start streaming/collecting
 		print "Start consuming";
		$sc = new Collector($username, $password, Kohana::config('config.tmp_directory'));
		$sc->setTrack(array('haiti'));
		$sc->consume();
	}
}
?>
