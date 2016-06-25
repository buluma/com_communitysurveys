<?php
/**
 * @version		$Id: invite.php 01 2013-02-01 11:37:09Z maverick $
 * @package		corejoomla.surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class CommunitySurveysControllerInvite extends JControllerLegacy {
	
	function __construct(){
		
		parent::__construct();
		
		$this->registerDefaultTask('get_invite_page');
		$this->registerTask('save_group', 'save_contact_group');
		$this->registerTask('delete_group', 'delete_contact_group');
		$this->registerTask('get_contacts', 'get_contacts');
		$this->registerTask('save_contacts', 'save_contacts');
		$this->registerTask('delete_contacts', 'delete_contacts');
		$this->registerTask('import_contacts', 'import_contacts');
		$this->registerTask('assign_contacts', 'assign_contacts');
		$this->registerTask('invite_contact_group', 'invite_contact_group');
		$this->registerTask('search_users', 'search_users');
		$this->registerTask('invite_registered_users', 'invite_registered_users');
		$this->registerTask('invite_registered_groups', 'invite_registered_user_groups');
		$this->registerTask('invite_js_groups', 'invite_jomsocial_groups');
		$this->registerTask('get_urls_list', 'get_unique_urls_list');
		$this->registerTask('create_unique_urls', 'create_unique_urls');
	}

	public function get_invite_page() {

		$user = JFactory::getUser();
		
		if($user->guest) {
			
			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			
			$app = JFactory::getApplication();
			$model = $this->getModel('survey');
			$usermodel = $this->getModel('users');
			$survey_id = $app->input->getInt('id', 0);
		
			if(($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)) && $model->authorize_survey($survey_id)){
		
				$view = $this->getView('invite', 'html');
				$view->setModel($model, true);
				$view->setModel($usermodel, false);
				$view->display();
			}
		}
	}
	
	public function save_contact_group(){

		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){

				$app = JFactory::getApplication();
				$group_name = trim($app->input->post->getString('group-name', null));
		
				if(empty($group_name)) {
		
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').' <br>Error: 100120'));
				}else {
						
					$model = $this->getModel('survey');
					$group = $model->add_contact_group($group_name);
						
					if(!empty($group)){
		
						echo json_encode(array('group'=>$group));
					}else{
		
						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '') ));
					}
				}
			} else {
		
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
		
		jexit();
	}
	
	public function delete_contact_group(){
	
		$user = JFactory::getUser();
	
		if($user->guest) {
	
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
	
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
	
				$app = JFactory::getApplication();
				$gid = $app->input->getInt('gid', 0);
	
				if(!$gid) {
	
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').' <br>Error: 100130'));
				}else {
						
					$model = $this->getModel('survey');
					
					if($model->delete_contact_group($gid)){

						echo json_encode(array('data'=>1));
					}else{
						
						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '') ));
					}
				}
			} else {
				
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
	
		jexit();
	}
	
	public function save_contacts(){
		
		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
				
				$app = JFactory::getApplication();
				$contacts = $app->input->post->getArray(array('contacts'=>'array'));
				$contacts = $contacts['contacts'];
				
				$model = $this->getModel('survey');
				$count = $model->add_contacts($contacts);
				$count = !empty($count) ? $count : 0;
				
				echo json_encode(array('message'=>JText::sprintf('MSG_CONTACTS_ADDED', $count)));
			} else {
				
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
		
		jexit();
	}
	
	public function delete_contacts(){
		
		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
		
				$app = JFactory::getApplication();
				$cid = $app->input->post->getArray(array('cid'=>'array'));
				$cids = $cid['cid'];
				
				$model = $this->getModel('survey');
				$count = $model->delete_contacts($cids);
				
				if($count > 0){
					
					echo json_encode(array('data'=>$count));
				} else {
					
					echo json_encode(array('error'=>JText::_('MSG_NO_CONTACTS_DELETED').(S_DEBUG_ENABLED ? $model->getError() : '') ));
				}
			} else {
				
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
		
		jexit();
	}
	
	public function create_unique_urls(){

		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
		
				$model = $this->getModel('survey');
				$app = JFactory::getApplication();
		
				$sid = $app->input->getInt('id', 0);
				$num_urls = $app->input->getInt('num_urls', 0);
		
				if($model->create_survey_keys($sid, $num_urls, true)){
					
					echo json_encode(array('data'=>1));
				} else {
					
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				}
			} else {
		
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
		
		jexit();
	}

	public function get_unique_urls_list(){

		$user = JFactory::getUser();
	
		if($user->guest) {
	
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
	
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
	
				$model = $this->getModel('survey');
				$app = JFactory::getApplication();
	
				$sid = $app->input->getInt('id', 0);
				$pid = $app->input->getInt('pid', 0);
				$limit = $app->input->getInt('limit', 20);
				$limit = ($limit > 0 && $limit <= 100) ? $limit : 20;
				$start = $pid * $limit;
				
				$urls = $model->get_survey_keys($sid, $start, $limit);
				$surveys_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=survey');
				
				foreach ($urls as &$url){
					
					$url->url = JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&key='.$url->key_name.$surveys_itemid, false, -1);
				}
				
				echo json_encode(array('urls'=>$urls));
			} else {
	
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
	
		jexit();
	}
	
	public function get_contacts(){
		
		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
				
				$model = $this->getModel('survey');
				$app = JFactory::getApplication();
				
				$pid = $app->input->getInt('pid', 0);
				$gid = $app->input->getInt('gid', 0);
				$limit = $app->input->getInt('limit', 20);
				$search = $app->input->getString('search', null);
				
				$limit = ($limit > 0 && $limit <= 100) ? $limit : 20;
				$start = $pid * $limit;
				$contacts = $model->get_contacts($user->id, 3, $gid, true, $start, $limit, $search);
				
				if(!empty($contacts)){

					echo json_encode(array('contacts'=>$contacts));
				} else {
					
					echo json_encode(array('error'=>JText::_('TXT_NO_RESULTS_FOUND').(S_DEBUG_ENABLED ? $model->getError() : '') ));
				}
			} else {
				
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
		
		jexit();
	}
	
	public function import_contacts(){
	
		$user = JFactory::getUser();
		$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if(!$xhr) echo '<textarea>';
	
		if($user->authorise('core.create', S_APP_NAME)){
				
			$params = JComponentHelper::getParams(S_APP_NAME);
			$allowed_extensions = $params->get('allowed_image_types', '');
			$allowed_size = ((int)$params->get('max_attachment_size', 1024))*1024;
			$input = JFactory::getApplication()->input;

			$tmp_file = $input->files->get('input-attachment');

			if($tmp_file['error'] > 0){

				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
			} else {

				$temp_file_path = $tmp_file['tmp_name'];
				$temp_file_name = $tmp_file['name'];
				$temp_file_ext = JFile::getExt($temp_file_name);
					
				if (!in_array(strtolower($temp_file_ext), array('csv'))){

					echo json_encode(array('error'=>JText::_('MSG_INVALID_FILETYPE')));
				} else if ($tmp_file['size'] > $allowed_size){

					echo json_encode(array('error'=>JText::_('MSG_MAX_SIZE_FAILURE')));
				} else {
					
					if(($handle = fopen($temp_file_path, 'r')) !== FALSE) {
						
						set_time_limit(0);
						$row = 0;
						$contacts = array();
						
						while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
							
							if(count($data) > 1){
								
								array_push($contacts, array('name'=>trim($data[0]), 'email'=>trim($data[1])));
							}
						}
						
						fclose($handle);
						
						$model = $this->getModel('survey');
						$count = $model->add_contacts($contacts);
						$count = !empty($count) ? $count : 0;
						
						echo json_encode(array('message'=>JText::sprintf('MSG_CONTACTS_ADDED', $count)));
					}
				}
			}
				
		} else {
				
			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		}
	
		if(!$xhr) echo '</textarea>';
	
		jexit();
	}
	
	public function assign_contacts(){

		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){

				$app = JFactory::getApplication();
				$gid = $app->input->getInt('gid', 'int');
				$cid = $app->input->post->getArray(array('cid'=>'array'));
				$cids = $cid['cid'];
		
				$model = $this->getModel('survey');
				
				if($count = $model->assign_contacts($gid, $cids)){
						
					echo json_encode(array('message'=>JText::sprintf('TXT_CONTACTS_ASSIGNED', $count)));
				} else {
						
					echo json_encode(array('error'=>JText::_('MSG_NO_CONTACTS_DELETED').(S_DEBUG_ENABLED ? $model->getError() : '') ));
				}
			} else {
		
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
		
		jexit();
	}

	public function search_users(){
	
		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
				
				$model = $this->getModel('users');
				$search = JFactory::getApplication()->input->getString('search', null);
				$users = array();
				
				if(!empty($search)){
				
					$users = $model->get_all_user_names($search);
				}
				
				echo json_encode(array('data'=>$users));
			}
		}
		
		jexit();
	}
	
	public function invite_contact_group(){
	
		$user = JFactory::getUser();
	
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){

				$app = JFactory::getApplication();
				$model = $this->getModel('survey');
				
				$sid = $app->input->getInt('id', 0);
				$gid = $app->input->getInt('gid', 0);
				
				if(!$sid || !$gid) {
					
					echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
					jexit();
				}
				
				$contacts = $model->get_contacts($user->id, 2, $gid, true);
				$this->send_invitations($sid, $contacts);
			} else {
				
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
	
		jexit();
	}

	function invite_registered_user_groups(){
	
		$user = JFactory::getUser();
	
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){

				$app = JFactory::getApplication();
				$model = $this->getModel('users');
				
				$sid = $app->input->getInt('id', 0);
				$gids = $app->input->post->getArray(array('gid'=>'array'));
				$gids = $gids['gid'];
				JArrayHelper::toInteger($gids);
				
				$contacts = $model->get_registered_users_by_gid($gids);
				
				if(!empty($contacts)){

					$this->send_invitations($sid, $contacts);
				} else {
					
					echo json_encode(array('error'=>JText::_('MSG_NO_USERS_IN_GROUPS') ));
				}
			} else {
	
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
	
		jexit();
	}

	public function invite_registered_users(){

		$user = JFactory::getUser();
		
		if($user->guest) {
		
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
		
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
		
				$app = JFactory::getApplication();
				$model = $this->getModel('users');
		
				$sid = $app->input->getInt('id', 0);
				$cid = $app->input->post->getArray(array('cid'=>'array'));
				$cid = $cid['cid'];
				
				$contacts = $model->get_registered_users($cid);
				$this->send_invitations($sid, $contacts);
			} else {
		
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
		
		jexit();
	}

	public function invite_jomsocial_groups(){
	
		$user = JFactory::getUser();
	
		if($user->guest) {
	
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
	
			if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
	
				$app = JFactory::getApplication();
				$model = $this->getModel('users');
	
				$sid = $app->input->getInt('id', 0);
				$gid = $app->input->post->getArray(array('gid'=>'array'));
				$gid = $gid['gid'];
	
				$contacts = $model->get_jomsocial_users($gid);
				$this->send_invitations($sid, $contacts);
			} else {
	
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
	
		jexit();
	}
	
	private function send_invitations($sid, $contacts, $remaining = 0){
		
		if(!empty($contacts)){

			$app = JFactory::getApplication();
			$user = JFactory::getUser();
			$model = $this->getModel('survey');
			$params = JComponentHelper::getParams(S_APP_NAME);

			$itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=survey');
			$editor = $user->authorise('core.wysiwyg', S_APP_NAME) ? $params->get('default_editor', 'bbcode') : 'none';
			$subject = $app->input->getString('invitation-subject', JText::_('TXT_INVITE_DEFAULT_SUB'));
			$default_body = $editor == 'wysiwyg' ? str_replace("\n", '<br>', JText::_('TXT_INVITE_DEFAULT_BODY')) : JText::_('TXT_INVITE_DEFAULT_BODY');
			$body = CJFunctions::get_clean_var('invitation-body', true, $default_body);
			$body = CJFunctions::process_html($body, ($editor == 'bbcode'));
			$messageid = $app->input->getInt('messageid', 0);
		
			$count = count($contacts);
			$keys = $model->create_survey_keys($sid, $count);
		
			if(!empty($keys) && count($keys) > 0){
		
				$emails = array();
		
				foreach ($keys as $i=>$key) {
		
					$link = JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&key='.$key.$itemid, false, -1);
					$link = '<a href="'.$link.'">'.$link.'</a>';
						
					$email = new stdClass();
					$email->name = $contacts[$i]->name;
					$email->subid = 0;
					$email->link = $link;
					$email->email = $contacts[$i]->email;
						
					$emails[] = $email;
					$i++;
				}
		
				$template = $params->get('mail-tpl-newanswer', 'mail-blue.tpl');
				$sent = $model->add_messages_to_queue($sid, $subject, $body, $emails, $template, $messageid);
		
				if($sent === false){
						
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '') ));
				} else {
						
					echo json_encode(array('message'=>JText::sprintf('MSG_INVITATIONS_ADDED_TO_QUEUE', $sent), 'remaining'=>$remaining));
				}
			}else{
		
				echo json_encode(array('error'=>JText::_('MSG_NO_CREDITS')));
			}
		}else{
		
			echo json_encode(array('error'=>JText::_('MSG_NO_CONTACTS_SELECTED') ));
		}
	}
}