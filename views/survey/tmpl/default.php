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

$catparam = '';
$page_id = isset($this->page_id) ? $this->page_id : 1;

if(!empty($this->category)){

	$catparam = '&catid='.$this->category->id.':'.$this->category->alias;
}
CJFunctions::load_jquery(array('libs'=>array('rating')));
?>

<div id="cj-wrapper">

	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<div class="span12">
			
				<?php echo CJFunctions::load_module_position('surveys-list-above-categories');?>
				
				<?php if($this->params->get('display_cat_list', 1) == 1 || !empty($this->page_header)):?>
				<div class="well">
					
					<?php if(!empty($this->categories) || !empty($this->category)):?>
					<h2 class="page-header margin-bottom-10 no-space-top">
						<?php echo JText::_('LBL_CATEGORIES').(!empty($this->category) ? ': <small>'.$this->escape($this->category->title).'</small>' : '');?>
						
						<?php if($this->params->get('enable_rss_feed', 0) == '1'):?>
						<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=feed&format=feed'.$catparam.$itemid);?>" 
							title="<?php echo JText::_('LBL_RSS_FEED')?>" class="tooltip-hover">
							<i class="cjicon-feed"></i>
						</a>
						<?php endif;?>
					</h2>
					<?php elseif(!empty($this->page_header)):?>
					<h2 class="page-header margin-bottom-10 no-space-top"><?php echo $this->escape($this->page_header);?></h2>
					<?php endif;?>
					
					<?php if(!empty($this->page_description)):?>
					<div class="margin-bottom-10"><?php echo $this->page_description;?></div>
					<?php endif;?>
					
					<?php 
					if($this->params->get('display_cat_list', 1) == 1){
	
						echo CJFunctions::get_joomla_categories_table_markup($this->categories, array(
								'max_columns'=>$this->params->get('num_cat_list_columns', 3), 'max_children'=>0, 'base_url'=>$this->page_url, 'menu_id'=>$itemid));
					} 
					?>
					
					<?php if($this->params->get('dispay_search_box', 1) == 1):?>
					<div class="row-fluid margin-top-10">
						<div class="span12">
							<form action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=search'.$itemid);?>" class="no-margin-bottom">
								<div class="input-append center">
									<input type="text" class="input-xlarge required" name="q" placeholder="<?php echo JText::_('LBL_SEARCH');?>">
									<button type="submit" class="btn"><?php echo JText::_('LBL_SEARCH');?></button>
								</div>
								<?php if(!empty($this->category)):?>
								<input type="hidden" name="catid" value="<?php echo $this->category->id;?>">
								<?php endif;?>
							</form>
						</div>
					</div>
					<?php endif;?>
					
				</div>
				<?php endif;?>
				
				<?php echo CJFunctions::load_module_position('surveys-list-below-categories');?>
				<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
				<?php if(!empty($this->items)):?>
				<?php foreach ($this->items as $item):?>
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
							<?php if($page_id == 10): // all surveys?>
							<i 
								class="<?php echo $item->private_survey == 1 ? 'icon-eye-close' : 'icon-eye-open'?> tooltip-hover" 
								title="<?php echo $item->private_survey == 1 ? JText::_('LBL_PRIVATE_SURVEY') : JText::_('LBL_PUBLIC_SURVEY');?>"></i>
							<?php endif;?>
						</h4>
						
						<?php if($this->params->get('display_meta_info', 1) == 1):?>
						<div class="muted">
							<small>
							<?php 
							$category_name = JHtml::link(
								JRoute::_($this->page_url.'&id='.$item->catid.':'.$item->category_alias.$itemid),
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

								<?/*<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_REPORTS');?></a>*/?>
								<?php endif;?>

								<!--show my surveys -->
									<?php if($user->authorise('core.create', S_APP_NAME)):?>
										<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=user&task=my_surveys'.$user_itemid);?>">
											<i class="icon-user"></i> <?php echo JText::_('LBL_MY_SURVEYS');?>
										</a>
									<?php endif;?>


								<!-- end show surveys -->
							</small>

							<!-- edit reports view -->
								<?php/*
									$user  = JFactory::getUser();

									$user_groups = $user->groups;

									//print_r($user_groups);

									//echo "<p>Your name is {$user->name}, your email is {$user->email}, and your username is {$user->username}</p>";
 
									if ($user->authorise('core.create', 'com_communitysurveys'))
									{
										echo "<p>You may create surveys.</p>";
									}
									else
									{
										echo "<p>You may not create surveys.</p>";
									}

									if ($user->authorise('core.edit', 'com_communitysurveys'))
									{
										echo "<p>You may edit surveys.</p>";
									}
									else
									{
										echo "<p>You may not edit surveys.</p>";
									}
								 
									if ($user->authorise('core.edit.own', 'com_communitysurveys'))
									{
										echo "<p>You may edit your own surveys.</p>";
									}
									else
									{
										echo "<p>You may not edit your own surveys.</p>";
									}
								*/?>
							<!-- end reports -->
						</div>
					</div>
				</div>
				<?php endforeach;?>
				
				<?php echo CJFunctions::load_module_position('surveys-list-above-pagination');?>
				
				<div class="row-fluid">
					<div class="span12">
						<?php 
						echo CJFunctions::get_pagination(
								$this->page_url.$catparam, 
								$this->pagination->get('pages.start'), 
								$this->pagination->get('pages.current'), 
								$this->pagination->get('pages.total'),
								$this->pagination->get('limit'),
								true
							);
						?>
					</div>
				</div>
		
				<?php else:?>
				<div class="alert alert-info"><i class="icon-info-sign"></i> <?php echo JText::_('MSG_NO_RESULTS')?></div>
				<?php endif;?>
				
				<?php echo CJFunctions::load_module_position('surveys-list-below-pagination');?>
			</div>
		</div>
		
		<div id="message-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="myModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
			</div>
		</div>
	</div>
	
	<div style="display: none;">
		<input type="hidden" id="cjpageid" value="survey_list">
		<div id="data-rating-noratemsg"><?php echo JText::_('LBL_RATING_NORATE_HINT');?></div>
		<div id="data-rating-cancelhint"><?php echo JText::_('LBL_RATING_CANCEL_HINT');?></div>
		<div id="data-rating-hints"><?php echo JText::_('LBL_RATING_HINTS');?></div>
	</div>
</div>