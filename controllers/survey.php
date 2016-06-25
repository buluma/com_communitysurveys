<?php
/**
 * @version		$Id: survey.php 01 2013-02-01 11:37:09Z maverick $
 * @package		corejoomla.surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class CommunitySurveysControllerSurvey extends JControllerLegacy {
	
	function __construct(){
		
		parent::__construct();
		
		$this->registerDefaultTask('get_latest_surveys');
		$this->registerTask('all', 'get_all_surveys');
		$this->registerTask('popular', 'get_popular_surveys');
		$this->registerTask('search', 'search_surveys');
	}

	function get_all_surveys() {

		$user = JFactory::getUser();
		
		if($user->guest) {
				
			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=all'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else if($user->authorise('core.manage', S_APP_NAME)){
			
			$view = $this->getView('survey', 'html');
			$model = $this->getModel('survey');
			$users_model = $this->getModel('users');
			$categories_model = $this->getModel('categories');
		
			$view->setModel($model, true);
			$view->setModel($users_model, false);
			$view->setModel($categories_model, false);
			$view->assign('action', 'all_surveys');
			$view->display();
		} else {
			
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}
	}
	
	function get_latest_surveys() {
		
		$view = $this->getView('survey', 'html');
		$model = $this->getModel('survey');
		$users_model = $this->getModel('users');
		$categories_model = $this->getModel('categories');
		
		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->setModel($categories_model, false);
		$view->assign('action', 'latest_surveys');
		$view->display();
	}
	
	function get_popular_surveys() {

		$view = $this->getView('survey', 'html');
		$model = $this->getModel('survey');
		$users_model = $this->getModel('users');
		$categories_model = $this->getModel('categories');
		
		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->setModel($categories_model, false);
		$view->assign('action', 'popular_surveys');
		$view->display();
	}
	
	function search_surveys() {

		$view = $this->getView('survey', 'html');
		$model = $this->getModel('survey');
		$users_model = $this->getModel('users');
		$categories_model = $this->getModel('categories');
		
		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->setModel($categories_model, false);
		$view->assign('action', 'search_surveys');
		$view->display();
	}
}