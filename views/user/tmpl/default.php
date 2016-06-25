<?php
/**
 * @version		$Id: default.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 7;
$i = 1;
$itemid = CJFunctions::get_active_menu_id();
?>
<div id="cj-wrapper">
	
	<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>
	
	<div class="container-fluid survey-wrapper">
		<div class="row-fluid">
			<div class="span12">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo JText::_('LBL_TITLE');?></th>
							<th><?php echo JText::_('LBL_RESPONSES');?></th>
							<th><?php echo JText::_('LBL_DATE');?></th>
							<th><?php echo JText::_('LBL_STATUS');?></th>
							<th><?php echo JText::_('LBL_EDIT');?></th>
							<th><?php echo JText::_('LBL_EDIT_QUESTIONS');?></th>
							<th><?php echo JText::_('LBL_INVITE');?></th>
							<th><?php echo JText::_('LBL_REPORTS');?></th>
							<th><?php echo JText::_('LBL_COPY');?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($this->items)):?>
						<?php foreach ($this->items as $item):?>
						<tr>
							<td><?php echo $this->pagination->get('limitstart') + ($i++);?></td>
							<td>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=response&task=take_survey&id='.$item->id.':'.$item->alias.$surveys_itemid)?>">
									<?php echo $this->escape($item->title);?>
								</a>
							</td>
							<td><?php echo $item->responses;?></td>
							<td nowrap="nowrap"><?php echo CJFunctions::get_localized_date($item->created);?></td>
							<td align="center" class="center">
								<button class="btn btn-mini <?php echo $item->published == 1 ? 'btn-success' : ($item->published == 2 ? 'btn-warning' : 'btn-danger')?> tooltip-hover" 
									title="<?php echo $item->published == 1 ? JText::_('LBL_PUBLISHED') : ($item->published == 2 ? JText::_('LBL_PENDING') : JText::_('LBL_UNPUBLISHED'));?>">
									<i class="<?php echo $item->published == 1 ? 'icon-ok-circle' : 'icon-ban-circle';?> icon-white"></i>
								</button>
							</td>
							<td>
								<?php if($user->authorise('core.edit.own', S_APP_NAME)):?>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=form&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_EDIT');?></a>
								<?php endif;?>
							</td>
							<td>
								<?php if($user->authorise('core.edit.own', S_APP_NAME)):?>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=edit&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_EDIT_QUESTIONS');?></a>
								<?php endif;?>
							</td>
							<td>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=invite&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_INVITE');?></a>
							</td>
							<td>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=dashboard&id='.$item->id.':'.$item->alias.$itemid)?>"><?php echo JText::_('LBL_REPORTS');?></a>
							</td>
							<td>
								<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=copy&id='.$item->id.':'.$item->alias.$surveys_itemid)?>"><?php echo JText::_('LBL_COPY');?></a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="10">
								<?php 
								echo CJFunctions::get_pagination(
										$this->page_url, 
										$this->pagination->get('pages.start'), 
										$this->pagination->get('pages.current'), 
										$this->pagination->get('pages.total'),
										JFactory::getApplication()->getCfg('list_limit', 20),
										true
									);
								?>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>