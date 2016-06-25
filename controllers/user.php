<?php
/**
 * @version		$Id: user.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class CommunitySurveysControllerUser extends JControllerLegacy {
	
	function __construct(){
		
		parent::__construct();
		
		$this->registerDefaultTask('get_my_surveys');
		$this->registerTask('my_responses', 'get_my_responses');
	}
	
	public function get_my_surveys(){
		
		$model = $this->getModel('survey');
		$view = $this->getView('user', 'html');
		
		$view->setModel($model, true);
		$view->assign('action', 'surveys');
		$view->display();
	}
	
	public function get_my_responses(){

		$model = $this->getModel('survey');
		$view = $this->getView('user', 'html');
		
		$view->setModel($model, true);
		$view->assign('action', 'responses');
		$view->display('responses');
	}
}