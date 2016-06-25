<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );

class CommunitySurveysViewForm extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
		
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$document = JFactory::getDocument();
		$pathway = $app->getPathway();
		$user = JFactory::getUser();
		
		$active = $app->getMenu()->getActive();
		$page_heading = JText::_('TXT_CREATE_EDIT_SURVEY');
		$this->print = $app->input->getBool('print');
		
		/********************************** PARAMS *****************************/
		$appparams = JComponentHelper::getParams(S_APP_NAME);
		$menuParams = new JRegistry;
		
		if ($active) {
		
			$menuParams->loadString($active->params);
		}
		
		$this->params = clone $menuParams;
		$this->params->merge($appparams);
		/********************************** PARAMS *****************************/
		
		$id = $app->input->getInt('id', 0);
		$itemid = CJFunctions::get_active_menu_id();
		
		switch ($this->action){
			
			case 'form':
				
				$survey = null;
				
				if(!empty($this->survey)){
					
					$survey = $this->survey;
				}elseif($id) {
					
					$survey = $model->get_survey_details($id);
				} else {
					
					$survey = new stdClass();
					$survey->id = $survey->catid = $survey->duration = $survey->skip_intro = $survey->max_responses = 
						$survey->anonymous = $survey->public_permissions = 0;
					/*$survey->show_answers = $survey->display_template = $survey->multiple_responses = $survey->backward_navigation = 
						$survey->private_survey = $survey->display_notice = $survey->display_progress = $survey->notification = 1;*/
						$survey->show_answers = $survey->display_template = $survey->multiple_responses = $survey->backward_navigation = 
						$survey->private_survey = $survey->display_notice = $survey->display_progress = 1;
						$survey->notification = 0;
					$survey->title = $survey->introtext = $survey->endtext = $survey->custom_header = $survey->alias = $survey->redirect_url = '';
					//$survey->publish_up = $survey->publish_down = '0000-00-00 00:00:00';
					$survey->created_by = $user->id;
					$survey->restriction = '';
					$survey->tags = array();
				}
				
				$this->assignRef('item', $survey);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));
				
				break;
				
			case 'questions':
				
				$page_id = $app->input->getInt('pid', 0);
				$survey = $model->get_survey_details($id, $page_id, false, true, false);
				
				if(!$page_id && !empty($survey->pages)) $page_id = $survey->pages[0];
				
				$survey->questions = $model->get_questions($id, $page_id, true, true);
				$this->assignRef('item', $survey);
				$this->assignRef('pid', $page_id);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));
				
				$tpl = 'questions';
				
				break;
		}
		
		$pathway->addItem($page_heading);
		
		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
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
		
		parent::display($tpl);
	}
}