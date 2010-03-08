<?php defined('SYSPATH') or die('No direct script access.');

/**
* Model for Twitter
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Twitter Model
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Twitter_Search_Geo_Model extends ORM
{
	//protected $belongs_to = array('incident');

	// Database table name
	protected $table_name = 'twitter_search_geo';

	function get_locations($search_id)
	{
		// Get process
		$geo = ORM::factory($this->table_name)
			->where('search_id', $search_id)
			->orderby('placename', 'ASC')
			->orderby('radius', 'ASC')
			->find_all();

		return $geo;
	}

	/*
	 * TODO: this is probably too simple, since you can spell the same area in different ways
	 */
	function location_exists($search_id, $placename, $radius)
	{
		// check if the user_id is in the twitter_search_users table
		$g = ORM::factory($this->table_name)
			->where('search_id', $search_id)
			->where('placename', $placename)
			->where('radius', $radius)
			->count_all();

		if($g == 0)
		{
			return false;
		}

		return true;
	}

	function get($id)
	{
		// Get process
		$location = ORM::factory($this->table_name)
				->where('id', $id)
				->find();

		return $location;
	}
}