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

class Dumper_Controller extends Controller
{
	public function __construct()
    {
    	if (PHP_SAPI != 'cli') { die('Not authorized'); }
        parent::__construct();
	}

	public function index()
	{
		// Dump one file from tweets and one from users to the database
		self::dump('tweets');
		self::dump('users');
	}

	//
	// Type is either tweets, or users
	//
	public function dump($type)
	{
		// check input
		if(empty($type))
		{
			return;
		}

		// Get oldest file
		$file = queue::get_oldest_file(Kohana::config('config.tmp_directory'), '.'.$type);

		if(empty($file)){
			return;
		}

		$file_location = Kohana::config('config.tmp_directory').$file;

		// Open file
   		$fp = fopen($file_location, 'r');

	    // Check if something has gone wrong, or perhaps the file is just locked by another process
	    if (!is_resource($fp))
	    {
	      	Kohana::log('warning', 'WARN: Unable to open file or file already open: '.$file.' - Skipping.');
	      	return FALSE;
	    }
	    else
	    {
	    	Kohana::log('debug', 'Processing twitter csv file: '.$file);
	    }

		fclose($fp);

		// Connect to db
		$connection = mysql_connect(Kohana::config('database.default.connection.host'), Kohana::config('database.default.connection.user'), Kohana::config('database.default.connection.pass'));

		// select database
		mysql_select_db(Kohana::config('database.default.connection.database'), $connection)
			or die(Kohana::log('debug', 'Error selecting database '.mysql_error()));

		// Load file into database
		mysql_query("LOAD DATA INFILE '".$file_location."' IGNORE INTO TABLE `twitter_".$type."` FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n'", $connection)
			or die(Kohana::log('debug', 'Error loading tweets '.mysql_error()));

		 // close connection
		 mysql_close( $connection );

	    // All done with this file
	    Kohana::log('debug', 'Successfully processed '.$file);

	    // Remove the file
	    unlink($file_location);
	}
}
?>
