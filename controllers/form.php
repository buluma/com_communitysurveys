<?php
/**
 * @version		$Id: form.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class CommunitySurveysControllerForm extends JControllerLegacy {
	
	function __construct() {
		
		parent::__construct();
		
		$this->registerDefaultTask('create_edit_survey');
		
		/* create survey */
		$this->registerTask('form', 'create_edit_survey');
		$this->registerTask('edit', 'edit_questions');
		$this->registerTask('save', 'save_survey');
		$this->registerTask('get_tags', 'get_tags');
		$this->registerTask('save_qn','save_question');
		$this->registerTask('delete_qn', 'delete_question');
		$this->registerTask('move_qn','move_question');
		$this->registerTask('new_page','new_page');
		$this->registerTask('delete_page','remove_page');
		$this->registerTask('finalize', 'finalize_survey');
		$this->registerTask('update_order', 'update_ordering');
		$this->registerTask('upload_answer_image', 'upload_answer_image');
		$this->registerTask('save_rule', 'save_conditional_rule');
		$this->registerTask('remove_rule', 'remove_conditional_rule');
		$this->registerTask('copy', 'copy_survey');
	}

	function create_edit_survey(){
		
		$user = JFactory::getUser();
		
		if($user->guest) {
			
			$itemid = CJFunctions::get_active_menu_id();
			
			$redirect_url = base64_encode(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));
			
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			
			$app = JFactory::getApplication();
			$model = $this->getModel('survey');
			
			$id = $app->input->getInt('id', 0);
			
			if($id > 0 && !$this->authorize_survey($id)) {
				
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$survey_itemid, false), JText::_('MSG_UNAUTHORIZED'));
			} else if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				
				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else{
				
				$view = $this->getView('form', 'html');
				$view->setModel($model, true);
				$view->assign('action', 'form');
				
				$view->display();
			}
		}
	}

	function edit_questions(){
	
		$user = JFactory::getUser();
	
		if($user->guest) {
				
			$itemid = CJFunctions::get_active_menu_id();
				
			$redirect_url = base64_encode(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));

			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {

			$app = JFactory::getApplication();
			$id = $app->input->getInt('id', 0);

			if(!$id){

				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else if(!$this->authorize_survey($id)) {
				
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$survey_itemid, false), JText::_('MSG_UNAUTHORIZED'));
			} else {

				$view = $this->getView('form', 'html');
				$model = $this->getModel('survey');

				$view->setModel($model, true);
				$view->assign('action', 'questions');
	
				$view->display();
			}
		}
	}

	function update_ordering(){

		$user = JFactory::getUser();

		if($user->guest) {
				
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			
			$app = JFactory::getApplication();
			$model = $this->getModel('survey');
			$survey_id = $app->input->getInt('id', 0);
			
			if(!$this->authorize_survey($survey_id)) {
		
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{
				
				$pid = $app->input->getInt('pid', 0);
				$ordering = $app->input->getArray(array('ordering'=>'array'));
				
				if(!$survey_id || !$pid || empty($ordering['ordering'])) {
						
					echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105101') );
				}else {
					
					if($model->update_ordering($survey_id, $pid, $ordering['ordering'])) {
				
						echo json_encode(array('return'=>'1'));
					}else {
				
						echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105102'.(S_DEBUG_ENABLED ? $model->getError() : '') ) );
					}
				}
			}
		}
		
		jexit();
	}

	function upload_answer_image(){
	
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$model = $this->getModel('survey');
		
		$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if(!$xhr) echo '<textarea>';
		
		$survey_id = $app->input->getInt('id', 0);
		
		if($this->authorize_survey($survey_id)) {
	
			$params = JComponentHelper::getParams(S_APP_NAME);
			$allowed_extensions = $params->get('allowed_image_types', 'jpg,png,gif');
			$allowed_size = ((int)$params->get('max_attachment_size', 256))*1024;
	
			if(!empty($allowed_extensions)){
	
				$tmp_file = $app->input->files->get('input-attachment');
	
				if($tmp_file['error'] > 0){
	
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				} else {
	
					$temp_file_path = $tmp_file['tmp_name'];
					$temp_file_name = $tmp_file['name'];
					$temp_file_ext = JFile::getExt($temp_file_name);
	
					if (!in_array(strtolower($temp_file_ext), explode(',', strtolower($allowed_extensions)))){
	
						echo json_encode(array('error'=>JText::_('MSG_INVALID_FILETYPE')));
					} else if ($tmp_file['size'] > $allowed_size){
	
						echo json_encode(array('error'=>JText::_('MSG_MAX_SIZE_FAILURE')));
					} else {
	
						$file_name = CJFunctions::generate_random_key(25, 'abcdefghijklmnopqrstuvwxyz1234567890').'.'.$temp_file_ext;
							
						if(JFile::upload($temp_file_path, S_TEMP_STORE.DS.$file_name)){
	
							echo json_encode(array('file_name'=>$file_name, 'url'=>S_TEMP_STORE_URI.$file_name));
						} else {
	
							echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
						}
					}
				}
	
			} else {
	
				echo '{"file_name": null, "url": null}';
			}
	
		} else {
	
			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		}
	
		if(!$xhr) echo '</textarea>';
	
		jexit();
	}
	
	function save_survey(){
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$catid = $app->input->post->getInt('catid', 0);
		
		if($user->authorise('core.create', S_APP_NAME.'.category.'.$catid) || $user->authorise('core.manage', S_APP_NAME)){
			
			$model = $this->getModel('survey');
			$survey = $model->create_edit_survey();
			
			if(empty($survey) || !empty($survey->error)){
				
				JFactory::getApplication()->enqueueMessage($survey->error);
				
				$view = $this->getView('form', 'html');
				$model = $this->getModel('survey');
				
				$view->setModel($model, true);
				$view->assignRef('survey', $survey);
				$view->assign('action', 'form');
				
				$view->display();
			}else{
				
				$itemid = CJFunctions::get_active_menu_id();
				//remove success message
				//$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=edit&id='.$survey->id.':'.$survey->alias.$itemid, false), JText::_('MSG_SURVEY_UPDATED'));
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=edit&id='.$survey->id.':'.$survey->alias.$itemid, false));
			}
		} else {
			
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}
	}

	function fetch_questions() {
		
		$user = JFactory::getUser();
		
		if($user->guest) {
			
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$app = JFactory::getApplication();
			$model = $this->getModel('survey');
			$survey_id = $app->input->getInt('id', 0);
			$pid = $app->input->getInt('pid', 0);
			
			if(!$survey_id || !$pid) {
					
				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
			}else {
				
				if(!$this->authorize_survey($survey_id)) {
				
					echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
				}else{
					
					$questions = $model->get_questions($survey_id, $pid);
					$error = $model->getError();
						
					if($questions) {
				
						echo json_encode(array('questions'=>$questions));
					}else if(!empty($error)) {
				
						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
					}else {
				
						echo json_encode(array('questions'=>array()));
					}
				}
			}
		}
		
		jexit();
	}
	
	function save_question() {
		
		$user = JFactory::getUser();
		
		if($user->guest) {
			
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			
			$id = JFactory::getApplication()->input->getInt('id');
			
			if(!$this->authorize_survey($id)) {
				
				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else{
				
				$model = $this->getModel('survey');
				
				if($qid = $model->save_question()) {
					
					$question = $model->get_question($qid);
					
					if($question){
					
						echo json_encode(array('question'=>$question));
					} else {
						
						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
					}
				}else {
					
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
				}
			}
		}
		
		jexit();
	}

	function new_page() {
		
		$user = JFactory::getUser();
		$itemid = CJFunctions::get_active_menu_id();
		
		if($user->guest) {
			
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			
			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);
			
			if(!$this->authorize_survey($survey_id)) {
				
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{
				
				$model = $this->getModel('survey');
				
				if($survey_id && ($pid = $model->create_page($survey_id))) {
					
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=edit&id='.$survey_id.'&pid='.$pid.$itemid, false), JText::_('MSG_PAGE_CREATED'));
				}else {
					
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=edit&id='.$survey_id.$itemid, false), JText::_('MSG_ERROR_PROCESSING'));
				}
			}
		}
	}

	function remove_page() {
		
		$user = JFactory::getUser();
		
		if($user->guest) {
			
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			
			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);
			
			if(!$this->authorize_survey($survey_id)) {
				
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{
				
				$model = $this->getModel('survey');
				$pid = $app->input->getInt('pid', 0);
				
				if($pid && $model->remove_page($survey_id, $pid)) {
					
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=edit&id='.$survey_id.'&pid=0'.$itemid, false), JText::_('MSG_PAGE_REMOVED'));
				}else {
					
					$msg = JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '');
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=edit&id='.$survey_id.$itemid, false), $msg);
				}
			}
		}
	}

	function move_question() {
	
		$user = JFactory::getUser();
	
		if($user->guest) {
				
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
				
			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);
			
			if(!$this->authorize_survey($survey_id)) {
	
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{
	
				$model = $this->getModel('survey');
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('pid', 0);
	
				if($survey_id && $qid && $pid && $model->move_question($survey_id, $qid, $pid)) {
					
					echo json_encode(array('data'=>1));
				}else {
						
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			}
		}
		
		jexit();
	}
	
	function delete_question(){
		
		$user = JFactory::getUser();
		
		if($user->guest) {
			
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			
			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);
			
			if(!$this->authorize_survey($survey_id)) {
				
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{
				
				$model = $this->getModel('survey');
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('pid', 0);
				
				if($survey_id && $pid && $qid && $model->delete_question($survey_id, $pid, $qid)) {
					
					echo json_encode(array('data'=>1));
				}else {
					
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			}
		}
		
		jexit();
	}
	
	function finalize_survey(){
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = JRequest::getInt('id', 0);
		$itemid = CJFunctions::get_active_menu_id();
		
		$survey = $model->get_survey_details($id, true);
			
		if($user->authorise('core.create', S_APP_NAME.'.category.'.$survey->catid) || $user->authorise('core.manage', S_APP_NAME)){
			
			if($survey->published == 3){
				
				$status = $user->authorise('core.autoapprove', S_APP_NAME.'.category.'.$survey->catid) ? 1 : 2;
				
				if(!$model->finalize_survey($id, $status)){
					
					$error = S_DEBUG_ENABLED ? $model->getError() : '';
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_ERROR_PROCESSING').$error);
				}else{
					
					$config = JComponentHelper::getParams(S_APP_NAME);
					
					if(!$user->authorise('core.autoapprove', S_APP_NAME.'.category.'.$survey->catid)){
						
						if($config->get('admin_new_survey_notification', 1) == 1){
							
							$from = $app->getCfg('mailfrom' );
							$fromname = $app->getCfg('fromname' );
							$admin_emails = $model->get_admin_emails($params->get('admin_user_groups', 8));
							
							if(!empty($admin_emails)){
								
								CJFunctions::send_email($from, $fromname, $admin_emails, JText::_('MSG_MAIL_PENDING_REVIEW_SUBJECT'), JText::_('MSG_MAIL_PENDING_REVIEW_BODY'), 1);
							}
						}
						
						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_SENT_FOR_REVIEW'));
					}else{

						if($survey->private_survey == 0){
							
							$link = JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&id='.$survey->id.":".$survey->alias.$itemid);
							
							CJFunctions::stream_activity(
								$config->get('activity_stream_type', 'none'),
								$user->id,
								array(
										'command' => A_APP_NAME.'.new_survey',
										'component' => S_APP_NAME,
										'title' => JText::sprintf('TXT_CREATED_SURVEY', $link, $survey->title),
										'href' => $link,
										'description' => $survey->introtext,
										'length' => $config->get('stream_character_limit', 256),
										'icon' => 'components/'.S_APP_NAME.'/assets/images/icon-16-survey.png',
										'group' => 'Surveys'
								));
						}
	
						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
					}
				}
			} else {
				
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
			}
		} else {
			
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$survey_itemid, false), JText::_('MSG_UNAUTHORIZED'));
		}
	}
	
	function save_conditional_rule(){
	
		$user = JFactory::getUser();
	
		if($user->guest) {
				
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			
			$model = $this->getModel('survey');
			$app = JFactory::getApplication();
			
			$survey_id = $app->input->getInt('id', 0);
			$rule_data = $app->input->getArray(array('rule'=>'array'));
			$rule_data = $rule_data['rule'];
			
			if($survey_id && !empty($rule_data) && !empty($rule_data['qid']) && $rule_data['type'] >= 1 && $rule_data['type'] <= 4 && $this->authorize_survey($survey_id)){

				$rule = new stdClass();
				$rule_names = array(1=>'answered', 2=>'unanswered', 3=>'selected', 4=>'unselected');
				
				$rule->name = $rule_names[$rule_data['type']];
				$rule->answer_id = !empty($rule_data['answer']) ? $rule_data['answer'] : 0;
				$rule->column_id = !empty($rule_data['column']) ? $rule_data['column'] : 0;
				$rule->finalize = $rule_data['outcome'] == 1 ? 0 : 1;
				$rule->page =  $rule_data['outcome'] == 1 ?  $rule_data['page'] : 0;
				$question_id = $rule_data['qid'];
				
				if(!$rule->name || (!$rule->page && !$rule->answer_id && !$rule->finalize)){

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').' <br>Error: 100108'));
				}else{

					$rule_id = $model->save_conditional_rule($survey_id, $question_id, json_encode($rule));

					if($rule_id > 0){
							
						$rules = $model->get_conditional_rules($survey_id, null, $question_id);
						echo json_encode(array('rules'=>$rules));
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
	
	function remove_conditional_rule(){
	
		$user = JFactory::getUser();
	
		if($user->guest) {
				
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			
			$app = JFactory::getApplication();
			$model = $this->getModel('survey');

			$survey_id = $app->input->getInt('id', 0);
			$question_id = $app->input->getInt('qid', 0);
			$rule_id = $app->input->getInt('rid', 0);

			if($survey_id > 0 && $question_id > 0 && $rule_id > 0 && $this->authorize_survey($survey_id)){
	
				if($model->remove_conditional_rule($survey_id, $question_id, $rule_id)){

					echo json_encode(array('data'=>1));
				}else{

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '') ));
				}
			} else {
				
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}
	
		jexit();
	}
	
	private function authorize_survey($survey_id){

		$user = JFactory::getUser();
		
		if(!$user->authorise('core.manage', S_APP_NAME)){
			
			$model = $this->getModel('survey');
			$survey = $model->get_survey_details($survey_id);
			
			if(!$user->authorise('core.create', S_APP_NAME.'.category.'.$survey->catid)){
				
				return false;
			}
		}
				
		return true;
	}
	
	function copy_survey(){

		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);
		$model = $this->getModel('survey');

		if(!$id){

			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys', JText::_('MSG_ERROR_PROCESSING'));
		} if(!JFactory::getUser()->authorise('core.create', S_APP_NAME) || !$model->authorize_survey($id)){

			return CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 403);
		} else{

			$user_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=user');

			if(!$model->copy_survey($id)){
				
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=user&task=my_surveys'.$user_itemid), JText::_('MSG_ERROR_PROCESSING'));
			} else {

				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=user&task=my_surveys'.$user_itemid), JText::_('MSG_COPY_SUCCESS'));
			}
		}
	}
}
?>
