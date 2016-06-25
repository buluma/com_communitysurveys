<?php
/**
 * @version		$Id: communitysurveys.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die();
defined('S_APP_NAME') or define('S_APP_NAME', 'com_communitysurveys');

// CJLib includes
$cjlib = JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cjlib'.DIRECTORY_SEPARATOR.'framework.php';
if(file_exists($cjlib)){

	require_once $cjlib;
}else{

	die('CJLib (CoreJoomla API Library) component not found. Please download and install it to continue.');
}

CJLib::import('corejoomla.framework.core');
CJLib::import('corejoomla.template.core');

require_once JPATH_COMPONENT.DS.'controller.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'constants.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

$app = JFactory::getApplication();
$params = JComponentHelper::getParams(S_APP_NAME);

define('S_DEBUG_ENABLED', ($params->get('enable_debugging', 0) == '1'));
CJFunctions::load_component_language(S_APP_NAME);

if($params->get('enable_bootstrap', true)){
	
	CJLib::import('corejoomla.ui.bootstrap', true);
} else {
	
	CJFunctions::load_jquery(array('libs'=>array()));
}

$view = $app->input->getCmd('view', 'survey');

if( JFile::exists( JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php' ) ){

	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php');
}else{

	return CJFunctions::throw_error('View '. JString::ucfirst($view) . ' not found!', 500);
}

$controller = new CommunitySurveysController();
$controller->execute();

$format = $app->input->getCmd('format');

if($format != 'raw'){
	
	$return = CJFunctions::send_messages_from_queue();
	
	if($params->get('enable_credits', 1) == '1'){
	
    	echo '';
	}
}