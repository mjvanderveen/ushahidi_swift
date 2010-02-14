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

		print "Starting new process";

		// If not running, set the process to running
		$process->process_active = true;
		$process->save();

		// Get oldest file
		$file = queue::get_oldest_file(Kohana::config('config.tmp_directory'));

		// Open file
   		$fp = fopen(Kohana::config('config.tmp_directory') . $file, 'r');

	    // Check if something has gone wrong, or perhaps the file is just locked by another process
	    if (!is_resource($fp))
	    {
	      Kohana::log('warning', 'WARN: Unable to open file or file already open: ' . $file . ' - Skipping.');
	      return FALSE;
	    }

	    // Lock file
	    flock($fp, LOCK_EX);

		// Create tweets array
		$tweets = array();

	    // Loop over each line (1 line per status)
	    $statusCounter = 0;
	    while ($rawStatus = fgets($fp, 4096))
	    {

	      $data = json_decode($rawStatus, true);
	      if (is_array($data) && isset($data['user']['screen_name'])) {
	        // TODO: do some SILCC processing here

			// Grab desired fields to store
			$tweet = array();
			$tweet['user'] = $data['user']['screen_name'];
			$tweet['text'] = $data['text'];

			// Add to tweets
			array_push($tweets, $tweet);
	      }

	    } // End while

		// Write to file
		$fpf = fopen(Kohana::config('config.tmp_directory').'twitter_'.date('Ymd-His').'.csv', 'w');
		fwrite($fpf, queue::str_putcsv($tweets));
		fclose($fpf);

	    // Release lock and close
	    flock($fp, LOCK_UN);
	    fclose($fp);

	    // All done with this file
	    Kohana::log('info', 'Successfully processed ' . $statusCounter . ' tweets from ' . $file . ' - deleting.');

	    // Remove the file
	    //unlink($file);

		// Set the process to not running
		$process->process_active = false;
		$process->save();

	}
}
?>