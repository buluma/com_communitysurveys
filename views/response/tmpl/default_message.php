<?php 
/**
 * @version		$Id: default.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = $this->params->get('hide_toolbar_responses', 0) == 1 ? -1 : 8;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$bbcode = $user->authorise('core.wysiwyg', S_APP_NAME) && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;
?>

<div id="cj-wrapper">
	<div<?php echo $this->hide_template == 1 ? ' class="full-screen"' : '';?>>
		<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
		
		<?php echo CJFunctions::load_module_position('surveys-results-top');?>
		
		<h2 class="page-header"><?php echo $this->escape($this->item->title);?></h2>
		<div class="survey-message"><?php echo CJFunctions::process_html($this->item->endtext, $bbcode, $content)?></div>
		
		<?php if($this->hide_template != 1):?>
		<div class="well center margin-top-20">
			<a class="btn btn-primary" data-loading-text="<?php echo JText::_('TXT_LOADING');?>" onclick="jQuery(this).button('loading');" 
				href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid);?>">
				<i class="icon-home icon-white"></i> <?php echo JText::_('LBL_HOME');?>
			</a>
		</div>
		<?php endif;?>
		
		<?php if(!empty($this->item->related)):?>
		<h3 class="page-header margin-top-20"><?php echo JText::_('LBL_RELATED_SURVEYS')?></h3>
		<?php foreach ($this->item->related as $item):?>
		<div class="media clearfix">
			<?php if($this->params->get('user_avatar') != 'none'):?>
			<div class="pull-left margin-right-10 avatar hidden-phone">
				<?php echo CJFunctions::get_user_avatar(
					$this->params->get('user_avatar'), 
					$item->created_by, 
					$this->params->get('user_display_name'), 
					$this->params->get('avatar_size'),
					$item->email,
					array('class'=>'thumbnail tooltip-hover', 'title'=>$item->username),
					array('class'=>'media-object', 'style'=>'height:'.$this->params->get('avatar_size').'px'));?>
			</div>
			<?php endif;?>

			<?php if($this->params->get('display_response_count', 1) == 1):?>
			<div class="pull-left hidden-phone thumbnail num-box">
				<h2 class="num-header"><?php echo $item->responses;?></h2>
				<span class="muted"><?php echo $item->responses == 1 ? JText::_('LBL_RESPONSE') : JText::_('LBL_RESPONSES');?></span>
			</div>
			<?php endif;?>
			
			<div class="media-body">

				<h4 class="media-heading">
					<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&id='.$item->id.':'.$item->alias.$itemid);?>"<?php echo $item->skip_intro == 1? ' rel="nofollow"' : ''?>>
						<?php echo $this->escape($item->title)?>
					</a>
				</h4>
				
				<?php if($this->params->get('display_meta_info', 1) == 1):?>
				<div class="muted">
					<small>
					<?php 
					$category_name = JHtml::link(
						JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&id='.$item->catid.':'.$item->category_alias.$itemid),
						$this->escape($item->category_title));
					$user_name = $item->created_by > 0 
						? CJFunctions::get_user_profile_link($this->params->get('user_avatar'), $item->created_by, $this->escape($item->username))
						: $this->escape($item->username);
					$formatted_date = CJFunctions::get_formatted_date($item->created);
					
					echo JText::sprintf('TXT_LIST_ITEM_META', $user_name, $category_name, $formatted_date);
					?>
					</small>
				</div>
				<?php endif;?>
				
				<div class="muted admin-controls">
					<small>
						<?php if(($user->id == $item->created_by && $user->authorise('core.edit.own', S_APP_NAME)) || $user->authorise('survey.manage', S_APP_NAME)):?>
						<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=form&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_EDIT');?></a>
						<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=edit&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_EDIT_QUESTIONS');?></a>
						<?php endif;?>
						<?php if(($user->id == $item->created_by) || $user->authorise('survey.manage', S_APP_NAME)):?>
						<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=invite&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_INVITE');?></a>
						<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_REPORTS');?></a>
						<?php endif;?>
					</small>
				</div>
			</div>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		
		<?php echo CJFunctions::load_module_position('surveys-results-bottom');?>
	</div>
</div>