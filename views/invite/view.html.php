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

class CommunitySurveysViewInvite extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
		
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$document = JFactory::getDocument();
		$pathway = $app->getPathway();
		$user = JFactory::getUser();
		$usermodel = $this->getModel('users');
		
		$active = $app->getMenu()->getActive();
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
		
		$page_heading = JText::_('LBL_INVITE_USERS');
		$pathway->addItem($page_heading);
		$itemid = CJFunctions::get_active_menu_id();
		$id = $app->input->getInt('id', 0);
		
		$survey = $model->get_survey_details($id);
		$contact_groups = $model->get_contact_groups($user->id);
		$credits = $model->check_user_credits();
		$contacts = $model->get_contacts($user->id, 3, 0, true);
		$unique_urls = $model->get_survey_keys($id);
		$jsgroups = $usermodel->get_jomsocial_user_groups($survey->created_by);
		
		$this->assignRef('item', $survey);
		$this->assignRef('contact_groups', $contact_groups);
		$this->assignRef('contacts', $contacts);
		$this->assignRef('credits', $credits);
		$this->assignRef('jsgroups', $jsgroups);
		$this->assignRef('unique_urls', $unique_urls);
		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));
		
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