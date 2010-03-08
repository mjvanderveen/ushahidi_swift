<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Messages Controller.
 * View SMS Messages Received Via FrontlineSMS
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Admin Messages Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Twitter_Controller extends Admin_Controller
{
	private $form_changed = FALSE;

	function __construct()
	{
		parent::__construct();

		$this->template->this_page = 'twitter';
		//$this->template->content->form_saved = false;

		// If this is not a super-user account, redirect to dashboard
		if (!$this->auth->logged_in('admin') && !$this->auth->logged_in('superadmin'))
        {
             url::redirect('admin/dashboard');
		}
	}

	public function index()
	{
		// Set view
		$this->template->content = new View('admin/twitter');

		// setup and initialize form field names
		$form = array();

		// Currently there is only one search
		$search_id = 1;

		// get settings
		$settings = ORM::factory('settings', 1);

		//copy the form as errors, so the errors will be stored with keys corresponding to the form field names
		$errors = $form;
		$form_error = FALSE;

		$this->template->content->form_saved = FALSE;

		// check, has the form been submitted, if so, setup validation
		if ($_POST)
		{
					// Instantiate Validation, use $post, so we don't overwrite $_POST fields with our own things
					$post = Validation::factory(array_merge($_POST,$_FILES));

					 //  Add some filters
					$post->pre_filter('trim', TRUE);

					if ($post->validate())
					{
						// Handle keywords
						if(isset($post['keywords_old']))
						{
							foreach($post['keywords_old'] as $id => $keyword_old){
								if(empty($post['keywords'][$id]))
								{
									// remove keyword
									$this->remove_keyword($id);
								}
								else if($post['keywords'][$id] != $keyword_old)
								{
									// change keyword
									$this->change_keyword($id, $post['keywords'][$id]);
								}
							}
						}

						// Save new keywords
						foreach($post['keywords_new'] as $keyword_new){
							$this->save_keyword($search_id, $keyword_new);
						}

						// Handle users
						if(isset($post['users_old']))
						{
							foreach($post['users_old'] as $id => $user_old){
								if(empty($post['users'][$id]))
								{
									// remove keyword
									$this->remove_user($id);
								}
								else if($post['users'][$id] != $user_old)
								{
									// change user
									try
									{
										$this->change_user($search_id, $id, $post['users'][$id]);
									}
									catch (Exception $e)
									{
										array_push($errors, $e);
									}
								}
							}
						}

						// Save new users
						foreach($post['users_new'] as $user_new){
							try
							{
								$this->save_user($search_id, $user_new);
							}
							catch (Exception $e)
							{
								array_push($errors, $e);
							}
						}

						// Handle geo
						if(isset($post['placename_old']))
						{
							foreach($post['placename_old'] as $id => $placename_old){
								if(empty($post['placename'][$id]) && empty($post['radius'][$id]))
								{
									// remove keyword
									$this->remove_location($id);
								}
								else if($post['placename'][$id] != $placename_old || $post['radius'][$id] != $post['radius_old'][$id])
								{
									// change keyword
									$this->change_location($id, $post['placename'][$id], $post['radius'][$id]);
								}
							}
						}

						// Save new users
						foreach($post['placename_new'] as $id => $placename_new){
							try
							{
								$this->save_location($search_id, $placename_new, $post['radius_new'][$id]);
							}
							catch (Exception $e)
							{
								array_push($errors, $e);
							}
						}

						$this->template->content->form_saved = $this->form_changed;
						$this->template->content->form_action = "Added / Updated"	;
					}

					// populate the error fields, if any
					$errors = arr::overwrite($errors, $post->errors('report'));
					if(count($errors))
					{
						$form_error = TRUE;
					}


				}

				$twitter_search = new Twitter_Search_Model($search_id);

				$this->template->content->keywords = $twitter_search->get_keywords();
				$this->template->content->users = $twitter_search->get_users();
				$this->template->content->locations = $twitter_search->get_locations();

				$this->template->content->errors = $errors;
				$this->template->content->form_error = $form_error;

				$this->template->content->form = $form;
	}

	private function save_keyword($search_id, $keyword)
	{
		if(empty($keyword))
		{
			return;
		}

		$keyword_model = new Twitter_Search_Keywords_Model();
		$keyword_exists = $keyword_model->keyword_exists($search_id, $keyword);

		if(!$keyword_exists){
			$keyword_model->search_id = $search_id;
			$keyword_model->keyword = $keyword;
			$keyword_model->save();

			$this->form_changed = true;
		}
	}

	private function remove_keyword($id)
	{
		if(empty($id) || !is_numeric($id))
		{
			return;
		}

		$keyword_model = new Twitter_Search_Keywords_Model();
		$keyword_model->delete($id);

		$this->form_changed = true;
	}

	private function change_keyword($id, $keyword)
	{
		if(empty($id) || !is_numeric($id) || empty($keyword))
		{
			return;
		}

		$keyword_model = new Twitter_Search_Keywords_Model();
		$k = $keyword_model->get($id);
		$k->keyword = $keyword;
		$k->save();

		$this->form_changed = true;
	}

	private function save_user($search_id, $user, $user_id = NULL)
	{
		if(empty($user))
		{
			return;
		}

		if($user_id == NULL)
		{
			$twitter_user = new Twitter_users_Model();
			$tu = $twitter_user->get($user);

			// If the user does not exist, get it from the search api
			if($tu->id == 0)
			{
				// Call the api
				$search = new Twitter_Search_API();
				$u = $search->get_user($user);

				// Add to the twitter_users table
				$twitter_users_model = new Twitter_users_Model();
				$twitter_users_model->id = $u->id;
				$twitter_users_model->name = $u->name;
				$twitter_users_model->screen_name = $u->screen_name;
				$twitter_users_model->created_at = date("Y-m-d H:i:s", strtotime($u->created_at));
				$twitter_users_model->save();

				// Set user_id
				$user_id = $u->id;
			}
			else
			{
				$user_id = $tu->id;
			}
		}

		if($user_id == NULL)
		{
			throw new Kohana_User_Exception('Melding:','Twitter user "' . $user . '" not found');
		}

		$user_model = new Twitter_Search_Users_Model();
		$user_exists = $user_model->user_exists($search_id, $user_id);

		if(!$user_exists){
			$user_model->search_id = $search_id;
			$user_model->user_id = $user_id;
			$user_model->save();

			$this->form_changed = true;
		}
		else
		{
			throw new Kohana_User_Exception('Melding:','Twitter user "' . $user . '" already in the list');
		}
	}

	private function remove_user($id)
	{
		if(empty($id) || !is_numeric($id))
		{
			return;
		}

		$user_model = new Twitter_Search_Users_Model();
		$user_model->delete($id);

		$this->form_changed = true;
	}

	private function change_user($search_id, $id, $user)
	{
		if(empty($id) || !is_numeric($id) || empty($user))
		{
			return;
		}

		$this->remove_user($id);
		$this->save_user($search_id, $user);
	}

	private function save_location($search_id, $placename, $radius)
	{
		if(empty($placename) && empty($radius)){
			return;
		}

		if(empty($placename) || empty($radius))
		{
			throw new Kohana_User_Exception('Notice:','Placename or radius left empty, not stored');
			return;
		}

		$geo_model = new Twitter_Search_Geo_Model();
		$location_exists = $geo_model->location_exists($search_id, $placename, $radius);

		if(!$location_exists){
			// Get geo coordinates
			$geonames = new Geonames_API();
			$location = $geonames->get_location($placename);

			if($location == NULL)
			{
				throw new Kohana_User_Exception('Notice:','No location could be found for placename:'.$placename);
				return;
			}

			$geo_model->search_id = $search_id;
			$geo_model->placename = $placename;
			$geo_model->lattitude = $location['lat'];
			$geo_model->longitude = $location['lng'];
			$geo_model->radius = $radius;
			$geo_model->save();

			$this->form_changed = true;
		}
	}

	private function remove_location($id)
	{
		if(empty($id) || !is_numeric($id))
		{
			throw new Kohana_User_Exception('Notice:','Cannot remove location with id: '.$id);
			return;
		}

		$geo_model = new Twitter_Search_Geo_Model();
		$geo_model->delete($id);

		$this->form_changed = true;
	}

	private function change_location($id, $placename, $radius)
	{
		if(empty($id) || !is_numeric($id) || empty($placename) || empty($radius))
		{
			throw new Kohana_User_Exception('Notice:','Placename or radius left empty, not stored');
			return;
		}

		$geo_model = new Twitter_Search_Geo_Model();
		$g = $geo_model->get($id);
		$g->placename = $placename;
		$g->radius = $radius;
		$g->save();

		$this->form_changed = true;
	}
}
