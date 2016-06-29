<?php 
/**
 * @version		$Id: default_registered.php 01 2012-04-30 11:37:09Z maverick $
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
<div class="invite-registered-wrapper">
	
	<div class="margin-top-20">

		<div class="alert alert-error alert-no-user-selected hide"><i class="icon-warning-sign"></i> <?php echo JText::_('MSG_ADD_CONTACTS_TO_CONTINUE')?></div>
		<input id="user-invite" type="text" name="input-username" value="" placeholder="<?php echo JText::_('LBL_TYPE_TO_GET_SUGGESTIONS');?>">

		<table class="item-selector">
			<tr>
				<td class="source-list items" align="center">
					<select name="source_names" id="source_names" multiple="multiple" size="10"></select>
					<div class="source_controls">
						<a href="#" class="btn btn-small btn-success tooltip-hover select_all" onclick="return false;" title="<?php echo JText::_('LBL_SELECT_ALL');?>">
							<i class="icon-ok-circle icon-white"></i>
						</a>
						<a href="#" class="btn btn-small btn-danger tooltip-hover deselect_all" onclick="return false;" title="<?php echo JText::_('LBL_DESELECT_ALL');?>">
							<i class="icon-ban-circle icon-white"></i>
						</a>
					</div>
				</td>
				<td class="controls pad-left-10 pad-right-10" align="center" valign="middle">
					<ul class="unstyled">
						<li>
							<a href="#" class="btn btn-small tooltip-hover to_right" title="<?php echo JText::_('LBL_TO_RIGHT')?>" onclick="return false;">
								<i class="icon-hand-right"></i>
							</a>
						</li>
						<li>
							<a href="#" class="btn btn-small tooltip-hover to_left" title="<?php echo JText::_('LBL_TO_LEFT')?>" onclick="return false;">
								<i class="icon-hand-left"></i>
							</a>
						</li>
						<li>
							<a href="#" class="btn btn-small tooltip-hover all_right" title="<?php echo JText::_('LBL_ALL_RIGHT')?>" onclick="return false;">
								<i class="icon-fast-forward"></i>
							</a>
						</li>
						<li>
							<a href="#" class="btn btn-small tooltip-hover all_left" title="<?php echo JText::_('LBL_ALL_LEFT')?>" onclick="return false;">
								<i class="icon-fast-backward"></i>
							</a>
						</li>
					</ul>
				</td>
				<td class="target-list items" align="center">
					<select name="cid[]" id="target_names" multiple="multiple" size="10"></select>
					<div class="target_controls">
						<a href="#" class="btn btn-small btn-success tooltip-hover select_all" onclick="return false;" title="<?php echo JText::_('LBL_SELECT_ALL');?>">
							<i class="icon-ok-circle icon-white"></i>
						</a>
						<a href="#" class="btn btn-small btn-danger tooltip-hover deselect_all" onclick="return false;" title="<?php echo JText::_('LBL_DESELECT_ALL');?>">
							<i class="icon-ban-circle icon-white"></i>
						</a>
					</div>
				</td>
			</tr>
		</table><div class="margin-top-10 margin-bottom-10">
		<button style="float: right;" type="button" class="btn btn-primary btn-invite-registered-users"><i class="icon-share icon-white"></i> <?php echo JText::_('LBL_INVITE');?></button>
	</div>
	</div> <br />
	<br />
	
	<div style="display: none;">
		<span id="url_search_users"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=search_users'.$itemid)?></span>
		<span id="url_invite_registered_users"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=invite_registered_users&id='.$this->item->id.$itemid)?></span>
	</div>
</div>