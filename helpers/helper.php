<?php
/**
 * @version		$Id: header.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

class SurveyHelper {
	
	public static function get_question_icon($type){
		
		switch ($type){
			
			case S_PAGE_HEADER:
				return 'icon-magnet';
				
			case S_CHOICE_RADIO:
				return 'icon-adjust';
				
			case S_CHOICE_CHECKBOX;
				return 'icon-check';
				
			case S_CHOICE_SELECT:
				return 'icon-chevron-down';
				
			case S_GRID_RADIO:
				return 'icon-th';
				
			case S_GRID_CHECKBOX:
				return 'icon-th-large';
				
			case S_FREE_TEXT_SINGLE_LINE:
				return 'icon-minus';
				
			case S_FREE_TEXT_MULTILINE:
				return 'icon-align-justify';
				
			case S_FREE_TEXT_PASSWORD:
				return 'icon-qrcode';
				
			case S_FREE_TEXT_RICH_TEXT:
				return 'icon-file';
				
			case S_IMAGE_CHOOSE_IMAGE:
				return 'icon-picture';
				
			case S_IMAGE_CHOOSE_IMAGES:
				return 'icon-film';
		}
	}

	public static function award_points($params, $userid, $action, $reference, $info){

		$functions = null;

		switch ($params->get('points_system', 'none')){

			case 'cjblog':
			case 'touch':
			case 'jomsocial':

				$functions = array(
					'newsurvey'=>'com_communitysurveys.new_survey',
					'response'=>'com_communitysurveys.credits',
					'userresponse'=>'com_communitysurveys.survey_response');

				break;

			case 'aup':

				$functions = array(
					'newsurvey'=>'sysplgaup_new_survey',
					'response'=>'sysplgaup_survey_response',
					'userresponse'=>'sysplgaup_response_points');

				break;

			default:

				return false;
		}

		switch ($action){

			case 1: // new survey

				CJFunctions::award_points($params->get('points_system'), $userid, array(
					'points'=>$params->get('points_on_new_survey', 0),
					'reference'=>$reference,
					'info'=>$info,
					'function'=>$functions['newsurvey']
				));

				break;

			case 2: // new response - charged to author

				CJFunctions::award_points($params->get('points_system'), $userid, array(
					'points'=>$params->get('points_on_new_response', 0),
					'reference'=>$reference,
					'info'=>$info,
					'function'=>$functions['response']
				));
				
				break;

			case 3: // new response - for users
			
				CJFunctions::award_points($params->get('points_system'), $userid, array(
					'points'=>$params->get('points_on_new_response_user', 0),
					'reference'=>$reference,
					'info'=>$info,
					'function'=>$functions['userresponse']
				));
				
				break;
		}
	}
}