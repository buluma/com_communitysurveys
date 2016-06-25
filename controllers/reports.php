<?php
/**
 * @version		$Id: reports.php 01 2013-03-10 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die();
jimport('joomla.application.component.controller');

require_once(CJLIB_PATH.DS.'lib'.DS.'tcpdf'.DS.'config'.DS.'lang'.DS.'eng.php');
require_once(CJLIB_PATH.DS.'lib'.DS.'tcpdf'.DS.'tcpdf.php');

class MYPDF extends TCPDF {

	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Powered by Community Surveys - www.corejoomla.com', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

class CommunitySurveysControllerReports extends JControllerLegacy {
	
	function __construct() {
		
		parent::__construct();
		$this->registerDefaultTask('get_survey_reports');
		$this->registerTask('consolidated', 'get_consolidated_report');
		$this->registerTask('responses', 'get_responses_list');
		$this->registerTask('view_response', 'get_response_details');
		$this->registerTask('remove_responses', 'remove_responses');
		$this->registerTask('location_report', 'get_location_report');
		$this->registerTask('device_report', 'get_device_report');
		$this->registerTask('os_report', 'get_os_report');
		$this->registerTask('csvdownload', 'download_csv_report');
		$this->registerTask('pdfdownload', 'download_pdf_report');
	}

	function get_survey_reports(){
		
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_survey($id)){
				
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
			
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'survey_reports');
				
			$view->display();
		}
	}
	
	function get_consolidated_report(){

		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_survey($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{

			$survey = $model->get_consolidated_report($id);

			if(!empty($survey)){
				
				$view = $this->getView('reports', 'html');
				$view->setModel($model, true);
				$view->assign('action', 'consolidated_report');
				$view->assignRef('item', $survey);
				
				$view->display('consolidated');
			} else {
				
				$msg = JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '');
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$id.$itemid), $msg);
			}
		}
	}
	
	function get_responses_list(){

		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_survey($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{

			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'survey_responses');
		
			$view->display('responses');
		}
	}
	
	function get_response_details(){
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_survey($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{

			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'view_response');
			
			$view->display('response');
		}
	}
	
	function remove_responses(){
		
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_survey($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
			
			$cids = $app->input->post->getArray(array('cid'=>'array'));
			$cids = $cids['cid'];
			JArrayHelper::toInteger($cids);
			$itemid = CJFunctions::get_active_menu_id();
			
			$success = $model->delete_responses($id, $cids);
			$msg = $success ? JText::_('MSG_RESPONSES_DELETED_SUCCESSFULLY') : JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '');
			
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=responses&id='.$id.$itemid), $msg);
		}
	}

	function get_location_report(){

		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_survey($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
		
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'location_report');
		
			$view->display('locations');
		}
	}

	function get_device_report(){
	
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
	
		if(!$id || !$model->authorize_survey($id)){
	
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
	
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'device_report');
	
			$view->display('devices');
		}
	}

	function get_os_report(){
	
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('survey');
		$id = $app->input->getInt('id', 0);
	
		if(!$id || !$model->authorize_survey($id)){
	
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
	
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'os_report');
	
			$view->display('oses');
		}
	}
	
	function download_csv_report(){
	
		$itemid = CJFunctions::get_active_menu_id();
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$survey_id = $app->input->getInt('id', 0);
		$params = JComponentHelper::getParams(S_APP_NAME);
	
		if($user->guest) {
				
			$redirect_url = base64_encode(JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$survey_id.$itemid));
				
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
				
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
	
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid), JText::_('MSG_UNAUTHORIZED'));
			}else{
	
				if(!$survey_id) {
						
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid), JText::_('MSG_UNAUTHORIZED'));
				}else{
						
					$model = $this->getModel('survey');
					$return = $model->get_reponse_data_for_csv($survey_id);
						
					if(empty($return)){
	
						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$survey_id.$itemid), JText::_('MSG_ERROR_PROCESSING').$model->getError());
					}else{
	
						$responses = array();
						$include_email_in_reports = $params->get('include_email_in_reports', 0);
	
						foreach ($return->responses as $response){
								
							$responses[$response->id] = new stdClass();
							$responses[$response->id]->created_by = $response->created_by;
							$responses[$response->id]->created = $response->created;
							$responses[$response->id]->username = $response->username;
							$responses[$response->id]->name = $response->name;
							
							if($include_email_in_reports == 1){
							
								$responses[$response->id]->email = $response->email;
							}
							
							$responses[$response->id]->questions = array();
	
							foreach ($return->questions as $question){
	
								$responses[$response->id]->questions[$question->id] = new stdClass();
								$responses[$response->id]->questions[$question->id]->answer = '';
							}
						}
	
						if(!empty($return->entries)){
								
							foreach ($return->entries as $entry){
	
								if(isset($responses[$entry->response_id]) && isset($responses[$entry->response_id]->questions[$entry->question_id])){
										
									if(!empty($entry->answer)){
	
										if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->answer;
										}else{
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('|'.$entry->answer);
										}
									}
	
									if(!empty($entry->answer2)){
	
										if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->answer2;
										}else{
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('|'.$entry->answer2);
										}
									}
	
									if(!empty($entry->free_text)){
	
										if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->free_text;
										}else{
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('|'.$entry->free_text);
										}
									}
								}
							}
						}
	
						$csv_array = array();
						$string = 'Response ID, User ID, Response Date, Username, User Display Name';
						
						if($include_email_in_reports == 1){
							
							$string = $string.', Email';
						}
	
						foreach ($return->questions as $question){
								
							$string = $string.',"'.str_replace('"', '""', $question->title).'"';
						}
	
						array_push($csv_array, $string);
	
						foreach ($responses as $id => $response){
								
							$string = $id.','.$response->created_by.','.$response->created.',"'.$response->username.'","'.$response->name.'"';
							
							if($include_email_in_reports == 1){
								
								$string = $string .',"'.$response->email.'"';
							}
								
							foreach ($response->questions as $id=>$question){
	
								$string = $string.',"'.str_replace('"', '""', $question->answer).'"';
							}
								
							array_push($csv_array, $string);
						}
	
						$filename = 'survey_'.$survey_id.'_'.date('d-m-Y').'.csv';
						$file = JPATH_ROOT.DS.'tmp'.DS.$filename;
	
						$exts = array('.php','.htm','.html','.ph4','.ph5');
						$found = false;
	
						foreach($exts as $l=>$ext){
								
							if (file_exists('index'.$ext)) {
	
								$found = true;
							}
						}
	
						if(!$found){
								
							$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=dashboard&id='.$survey_id.$itemid), JText::_('MSG_ERROR_PROCESSING'));
						}else{
								
							$fh = fopen($file, 'w') or die("can't open ".$filename." file");
								
							foreach($csv_array as $line){
	
								fwrite($fh, $line."\n");
							}
								
							fclose($fh);
	
							if(!file_exists($file)) die("I'm sorry, the file doesn't seem to exist.");
	
							header('Content-type: text/csv');
							header('Content-Disposition: attachment;filename='.$filename);
								
							readfile($file);
						}
					}
				}
			}
		}
		
		jexit();
	}
	
	function download_pdf_report(){
	
		$itemid = CJFunctions::get_active_menu_id();
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$survey_id = $app->input->getInt('id', 0);
		$params = JComponentHelper::getParams(S_APP_NAME);
	
		if($user->guest) {
				
			$redirect_url = base64_encode(JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$survey_id.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
			return;
		}else {
				
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
	
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid), JText::_('MSG_UNAUTHORIZED'));
				return;
			}else{
	
				if(!$survey_id) {

					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid), JText::_('MSG_UNAUTHORIZED'));
					return;
				}else{
					
					$cids = $app->input->post->getArray(array('cid'=>'array'));
					JArrayHelper::toInteger($cids['cid']);
						
					if(empty($cids['cid'])) {

						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=responses&id='.$survey_id.$itemid, false), JText::_('MSG_SELECT_ITEMS_TO_CONTINUE'));
						return;
					} else {
	
						$model = $this->getModel('survey');
						$return = $model->get_reponse_data_for_csv($survey_id, $cids['cid']);
							
						if(empty($return)){
		
							$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$survey_id.$itemid), JText::_('MSG_ERROR_PROCESSING').$model->getError());
							return;
						}else{
		
							$responses = array();
							$include_email_in_reports = $params->get('include_email_in_reports', 0);
		
							foreach ($return->responses as $response){
									
								$responses[$response->id] = new stdClass();
								$responses[$response->id]->created_by = $response->created_by;
								$responses[$response->id]->created = $response->created;
								$responses[$response->id]->username = $response->username;
								$responses[$response->id]->name = $response->name;
								
								if($include_email_in_reports == 1){
								
									$responses[$response->id]->email = $response->email;
								}
								
								$responses[$response->id]->questions = array();
		
								foreach ($return->questions as $question){
		
									$responses[$response->id]->questions[$question->id] = new stdClass();
									$responses[$response->id]->questions[$question->id]->answer = '';
								}
							}
		
							if(!empty($return->entries)){
									
								foreach ($return->entries as $entry){
		
									if(isset($responses[$entry->response_id]) && isset($responses[$entry->response_id]->questions[$entry->question_id])){
											
										if(!empty($entry->answer)){
		
											if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
													
												$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->answer;
											}else{
													
												$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('<br/>'.$entry->answer);
											}
										}
		
										if(!empty($entry->answer2)){
		
											if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
													
												$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->answer2;
											}else{
													
												$responses[$entry->response_id]->questions[$entry->question_id]->answer .= (' - '.$entry->answer2);
											}
										}
		
										if(!empty($entry->free_text)){
		
											if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
													
												$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->free_text;
											}else{
													
												$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('<br/>'.$entry->free_text);
											}
										}
									}
								}
							}
							
							$response_rows = array();
							
							foreach ($responses as $id=>$response){
								
								$string = '<table class="table table-striped" width="100%">';
								$string = $string.'<tr><th width="30%"><strong>Response ID:</strong></th><td width="70%">'.$id.'</td></tr>';
								$string = $string.'<tr><th><strong>Response Date:</strong></th><td>'.$response->created.'</td></tr>';
								$string = $string.'<tr><th><strong>User ID:</strong></th><td>'.$response->created_by.'</td></tr>';
								$string = $string.'<tr><th><strong>Username:</strong></th><td>'.$response->username.'</td></tr>';
								$string = $string.'<tr><th><strong>User Display Name:</strong></th><td>'.$response->name.'</td></tr>';
								
								if($include_email_in_reports == 1){
									
									$string = $string.'<tr><td><strong>Email:</strong></td><td>'.$response->email.'</td></tr>';
								}
								
								foreach ($return->questions as $question){
									
									$string = $string.'<tr><td colspan="2">&nbsp;<hr></td></tr>';
									$string = $string.'<tr><th colspan="2"><h3>'.$question->title.'</h3></th></tr>';
									
									if(!empty($question->description)){
										$string = $string.'<tr><td colspan="2">'.$question->description.'</td></tr>';
									}
									
									$string = $string.'<tr><td colspan="2">&nbsp;</td></tr>';
									$string = $string.'<tr><td colspan="2">'.$response->questions[$question->id]->answer.'</td></tr>';
								}
								
								$string = $string.'</table>';
								
								array_push($response_rows, $string);
							}
							
							$return->id = $survey_id;
							$this->write_to_pdf_file($return, $response_rows);
	
						}
					} // end 
				}
			}
		}
		
		jexit();
	}
	
	private function write_to_pdf_file($survey, $response_rows){
		
		// create new PDF document
		$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set default header data
		$pdf->SetHeaderData('logo.png', PDF_HEADER_LOGO_WIDTH, $survey->title, '');
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('corejoomla.com');
		$pdf->SetTitle('Survey Report');
		$pdf->SetSubject('Survey Responses Report');
		$pdf->SetKeywords('survey, report');
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		foreach ($response_rows as $i=>$response){
			
			$pdf->AddPage();
			$pdf->writeHTML($response, true, false, true, false, '');
			$pdf->lastPage();
		}
		
		$file = 'survey_'.$survey->id.'_'.date('dmYHis').'.pdf';
		$pdf->Output($file, 'D');
	}
}
?>
