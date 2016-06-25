<?php
/**
 * @version		$Id: view.html.php 01 2013-01-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );

class CommunitySurveysViewReports extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		
		$pathway = $app->getPathway();
		$active = $app->getMenu()->getActive();
		$itemid = CJFunctions::get_active_menu_id();
		
		$this->print = $app->input->getBool('print');
		$page_heading = '';
		
		/********************************** PARAMS *****************************/
		$appparams = JComponentHelper::getParams(S_APP_NAME);
		$menuParams = new JRegistry;
		
		if ($active) {
		
			$menuParams->loadString($active->params);
		}
		
		$this->params = clone $menuParams;
		$this->params->merge($appparams);
		/********************************** PARAMS *****************************/

		CJFunctions::load_jquery(array('libs'=>array('fontawesome')));
		
		switch($this->action){

			case 'survey_reports':
			
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);

				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$survey->stats = $model->get_survey_statistics($survey->id);
				$this->assignRef('item', $survey);
			
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
			
				break;

			case 'consolidated_report':
				
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($this->item->title));
				
				break;

			case 'survey_responses':
				
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$result = $model->get_responses($survey->id);
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $survey);
				$this->assignRef('responses', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
				
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
				
				break;

			case 'location_report':
			
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$result = $model->get_location_report($survey->id);
				$responses = empty($result->rows) ? array() : $result->rows;

				$this->assignRef('item', $survey);
				$this->assignRef('locations', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
			
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
			
				break;

			case 'device_report':
					
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$result = $model->get_device_report($survey->id);
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $survey);
				$this->assignRef('devices', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
					
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
					
				break;

			case 'os_report':
					
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$result = $model->get_os_report($survey->id);
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $survey);
				$this->assignRef('oses', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
					
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
					
				break;
				
			case 'view_response':

				$id = $app->input->getInt('id', 0);
				$rid = $app->input->getInt('rid', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$survey->questions = $model->get_questions($survey->id);
				$responses = $model->get_response_details($rid, $survey->id, 0, false);
					
				foreach ($survey->questions as &$question){

					$question->responses = array();
						
					foreach ($responses as $response){
			
						if($question->id == $response->question_id){
								
							$question->responses[] = $response;
						}
					}
				}
			
				$page_heading = JText::sprintf('TXT_RESULTS', $this->escape($survey->title));

				$this->assignRef('item', $survey);
				$this->assign('response_id', $rid);
			
				break;
		}
		
		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', 'index.php?option='.S_APP_NAME.'&view=survey'.$itemid);
		
		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
		$pathway->addItem($page_heading);
		
		$title = $this->params->get('page_title', $app->getCfg('sitename'));
		
		if ($app->getCfg('sitename_pagetitles', 0) == 1) {
				
			$document->setTitle(JText::sprintf('JPAGETITLE', $title, $page_heading));
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
				
			$document->setTitle(JText::sprintf('JPAGETITLE', $page_heading, $title));
		} else {
			
			$document->setTitle($page_heading);
		}
		
		// set meta description
		if ($this->params->get('menu-meta_description')){
				
			$document->setDescription($this->params->get('menu-meta_description'));
		}
		
		// set meta keywords
		if ($this->params->get('menu-meta_keywords')){
				
			$document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		
		// set robots
		if ($this->params->get('robots')){
				
			$document->setMetadata('robots', $this->params->get('robots'));
		}
		
		// set nofollow if it is print
		if ($this->print){
				
			$document->setMetaData('robots', 'noindex, nofollow');
		}
		
		parent::display($tpl);
	}
	
	function is_hide_template($config_value, $user_value){
		
		if($config_value == '1'){ // force hide
			
			return true;
		} else if($config_value == '2'){ // force show
			
			return false;
		} else if($user_value == 0){ //user selectible
			
			return true; // hide template as survey display_template value is not set
		} else {
			
			return false;
		}
	}
}