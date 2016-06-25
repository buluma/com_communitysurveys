<?php
/**
 * @version		$Id: header.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();
$user_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=user');
$surveys_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=survey');
$catparam = !empty($catparam) ? $catparam : '';
?>

<?php if($this->params->get('display_toolbar', 1) == 1 && !$this->print && $page_id >= 0):?>
<div class="navbar">
	<div class="navbar-inner">
		<div class="header-container">

			<a class="btn btn-navbar" data-toggle="" data-target=".cq-nav-collapse"> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span>
			</a>
			 
			<a class="brand hidden" href="<?php echo JRoute::_(!empty($this->brand_url) ? $this->brand_url : '#');?>">
				<?php echo $this->escape($this->brand);?>
			</a>

			<!--create a survey -->
			<a class="brand" href="index.php?option=com_communitysurveys&view=form&task=form">
				<?php echo 'Add New'?>
			</a>
			<!-- create a survey -->

			<div class="cq-nav-collapse nav-collapse collapse">
				<ul class="nav">
					<li class="dropdown<?php echo $page_id > 0 && $page_id < 5 ? ' active' : ''?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('LBL_DISCOVER');?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header"><?php echo JText::_('LBL_SURVEYS');?></li>
							<li>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$catparam.$surveys_itemid);?>">
									<i class="icon-tasks"></i> <?php echo JText::_('LBL_LATEST_SURVEYS');?>
								</a>
							</li>
							<li>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=popular'.$catparam.$surveys_itemid);?>">
									<i class="icon-eye-open"></i> <?php echo JText::_('LBL_POPULAR_SURVEYS');?>
								</a>
							</li>
						</ul>
					</li>
				</ul>
				
				<ul class="nav pull-right">
				
					<?php if(!$user->guest && $user->authorise('core.create', S_APP_NAME)):?>
					<li<?php echo $page_id == 6 ? ' class="active"' : '';?>>
						<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=form'.$itemid);?>"><?php echo JText::_('LBL_CREATE_SURVEY')?></a>
					</li>
					<?php endif;?>
					
					<?php if($user->authorise('core.manage', S_APP_NAME)):?>
					<li<?php echo $page_id == 10 ? ' class="active"' : '';?>>
						<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=all'.$itemid);?>"><?php echo JText::_('LBL_ALL_SURVEYS')?></a>
					</li>
					<?php endif;?>
					
					<?php if(!$user->guest && !empty($user_itemid)):?>
					<li class="divider-vertical"></li>
					
					<li class="dropdown<?php echo $page_id > 6 && $page_id < 10 ? ' active' : '';?>">
					
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('LBL_MY_STUFF');?> <b class="caret"></b></a>
						
						<ul class="dropdown-menu">
						
							<?php if($user->authorise('core.create', S_APP_NAME)):?>
							<li <?php echo $page_id == 7 ? 'class="active"' : ''?>>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=user&task=my_surveys'.$user_itemid);?>">
									<i class="icon-user"></i> <?php echo JText::_('LBL_MY_SURVEYS');?>
								</a>
							</li>
							<?php endif;?>
							
							<?php if($user->authorise('core.respond', S_APP_NAME)):?>
							<li <?php echo $page_id == 8 ? 'class="active"' : ''?>>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=user&task=my_responses'.$user_itemid);?>">
									<i class="icon-check"></i> <?php echo JText::_('LBL_MY_RESPONSES');?>
								</a>
							</li>
							<?php endif;?>
							
						</ul>
						
					</li>
					<?php endif;?>
					
				</ul>
				
			</div>
		</div>
	</div>
</div>
<?php endif;?>