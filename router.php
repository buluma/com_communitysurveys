<?php
/**
 * @version		$Id: router.php 01 2012-12-07 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

/*
 * Function to convert a system URL to a SEF URL
*/
function CommunitySurveysBuildRoute(&$query) {
	
    static $items;

    $segments	= array();
    
    if(isset($query['task'])) {
    	
        $segments[] = $query['task'];
        unset($query['task']);
    }
    
    if(isset($query['id'])) {
    	
        $segments[] = $query['id'];
        unset($query['id']);
    }
    
    if(isset($query['catid'])) {
    	
        $segments[] = $query['catid'];
        unset($query['catid']);
    }
    
	unset($query['view']);
    
	return $segments;
}
/*
 * Function to convert a SEF URL back to a system URL
*/
function CommunitySurveysParseRoute($segments) {
	
    $vars = array();
    
    if(count($segments) > 1) {
    	 
    	$vars['task']	= $segments[0];
    	$vars['id']     = $segments[1];
    	$vars['catid']     = $segments[1];
    } else  if(count($segments) > 0){
    	
    	$vars['task']  = $segments[0];
    	$vars['catid']	= $segments[0];
    }

    return $vars;
}
?>