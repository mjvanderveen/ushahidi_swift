<?php
/**
 * Feeds $form page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
?>
			<div class="bg">
				<h2>
					<a href="<?php echo url::base() . 'admin/manage' ?>">Categories</a>
					<a href="<?php echo url::base() . 'admin/manage/forms' ?>">Forms</a>
					<a href="<?php echo url::base() . 'admin/manage/organizations' ?>">Organizations</a>
					<a href="<?php echo url::base() . 'admin/manage/pages' ?>">Pages</a>
					<a href="<?php echo url::base() . 'admin/twitter/index' ?>">Twitter</a>
					<a href="<?php echo url::base() . 'admin/feeds' ?>" class="active">News Feeds</a>
					<a href="<?php echo url::base() . 'admin/manage/layers' ?>">Layers</a>
					<a href="<?php echo url::base() . 'admin/manage/reporters' ?>">Reporters</a>
				</h2>

				<!-- tabs -->
				<?php
				if ($form_error) {
				?>
					<!-- red-box -->
					<div class="red-box">
						<h3>Meldingen</h3>
						<ul>
						<?php
						foreach ($errors as $error_item => $error_description)
						{
							// print "<li>" . $error_description . "</li>";
							print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
						}
						?>
						</ul>
					</div>
				<?php
				}

				if ($form_saved) {
				?>
					<!-- green-box -->
					<div class="green-box">
						<h3>The Feeds Have Been <?php echo $form_action; ?>!</h3>
					</div>
				<?php
				}
				?>
				<!-- report-table -->
				<div class="report-form">
				 		<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'twitterForm', 'name' => 'twitterForm', 'class' => 'gen_forms')); ?>
						<input type="hidden" name="action" id="action" value="">
						<input type="hidden" name="twitter_id" id="twitter_id_action" value="">
						<div class="table-holder">
							<table width="50%" border="0" class="tables">
							  <tr>
                                <th width="25%" ><h4>TWITER KEYWORDS</h4></th>
                                <th width="25%" ><h4>TWITTER USERS</h4></th>
                                <th width="50%" ><h4>TWITTER GEO LOCATIONS</h4></th>
                              </tr>
							  <tr>
							    <td>
                       	    <table width="100%" border="0" style="text-align:center">
                                   		<tr>
							        		<th>&nbsp;</th>
						         		</tr>
							      		<?php foreach($keywords as $keyword) { ?>
                                        <tr>
							        		<td>
                                            	<input type="hidden"
                                                		name="<?php echo 'keywords_old['.$keyword->id.']'; ?>"
                                                        id="<?php echo 'keywords_old['.$keyword->id.']' ?>"
                                                        value="<?php echo $keyword->keyword; ?>" />
												<?php
													print form::input('keywords['.$keyword->id.']', $keyword->keyword, ' class="text short2" ');
												?>
                                            </td>
						         		</tr>
                                        <?php } ?>
                                        <?php for($i = 0; $i < 3; $i++) { ?>
                                        <tr>
							        		<td>
												<?php
													print form::input('keywords_new['.$i.']','', ' class="text short2" ');
												?>
                                            </td>
						         		</tr>
                                        <?php } ?>
						        	</table>
                                 </td>
							    <td>
                                	<table width="100%" border="0"  style="text-align:center">
                                    	<tr>
							        		<th>&nbsp;</th>
						         		</tr>
							      		<?php foreach($users as $user) { ?>
                                        <tr>
							        		<td>
                                            	<input type="hidden"
                                                		name="<?php echo 'users_old['.$user->id.']'; ?>"
                                                        id="<?php echo 'users_old['.$user->id.']'; ?>"
                                                        value="<?php echo $user->screen_name; ?>" />
												<?php
													print form::input('users['.$user->id.']',$user->screen_name, ' class="text short2" ');
												?>
                                            </td>
						         		</tr>
                                        <?php } ?>
										<?php for($i = 0; $i < 3; $i++) { ?>
                                        <tr>
							        		<td>
												<?php
													print form::input('users_new['.$i.']','', ' class="text short2" ');
												?>
                                            </td>
						         		</tr>
                                        <?php } ?>
						        	</table>
                                 </td>
							    <td>
                                	<table width="100%" border="0"  style="text-align:center">
                                    	<tr>
							        		<th width="50%">Placename</th>
                                            <th width="50%">Radius</th>
						         		</tr>
                                        <?php foreach($locations as $location) { ?>
                                        <tr>
							        		<td>
                                            	<input type="hidden"
                                                		name="<?php echo 'placename_old['.$location->id.']'; ?>"
                                                        id="<?php echo 'placename_old['.$location->id.']'; ?>"
                                                        value="<?php echo $location->placename; ?>" />
												<?php
													print form::input('placename['.$location->id.']',$location->placename, ' class="text short2" ');
												?>
                                            </td>
                                            <td>
                                            	<input type="hidden"
                                                		name="<?php echo 'radius_old['.$location->id.']'; ?>"
                                                        id="<?php echo 'radius_old['.$location->id.']'; ?>"
                                                        value="<?php echo $location->radius; ?>" />
												<?php
													print form::input('radius['.$location->id.']',$location->radius, ' class="text short2" ');
												?>
                                            </td>
						         		</tr>
                                        <?php } ?>
                                        <?php for($i = 0; $i < 3; $i++) { ?>
                                        <tr>
							        		<td>
												<?php
													print form::input('placename_new['.$i.']','', ' class="text short2" ');
												?>
                                            </td>
                                            <td>
												<?php
													print form::input('radius_new['.$i.']','', ' class="text short2" ');
												?>
                                            </td>
						         		</tr>
                                        <?php } ?>
						        	</table>
                                 </td>
						      </tr>
                              <tr>
                              	<td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><input type="image" src="<?php echo url::base() ?>media/img/admin/btn-save.gif" class="save-rep-btn" /></td>
                              </tr>
						  </table>
					  </div>
					<?php print form::close(); ?>
				</div>
			</div>