<?php
/**
 * @version		$Id: controller.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die();

class CommunitySurveysController {

	function execute() {

		$app = JFactory::getApplication();
		$task = $app->input->getCmd('task');
		$controller = 'survey';

		switch ($task){

			case 'latest':
			case 'popular':
			case 'search':
			case 'feed':
				
				$controller = 'survey';
				break;

			case 'take_survey':
			case 'response_form':
			case 'save_response':
			case 'previous_page':
			case 'view_result':
			case 'end_message':
			case 'ajx_save_response':

				$controller = 'response';
				break;

			case 'form':
			case 'edit':
			case 'save':
			case 'save_qn':
			case 'delete_qn':
			case 'move_qn':
			case 'new_page':
			case 'delete_page':
			case 'update_order':
			case 'finalize':
			case 'get_tags':
			case 'upload_answer_image':
			case 'save_rule':
			case 'remove_rule':
			case 'copy':

				$controller = 'form';
				break;
				
			case 'invite':
			case 'save_group':
			case 'delete_group':
			case 'get_contacts':
			case 'save_contacts':
			case 'delete_contacts':
			case 'import_contacts':
			case 'assign_contacts':
			case 'invite_contact_group':
			case 'search_users':
			case 'invite_registered_users':
			case 'invite_registered_groups':
			case 'invite_js_groups':
			case 'get_urls_list':
			case 'create_unique_urls':
				
				$controller = 'invite';
				break;

			case 'dashboard':
			case 'responses':
			case 'consolidated':
			case 'csvdownload':
			case 'pdfdownload':
			case 'location_report':
			case 'device_report':
			case 'os_report':
			case 'view_response':
			case 'remove_responses':

				$controller = 'reports';
				break;

			case 'my_surveys':
			case 'my_responses':
				
				$controller = 'user';
				break;
		}

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/media/'.S_APP_NAME.'/css/cj.surveys.min.css');
		$document->addScript(JURI::root(true).'/media/'.S_APP_NAME.'/js/cj.surveys.min.js');

		require_once JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
		$classname = 'CommunitySurveysController'.JString::ucfirst($controller);
		$controller = new $classname( );
		$controller->execute( $task );
		
		$controller->redirect();
	}
}
?>
