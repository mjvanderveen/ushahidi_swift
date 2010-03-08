<?php
/*
 * Created on 28-feb-2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Twitter_Search_Model
{
	private $search_id;

	function __construct($id)
	{
		$this->search_id = $id;
	}

	function get_keywords()
	{
		$keyword_model = new Twitter_Search_Keywords_Model();

		// Get process
		$keywords = $keyword_model->get_keywords($this->search_id);

		return $keywords;
	}

	function get_users()
	{
		$users_model = new Twitter_Search_Users_Model();

		// Get process
		$users = $users_model->get_users($this->search_id);

		return $users;
	}

	function get_locations()
	{
		$geo_model = new Twitter_Search_Geo_Model();

		// Get process
		$geo = $geo_model->get_locations($this->search_id);

		return $geo;
	}
}
?>
