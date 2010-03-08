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

class Twitter_Search_Keywords_Model extends ORM
{
	//protected $belongs_to = array('incident');

	// Database table name
	protected $table_name = 'twitter_search_keywords';

	function get_keywords($search_id)
	{
		// Get process
		$keywords = ORM::factory($this->table_name)
			->where('search_id', $search_id)
			->orderby('keyword', 'ASC')
			->find_all();

		return $keywords;
	}

	function keyword_exists($search_id, $keyword)
	{
		// Get process
		$count = ORM::factory($this->table_name)
			->where('search_id', $search_id)
			->where('keyword', $keyword)
			->count_all();

		if($count == 0)
		{
			return false;
		}

		return true;
	}

	function get($id)
	{
		// Get process
		$keyword = ORM::factory($this->table_name)
			->where('id', $id)
			->find();

		return $keyword;
	}
}