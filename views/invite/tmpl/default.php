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

$user = JFactory::getUser();

$page_id = 6;
$editor = $user->authorise('core.wysiwyg', S_APP_NAME) ? $this->params->get('default_editor', 'bbcode') : 'none';
CJFunctions::load_jquery(array('libs'=>array('validate','form')));
?>


<div id="cj-wrapper">
	
	<?php //include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
	
	<h2 class="page-header"><?php echo $this->escape($this->item->title);?></h2>

	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<div class="span12">
				<div class="accordion" id="invite-wrapper">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#invite-wrapper" href="#invite-basic-details">
								<?php echo JText::_('LBL_ENTER_BASIC_DETAILS')?>
							</a>
						</div>
						<div id="invite-basic-details" class="accordion-body collapse in">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('basics'); ?>
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#invite-wrapper" href="#invite-manually">
								<?php echo JText::_('LBL_INVITE_MANUALLY')?>
							</a>
						</div>
						<div id="invite-manually" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('manual'); ?>
							</div>
						</div>
					</div>
					<div class="accordion-group hidden">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#invite-wrapper" href="#invite-contact-groups">
								<?php echo JText::_('LBL_INVITE_CONTACT_GROUPS')?>
							</a>
						</div>
						<div id="invite-contact-groups" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('contacts'); ?>
							</div>
						</div>
					</div>
					<?php if($user->authorise('core.manage', S_APP_NAME)):?>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#invite-wrapper" href="#invite-registered-groups">
								<?php echo JText::_('LBL_INVITE_REGISTERED_GROUPS')?>
							</a>
						</div>
						<div id="invite-registered-groups" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('groups'); ?>
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#invite-wrapper" href="#invite-registered-users">
								<?php echo JText::_('LBL_INVITE_REGISTERED_USERS')?>
							</a>
						</div>
						<div id="invite-registered-users" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('users'); ?>
							</div>
						</div>
					</div>
					<?php endif;?>
					<div class="accordion-group hidden">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#invite-wrapper" href="#invite-jomsocial-groups">
								<?php echo JText::_('LBL_INVITE_JOMSOCIAL_GROUPS')?>
							</a>
						</div>
						<div id="invite-jomsocial-groups" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('jomsocial'); ?>
							</div>
						</div>
					</div>
					<?php if($user->authorise('core.manage', S_APP_NAME)):?>
					<div class="accordion-group hidden">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#invite-wrapper" href="#invite-acymailing-groups">
								<?php echo JText::_('LBL_INVITE_ACYMAILING_GROUPS')?>
							</a>
						</div>
						<div id="invite-acymailing-groups" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo $this->loadTemplate('acymailing'); ?>
							</div>
						</div>
					</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	
	<input name="cjpageid" id="cjpageid" type="hidden" value="survey_invite">
		
	<div id="message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
		</div>
		<div class="modal-body"></div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location='<?php echo 'index.php'; ?>'"><?php echo JText::_('LBL_CLOSE');?></button>
			<!--<a href="index.php" class="btn" data-dismiss="modal" aria-hidden="true">
				<i class="icon-remove"></i> <?php echo JText::_('LBL_CLOSE');?>
			</a>-->
		</div>
	</div>
	
	<div id="confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="confirmModalLabel"><?php echo JText::_('LBL_ALERT');?></h3>
		</div>
		<div class="modal-body"><?php echo JText::_('MSG_CONFIRM')?></div>
		<div class="modal-footer">
			<button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <?php echo JText::_('LBL_CLOSE');?></button>
			<button class="btn btn-primary btn-confirm" aria-hidden="true"><i class="icon-thumbs-up icon-white"></i> <?php echo JText::_('LBL_CONFIRM');?></button>
		</div>
	</div>
</div>