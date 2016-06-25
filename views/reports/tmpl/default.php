<?php 
/**
 * @version		$Id: default_reports.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$page_id = 6;
$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME) ? true : false;
$bbcode =  $wysiwyg && ($this->params->get('default_editor', 'bbcode') == 'bbcode');
$content = $this->params->get('process_content_plugins', 0) == 1;

$document = JFactory::getDocument();
$document->addScript('https://www.google.com/jsapi');

$data = array();
if(!empty($this->item->stats->daily)){
	
	foreach($this->item->stats->daily as $stat){
		
		$data[] = "['".$stat->created_on."', ".$stat->responses."]";
	}

	$script = "
		google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([['".JText::_("LBL_DATE")."','".JText::_("LBL_RESPONSES")."'], ".implode(',', $data)."]);
			var options = {width: '100%', height: 350, 'chartArea': {'width': '92%', 'height': '80%'}, 'legend': {'position': 'in'}};
			var chart = new google.visualization.LineChart(document.getElementById('daily-response-chart'));
			chart.draw(data, options);
		}";
	$document->addScriptDeclaration($script);
}
?>

<div id="cj-wrapper">
	
	<?php //include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>

	<h2 class="page-header margin-bottom-10"><?php echo $this->escape($this->item->title);?></h2>
	<div class="survey-description"><?php echo CJFunctions::process_html($this->item->introtext, $bbcode, $content)?></div>
	
	<table class="table table-bordered table-striped table-hover margin-top-20">
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_COMPLETED_RESPONSES');?></td>
			<td width="15%"><?php echo $this->item->responses;?></td>
			<td width="30%">
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=consolidated&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_CONSOLIDATE_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_RESPONSES');?></td>
			<td><?php echo $this->item->stats->total_responses;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=responses&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_ALL_RESPONSES');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_COUNTRIES_PARTICIPATED');?></td>
			<td><?php echo $this->item->stats->countries;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=location_report&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_COUNTRY_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_BROWSERS_USED');?></td>
			<td><?php echo $this->item->stats->browsers;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=device_report&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_BROWSER_REPORT');?>
				</a>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('LBL_TOTAL_OS_USED');?></td>
			<td><?php echo $this->item->stats->oses;?></td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=os_report&id='.$this->item->id.':'.$this->item->alias.$itemid)?>">
					<?php echo JText::_('LBL_VIEW_OS_REPORT');?>
				</a>
			</td>
		</tr>
	</table>
	
	<h3 class="page-header margin-bottom-10"><?php echo JText::_('LBL_DAILY_RESPONSE_CHART');?></h3>
	<div id="tab-daily-chart" style="overflow: hidden;">
		<?php if(!empty($this->item->stats->daily)):?>
		<div id="daily-response-chart" style="width: 100%; height: 350px;"></div>
		<?php else:?>
		<?php echo JText::_('MSG_NO_DATA_AVAILABLE');?>
		<?php endif;?>
	</div>
	
	<h3 class="page-header no-margin-bottom"><?php echo JText::_('LBL_LATEST_RESPONSES');?></h3>
	
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('LBL_USERNAME');?></th>
				<th width="20%"><?php echo JText::_('LBL_COUNTRY');?></th>
				<th width="20%"><?php echo JText::_('LBL_DATE');?></th>
				<th width="20%"><?php echo JText::_('LBL_VIEW_REPORT');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->item->stats->recent as $i=>$item):?>
			<tr>
				<td><?php echo $i + 1;?></td>
				<td><?php echo $item->created_by > 0 ? $this->escape($item->username) : JText::_('LBL_GUEST');?></td>
				<td><?php echo $this->escape($item->country_name);?></td>
				<td><?php echo $item->created;?></td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=reports&task=view_response&id='.$this->item->id.':'.$this->item->alias.'&rid='.$item->id.$itemid)?>">
						<?php echo JText::_('LBL_VIEW_REPORT');?>
					</a>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>