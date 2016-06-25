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

$bbcode = ($this->params->get('default_editor', 'bbcode') == 'bbcode') ? true : false;
$content = $this->params->get('process_content_plugins', 0) == 1;
$key_param = !empty($this->item->key) ? '&key='.$this->item->key : '';

$session = JFactory::getSession();
?>

<div id="cj-wrapper">
	<div<?php echo $this->hide_template == 1 ? ' class="full-screen"' : '';?>>
		<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>

		<h2 class="page-header"><?php echo $this->escape($this->item->title);?></h2>
		<div class="survey-description"><?php echo CJFunctions::process_html($this->item->introtext, $bbcode, $content)?></div>
		
		<?php if($this->item->display_notice == '1'):?>
		<div class="well well-small all-caps margin-top-20"><?php echo $this->item->anonymous == '1' ? JText::_('NOTICE_ANONYMOUS_SURVEY') : JText::_('NOTICE_TRACKED_SURVEY');?></div>
		<?php endif;?>

		<form action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=response_form&id='.$this->item->id.':'.$this->item->alias.$key_param.$itemid);?>" method="post">
			
			<?php if($session->get('captcha.'.$this->item->id, 0) == 0 && $this->item->private_survey == 0 && $this->item->skip_intro != 1 && $this->params->get('enable_captcha', 0) == 1):?>
			<div class="margin-top-10">
				<div id="dynamic_recaptcha_1"></div>
				<?php 
				JHtml::_('behavior.framework');
				JPluginHelper::importPlugin('captcha');
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('onInit','dynamic_recaptcha_1');
				?>
			</div>
			<?php endif;?>
			
			<div class="well well-transperant center margin-top-20">
				<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid)?>">
					<?php echo JText::_('LBL_CANCEL');?>
				</a>
				<button type="submit" class="btn btn-primary" data-loading-text="<?php echo JText::_('TXT_LOADING');?>" onclick="jQuery(this).button('loading');">
					<i class="icon-hand-right icon-white"></i> <?php echo JText::_('LBL_CONTINUE');?>
				</button>
			</div>
		</form>
	</div>
</div>