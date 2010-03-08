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

class Twitter_Search_Users_Model extends ORM
{
	//protected $belongs_to = array('incident');

	// Database table name
	protected $table_name = 'twitter_search_users';

	function get_users($search_id)
	{
		// Get process
		$users = ORM::factory($this->table_name)
			->select($this->table_name.'.*', 'twitter_users.screen_name')
			->join('twitter_users', 'twitter_users.id', $this->table_name.'.user_id', 'LEFT')
			->where('search_id', $search_id)
			->orderby('screen_name', 'ASC')
			->find_all();

		return $users;
	}

	function user_exists($search_id, $user_id)
	{
		// check if the user_id is in the twitter_search_users table
		$u = ORM::factory($this->table_name)
			->where('search_id', $search_id)
			->where('user_id', $user_id)
			->count_all();

		if($u == 0)
		{
			return false;
		}

		return true;
	}

	function get($id)
	{
		// Get process
		$user = ORM::factory($this->table_name)
			->select($this->table_name.'.*', 'twitter_users.screen_name')
			->join('twitter_users', 'twitter_users.id', $this->table_name.'.user_id', 'LEFT')
			->where($this->table_name.'.id', $id)
			->find();

		return $user;
	}
}