<?php 
/**
 * @version		$Id: default_contacts.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
$itemid = CJFunctions::get_active_menu_id();
?>
<div class="invite-contacts-wrapper">
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-contact-groups" data-toggle="tab"><?php echo JText::_('LBL_CONTACT_GROUPS');?></a></li>
			<li><a href="#tab-contacts" data-toggle="tab"><?php echo JText::_('LBL_CONTACTS');?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab-contact-groups">
				<button type="button" class="btn btn-small btn-success btn-create-contact-group">
					<i class="icon-plus icon-white"></i> <?php echo JText::_('LBL_NEW');?>
				</button>
				<button class="btn btn-small btn-primary btn-invite-contact-group" type="button">
					<i class="icon-share icon-white"></i> <?php echo JText::_('LBL_INVITE');?>
				</button>
				<button type="button" class="btn btn-small btn-danger btn-delete-contact-group">
					<i class="icon-trash icon-white"></i> <?php echo JText::_('LBL_DELETE');?>
				</button>
				
				<div class="alert alert-error alert-delete-group-error margin-top-10 hide">
					<i class="icon-warning-sign"></i> <?php echo JText::_('MSG_SELECT_CONTACT_GROUP_TO_DELETE');?>
				</div>
				
				<div class="contact-groups-list margin-top-20">
					<?php foreach ($this->contact_groups as $group):?>
					<label class="radio">
						<input name="group_id" type="radio" value="<?php echo $group->id?>"> <?php echo $this->escape($group->name),' ('.JText::sprintf('TXT_CONTACTS', $group->contacts).')';?> - 
						<a href="#" onclick="return false;" class="btn-edit-contact-assignments"><?php echo JText::_('TXT_ASSIGN_CONTACTS');?></a>
					</label>
					<?php endforeach;?>
				</div>
			</div>
			<div class="tab-pane" id="tab-contacts">
				<div class="contacts-filter well well-small clearfix">
					<div class="row-fluid">
						<div class="span12">
							<div class="pull-right">
								<select name="filter-limit" size="1" class="input-mini">
									<option value="10">10</option>
									<option value="20" selected="selected">20</option>
									<option value="30">30</option>
									<option value="50">50</option>
									<option value="100">100</option>
								</select>
								<select name="filter-group-id" size="1" class="input-medium">
									<option value="0"><?php echo JText::_('LBL_FILTER_BY_GROUP');?></option>
									<?php foreach ($this->contact_groups as $group):?>
									<option value="<?php echo $group->id?>"><?php echo $this->escape($group->name),' ('.JText::sprintf('TXT_CONTACTS', $group->contacts).')';?></option>
									<?php endforeach;?>
								</select>
								<span class="input-append margin-right-10">
									<input type="text" name="input-search-contacts" class="input-medium" placeholder="<?php echo JText::_('LBL_SEARCH')?>">
									<button type="button" class="btn btn-search-contacts tooltip-hover" title="<?php echo JText::_('LBL_SEARCH')?>"><i class="icon-search"></i> </button>
								</span>
							</div>
							<div class="">
								<button type="button" class="btn btn-info btn-add-contacts tooltip-hover" title="<?php echo JText::_('LBL_ADD_CONTACTS');?>">
									<i class="icon-plus icon-white"></i>
								</button>
								<button type="button" class="btn btn-info btn-assign-contacts tooltip-hover" title="<?php echo JText::_('TXT_ASSIGN_CONTACTS');?>">
									<i class="icon-check icon-white"></i>
								</button>
								<button type="button" class="btn btn-success btn-import-contacts tooltip-hover" title="<?php echo JText::_('LBL_IMPORT_CONTACTS_FROM_CSV_FILE');?>">
									<i class="icon-upload icon-white"></i>
								</button>
								<button type="button" class="btn btn-danger btn-delete-contacts tooltip-hover" title="<?php echo JText::_('LBL_DELETE');?>">
									<i class="icon-trash icon-white"></i> 
								</button>
							</div>
						</div>
					</div>
					<div class="">
						<p><?php echo JText::_('TXT_CSV_IMPORT_HELP');?></p>
					</div>
				</div>
				
				<div class="alert alert-error alert-delete-contacts-error margin-top-10 hide"><i class="icon-warning-sign"></i> <?php echo JText::_('MSG_SELECT_CONTACTS_TO_CONTINUE');?></div>
				
				<table class="table table-striped table-hover table-condensed contacts-list">
					<thead>
						<tr>
							<th width="20">#</th>
							<th width="20"><input type="checkbox" class="chk-global-select-contacts"></th>
							<th><?php echo JText::_('LBL_CONTACT_NAME');?></th>
							<th><?php echo JText::_('LBL_EMAIL_ADDRESS');?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->contacts as $i=>$contact):?>
						<tr>
							<td><?php echo $i + 1;?></td>
							<td><input name="cid[]" type="checkbox" value="<?php echo $contact->id;?>"></td>
							<td><?php echo $this->escape($contact->name);?></td>
							<td><?php echo $this->escape($contact->email);?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				
				<ul class="pager">
					<li class="previous disabled">
						<a href="#" onclick="return false;" class="btn-pager-contacts-previous disabled">&larr; <?php echo JText::_('LBL_PREVIOUS');?></a>
					</li>
					<li class="next<?php echo count($this->contacts) < 20 ? ' disabled' : '';?>">
						<a href="#" onclick="return false;" class="btn-pager-contacts-next"><?php echo JText::_('LBL_NEXT');?> &rarr;</a>
					</li>
				</ul>
				
				<input name="contacts-current-page" type="hidden" value="0">
			</div>
		</div>
	</div>
	
	<div id="group-form-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel"><?php echo JText::_('LBL_CREATE_GROUP');?></h3>
		</div>
		<div class="modal-body">
			<label><?php echo JText::_('LBL_ENTER_NAME');?></label>
			<input type="text" class="input-xlarge" name="group-name" value="" placeholder="<?php echo JText::_('LBL_GROUP_NAME');?>">
			<input type="hidden" name="group-id" value="">
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
			<button class="btn btn-success btn-save-contact-group" aria-hidden="true"><i class="icon-plus icon-white"></i> <?php echo JText::_('LBL_SAVE');?></button>
		</div>
	</div>
	
	<div id="add-contacts-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="addContactModalLabel"><?php echo JText::_('LBL_ADD_CONTACTS');?></h3>
		</div>
		<div class="modal-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?php echo JText::_('LBL_CONTACT_NAME');?></th>
						<th><?php echo JText::_('LBL_EMAIL_ADDRESS');?></th>
					</tr>
				</thead>
				<tbody>
					<?php for($i = 0; $i < 5; $i++):?>
					<tr>
						<td><input name="contact-name" class="input-block-level" type="text" placeholder="<?php echo JText::_('LBL_CONTACT_NAME');?>"></td>
						<td><input name="contact-email" class="input-block-level" type="text" placeholder="<?php echo JText::_('LBL_EMAIL_ADDRESS');?>"></td>
					</tr>
					<?php endfor;?>
				</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
			<button class="btn btn-success btn-save-contacts" aria-hidden="true"><i class="icon-plus icon-white"></i> <?php echo JText::_('LBL_SAVE');?></button>
		</div>
	</div>
	
	<div id="assign-contacts-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="addContactModalLabel"><?php echo JText::_('TXT_ASSIGN_CONTACTS');?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::_('LBL_SELECT_GROUP');?>
			<select name="group-id" size="1">
				<option value="0"><?php echo JText::_('LBL_SELECT_GROUP');?></option>
				<?php foreach ($this->contact_groups as $group):?>
				<option value="<?php echo $group->id?>"><?php echo $this->escape($group->name),' ('.JText::sprintf('TXT_CONTACTS', $group->contacts).')';?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CLOSE');?></button>
			<button class="btn btn-success btn-assign-contacts" aria-hidden="true"><i class="icon-plus icon-white"></i> <?php echo JText::_('LBL_SAVE');?></button>
		</div>
	</div>
	
	<div style="display: none;">
		<span id="url-save-contact-group"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=save_group'.$itemid);?></span>
		<span id="url_delete_contact_group"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=delete_group'.$itemid);?></span>
		<span id="url_save_contacts"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=save_contacts'.$itemid);?></span>
		<span id="url_delete_contacts"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=delete_contacts'.$itemid);?></span>
		<span id="url_get_contacts"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=get_contacts'.$itemid);?></span>
		<span id="url_assign_contacts"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=assign_contacts'.$itemid);?></span>
		<span id="url_invite_contact_group"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=invite_contact_group&id='.$this->item->id.$itemid);?></span>
		<form id="file-upload-form" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=import_contacts'.$itemid);?>" enctype="multipart/form-data" method="post">
			<input name="input-attachment" class="input-file-upload" type="file">
		</form>
		<span id="txt_assign_contacts"><?php echo JText::_('TXT_ASSIGN_CONTACTS');?></span>
	</div>
</div>