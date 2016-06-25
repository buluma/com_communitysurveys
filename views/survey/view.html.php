<?php
/**
 * @version		$Id: view.html.php 01 2013-01-13 11:37:09Z maverick $
 * @package		CoreJoomla.Survey
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );

class CommunitySurveysViewSurvey extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
	protected $canDo;
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		
		$model = $this->getModel();
		$users_model = $this->getModel('users');
		$categories_model = $this->getModel('categories');
		
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

		$limit = $this->params->get('list_length', $app->getCfg('list_limit', 20));
		$limitstart = $app->input->getInt('start', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$catid = $app->input->getInt('id', 0);
		
		if(!$catid){
				
			$menuid = CJFunctions::get_active_menu_id(false);
			$menuparams = $app->getMenu()->getParams( $menuid );
			$catid = (int)$menuparams->get('catid', 0);
			$app->input->set('catid', $catid);
		}
		
		$app->input->set('cscatid', $catid); //set this only if it is not tags or search
		
		switch($this->action){

			case 'all_surveys':
			
				$options = array('catid'=>$catid, 'limit'=>$limit, 'limitstart'=>$limitstart);
				$return = $model->get_surveys(3, $options, $this->params);
				$users_model->load_users_from_items($return['surveys'], $this->params->get('user_avatar', 'none'));
			
				$this->assignRef('items', $return['surveys']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
			
				if($this->params->get('display_cat_list', 1) == 1){
			
					$categories = $categories_model->get_categories($catid);
					$this->assignRef('categories', $categories);
				}
			
				$this->assign('task', '');
				$this->assign('page_id', 10);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', 'index.php?option='.S_APP_NAME.'&view=survey'.$itemid);
				$this->assign('page_url', 'index.php?option='.S_APP_NAME.'&view=survey&task=all'.$itemid);
			
				$page_heading = JText::_('LBL_ALL_SURVEYS');
				break;

			case 'latest_surveys':
				
				$options = array('catid'=>$catid, 'limit'=>$limit, 'limitstart'=>$limitstart);
				$return = $model->get_surveys(1, $options, $this->params);
				$users_model->load_users_from_items($return['surveys'], $this->params->get('user_avatar', 'none'));
				
				$this->assignRef('items', $return['surveys']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);

				if($this->params->get('display_cat_list', 1) == 1){
						
					$categories = $categories_model->get_categories($catid);
					$this->assignRef('categories', $categories);
				}
				
				$this->assign('task', '');
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', 'index.php?option='.S_APP_NAME.'&view=survey'.$itemid);
				$this->assign('page_url', 'index.php?option='.S_APP_NAME.'&view=survey'.$itemid);
				
				$page_heading = JText::_('LBL_LATEST_SURVEYS');
				break;

			case 'popular_surveys':
			
				$options = array('catid'=>$catid, 'limit'=>$limit, 'limitstart'=>$limitstart);
				$return = $model->get_surveys(2, $options, $this->params);
				$users_model->load_users_from_items($return['surveys'], $this->params->get('user_avatar', 'none'));
			
				$this->assignRef('items', $return['surveys']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
			
				if($this->params->get('display_cat_list', 1) == 1){
			
					$categories = $categories_model->get_categories($catid);
					$this->assignRef('categories', $categories);
				}
			
				$this->assign('task', '');
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', 'index.php?option='.S_APP_NAME.'&view=survey'.$itemid);
				$this->assign('page_url', 'index.php?option='.S_APP_NAME.'&view=survey&task=popular'.$itemid);
			
				$page_heading = JText::_('LBL_POPULAR_SURVEYS');
				break;

			case 'search_surveys':
				
				$search_keywords = $app->input->getString('q', ''); // search keywords
				$search_username = $app->input->getString('u', ''); // username
				$search_query_type = $app->input->getInt('qt', 0); // query type, search in titles or titles+description
				$search_exact_match = $app->input->getInt('m', 0); // exact match of username
				$match_all_words = $app->input->getInt('all', 0); // search in all
				$search_type = $app->input->getInt('type', 0); // search in all
				$search_order = $app->input->getInt('ord', 0); // search order type
				$search_order_dir = $app->input->getInt('dir', 0); // direction
				$search_categories = $app->input->getArray(array('cid'=>'array')); // category ids
				
				JArrayHelper::toInteger($search_categories['cid']);
				$search_categories = array_filter($search_categories['cid']);
				
				$order = $search_order == 1 ? 'a.responses' : 'a.created';
				$order_dir = $search_order_dir == 0 ? 'asc' : 'desc';
				$search_params = array('q'=>$search_keywords, 'u'=>$search_username, 'qt'=>$search_query_type, 'm'=>$search_exact_match, 'type'=>$search_type, 'all'=>$match_all_words);
				
				$options = array(
						'catid'=>$search_categories, 
						'search_params'=>$search_params,
						'limit'=>$limit, 
						'limitstart'=>$limitstart, 
						'order'=>$order, 
						'order_dir'=>$order_dir);
				
				$return = $model->get_surveys(7, $options, $this->params);
				
				$query = 
					'&q='.$search_keywords.
					'&u='.$search_username.
					'&qt='.$search_query_type.
					'&m='.$search_exact_match.
					'&type='.$search_type.
					'&ord='.$search_order.
					'&dir='.$search_order_dir.
					(!empty($search_categories) ? '&cid[]='.implode('&cid[]=', $search_categories) : '');
				
				$this->assignRef('items', $return['surveys']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
				
				if($this->params->get('display_cat_list', 1) == 1){
				
					$this->assign('categories', array());
				}
				
				$this->assign('task', null);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', 'index.php?option='.S_APP_NAME.'&view=survey');
				$this->assign('page_url', 'index.php?option='.S_APP_NAME.'&view=survey&task=search'.$query.$itemid);
				
				$page_heading = JText::_('LBL_SEARCH');
				
				break;
		}
		
		if($catid > 0){
			
			$category = JCategories::getInstance('Communitysurveys')->get($catid);
			$this->assignRef('category', $category);
			
			if(!empty($category)){

				// breadcrumbs
				if(!in_array($this->action, array('search'))){
					
					$temp = $category;

					while ($temp && $temp->id > 1){
					
						$pathway->addItem($temp->title, JRoute::_($this->page_url.'&id='.$temp->id.':'.$temp->alias.$itemid));
						$temp = $temp->getParent();
					}
				}
				
				// add to pathway
				$pathway->addItem($page_heading);

				$page_heading = $page_heading . ' - '. $category->title;

				// set browser title
				$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
			}
		}
		
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
	
	private function load_users($surveys){
		
		if(empty($surveys)) return;
		
		$ids = array();
		
		foreach($surveys as $survey){
			
			$ids[] = $survey->created_by;
		}
		
		if(!empty($ids)){
			
			CJFunctions::load_users($this->params->get('user_avatar'), $ids);
		}
	}
}