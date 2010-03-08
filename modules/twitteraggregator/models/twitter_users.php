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

class Twitter_users_Model extends ORM
{
	//protected $belongs_to = array('incident');

	// Database table name
	protected $table_name = 'twitter_users';

	function get($name)
	{
		// Get process
		$user = ORM::factory($this->table_name)
			->where('screen_name', $name)
			->find();

		return $user;
	}
}