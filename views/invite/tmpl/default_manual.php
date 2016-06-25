<?php 
/**
 * @version		$Id: default_manuall.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
$itemid = CJFunctions::get_active_menu_id();
$surveys_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=survey');
?>
<div class="invite-manual-wrapper">
	<p><?php echo JText::_('TXT_SHARE_SURVEY_URL');?></p>
	<div class="well well-small"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&skey='.$this->item->survey_key.$itemid, false, -1);?></div>
	<p><?php echo JText::_('LBL_UNIQUE_SURVEY_URLS_HELP');?></p>
	<p><?php echo JText::_('TXT_CREATE_SURVEY_URLS_HELP');?></p>
	<div class="form-inline">
		<?php echo JText::_('LBL_UNIQUE_URLS');?>: 
		<input type="text" class="input-mini" maxlength="2" name="num-survey-urls">
		<button type="button" class="btn btn-primary btn-create-urls"><?php echo JText::_('LBL_CREATE')?></button>
	</div>
	
	<div class="alert alert-error alert-delete-contacts-error margin-top-10 hide"><i class="icon-warning-sign"></i> <?php echo JText::_('MSG_SELECT_CONTACTS_TO_CONTINUE');?></div>
	
	<table class="table table-striped table-hover table-condensed margin-top-20 table-urls-list">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('LBL_SURVEY_URL')?></th>
				<th><?php echo JText::_('LBL_DATE')?></th>
				<th><?php echo JText::_('LBL_STATUS');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->unique_urls as $i=>$url):?>
			<tr>
				<td width="20"><?php echo $i+1;?></td>
				<td><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&key='.$url->key_name.$surveys_itemid, false, -1)?></td>
				<td><?php echo $url->created;?></td>
				<td>
					<i 
						class="<?php echo $url->response_status == 1 ? 'icon-ok-circle' : 'icon-filter'?>" 
						title="<?php echo $url->response_status == 1 ? JText::_('LBL_COMPLETED') : JText::_('LBL_PENDING');?>"></i>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<ul class="pager">
		<li class="previous disabled">
			<a href="#" onclick="return false;" class="btn-pager-urls-previous disabled">&larr; <?php echo JText::_('LBL_PREVIOUS');?></a>
		</li>
		<li class="next<?php echo count($this->contacts) < 20 ? ' disabled' : '';?>">
			<a href="#" onclick="return false;" class="btn-pager-urls-next"><?php echo JText::_('LBL_NEXT');?> &rarr;</a>
		</li>
	</ul>
	
	<input name="urls-current-page" type="hidden" value="0">
	
	<div style="display: none;">
		<span id="url_get_urls_list"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=get_urls_list&id='.$this->item->id.$itemid);?></span>
		<span id="url_create_unique_urls"><?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=invite&task=create_unique_urls&id='.$this->item->id.$itemid);?></span>
	</div>
</div>