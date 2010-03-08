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

class Parser_Controller extends Controller
{
	public function __construct()
    {
        parent::__construct();
	}

	public function index()
	{
		// Check for running parsing process
		$processes = new Process_Model();
		$process = $processes->get_process(Kohana::config('config.twitter_process'));

		// If running, stop this thread
		if($process->process_active){
			return;
		}

		// Get oldest file
		$file = queue::get_oldest_file(Kohana::config('config.tmp_directory'), '.queue');

		// if there is no file, stop the process
		if(empty($file))
		{
			Kohana::log('debug', 'No file to process for parsing');
			return;
		}

		// If not running, set the process to running
		$process->process_active = true;
		$process->save();

		// Open file
   		$fp = fopen(Kohana::config('config.tmp_directory').$file, 'r');

	    // Check if something has gone wrong, or perhaps the file is just locked by another process
	    if (!is_resource($fp))
	    {
	    	// Set the process to not running
			$process->process_active = false;
			$process->save();

	      	Kohana::log('warning', 'WARN: Unable to open file or file already open: '.$file.' - Skipping.');
	      	return;
	    }
	    else
	    {
	    	Kohana::log('debug', 'Processing twitter hose file: '.$file);
	    }

	    // Lock file
	    flock($fp, LOCK_EX);

		// Create tweets array
		$tweets = array();
		$users = array();

	    // Loop over each line (1 line per status)
	    $statusCounter = 0;
	    while ($rawStatus = fgets($fp, 4096))
	    {

	      $data = json_decode($rawStatus, true);

	      if (is_array($data) && isset($data['user']['screen_name'])) {
	        // TODO: do some SILCC processing here

			// Grab desired fields to store
			$tweet = array();
			$tweet['id'] = $data['id'];
			$tweet['user_id'] = $data['user']['id'];
			$tweet['text'] = $data['text'];
			$tweet['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));

			if(isset($data['geo']['coordinates']))
			{
				list($tweet['lattitude'], $tweet['longitude']) = split(',', $data['geo']['coordinates']);
			}

			$user = array();
			$user['id'] = $data['user']['id'];
			$user['name'] = $data['user']['name'];
			$user['screen_name'] = $data['user']['screen_name'];
			$user['created_at'] = date("Y-m-d H:i:s", strtotime($data['user']['created_at']));

			// Add to tweets and users
			array_push($tweets, $tweet);
			array_push($users, $user);
	      }

	      $statusCounter++;

	    } // End while

		// Write to file
		$fpf = fopen(Kohana::config('config.tmp_directory').'twitter_tweets'.date('Ymd-His').'.tweets', 'w');
		fwrite($fpf, queue::str_putcsv($tweets, ',', '"', "\r\n"));
		fclose($fpf);

	    // Write to file
		$fpu = fopen(Kohana::config('config.tmp_directory').'twitter_users'.date('Ymd-His').'.users', 'w');
		fwrite($fpu, queue::str_putcsv($users, ',', '"', "\r\n"));
		fclose($fpu);

	    // Release lock and close
	    flock($fp, LOCK_UN);
	    fclose($fp);

	    // All done with this file
	    Kohana::log('debug', 'Successfully processed '.$statusCounter.' tweets from '.$file.' - deleting.');

	    // Remove the file
	    unlink(Kohana::config('config.tmp_directory').$file);

		// Set the process to not running
		$process->process_active = false;
		$process->save();

	}
}
?>