<?php
/**
 * @version		$Id: users.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');

class CommunitySurveysModelUsers extends JModelLegacy {

	function __construct() {

		parent::__construct ();
	}
	
	public function load_users_from_items($items, $component){
		
		if(empty($items) || $component == 'none') return;
		
		$ids = array();
		
		foreach($items as $item){
			
			$ids[] = $item->created_by;
		}
		
		if(!empty($ids)){
			
			CJFunctions::load_users($component, $ids);
		}
	}
	
	function get_all_user_names($search = null){
		
		$where = '';
		
		if(!empty($search)){
			
			$where = ' where username like \'%'.$this->_db->escape($search).'%\' or name like \'%'.$this->_db->escape($search).'%\'';
		}
		
		$query = 'select id, name, username from #__users'.$where;
		$this->_db->setQuery($query, 0, 50);
		
		return $this->_db->loadObjectList();
	}
	
	public function get_registered_users($cids){
		
		$query = 'select name, email from #__users u where id in ('.implode(',', $cids).')';
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList();
		
		return !empty($users) ? $users : array();
	}
	
	public function get_registered_users_by_gid($user_groups, $start = 0, $count = 0){
		
		$query = '
				select
					id, email, name 
				from 
					#__users 
				where 
					id in (select distinct(user_id) from #__user_usergroup_map where group_id in ('.implode(',', $user_groups).')) and block=0 and id > '.$start.'
				order by
					id asc';
			
		$this->_db->setQuery($query, 0, $count);
		$users = $this->_db->loadObjectList();
		
		return !empty($users) ? $users : array();
	}
	
	public function get_jomsocial_user_groups($user_id, $all = false){

		if(file_exists(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'userpoints.php')) {
			
			$user = JFactory::getUser();
			$db = JFactory::getDbo();
			
			$where = '';
			if(!$all) $where = ' and m.memberid='.$user->id; 
			
			$query = '
				select
					id, name
				from
					#__community_groups g
				left join
					#__community_groups_members m on g.id = m.groupid
				where
					approved = 1'.$where;
			
			$db->setQuery($query);
			$groups = $db->loadObjectList();
			
			return !empty($groups) ? $groups : array();
		}
		
		return array();
	}
	
	public function get_jomsocial_users($gids, $start = 0, $count = 0){
		
		$users = array();
		
		if(file_exists(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'userpoints.php')) {
		
			$query = '
					select 
						id, email, name 
					from 
						#__users 
					where 
						id in (select memberid from #__community_groups_members where groupid in ('.implode(',', $gids).')) and block=0 and id > '.$start.'
					order by
						id asc';
				
	
			$this->_db->setQuery($query, 0, $count);
			$users = $this->_db->loadObjectList();
		}
		
		return !empty($users) ? $users : array();
	}
}