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
CJFunctions::load_jquery(array('libs'=>array('validate')));
$categories = JHtml::_('category.categories', S_APP_NAME);

foreach ($categories as $id=>$category){
	
	if(!$user->authorise('core.create', S_APP_NAME.'.category.'.$category->value)) {
		
		unset($categories[$id]);
	}
}
?>

<div id="cj-wrapper">
	
	<?php //include_once JPATH_COMPONENT.DS.'helpers'.DS.'header.php';?>

	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
			
		<form name="survey-form" class="survey-form" action="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=form&task=save'.$itemid)?>" method="post">
		
			<fieldset>
			
				<legend><?php echo JText::_('LBL_BASIC_INFORMATION');?></legend>
				
				<div class="row-fluid">
					<div class="span12">

						<div class="clearfix">
							<label>
								<?php echo JText::_('LBL_TITLE');?><sup>*</sup>: 
								<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_TITLE');?>"></i>
							</label>
							<input name="title" type="text" class="input-xxlarge required" value="<?php echo $this->escape($this->item->title);?>" placeholder="<?php echo JText::_('LBL_ENTER_SURVEY_TITLE');?>">
						</div>
	
						<div class="clearfix hidden">
							<label>
								<?php echo JText::_('LBL_ALIAS');?>:
								<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_ALIAS');?>"></i>
							</label>
							<input name="alias" type="text" class="input-xxlarge" value="<?php echo $this->escape($this->item->alias);?>">
						</div>
						
						<div class="clearfix">
							<label><?php echo JText::_('LBL_CATEGORY');?><sup>*</sup>:</label>
							<?php echo JHTML::_('select.genericlist', $categories, 'catid', array('list.select'=>$this->item->catid));?>
						</div>
						
						<div class="clearfix margin-top-20">
							<label>
								<?php echo JText::_('LBL_INTROTEXT');?>:
								<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_INTROTEXT');?>"></i>
							</label>
							<?php echo CJFunctions::load_editor($editor, 'introtext', 'introtext', $this->item->introtext, '5', '40', '100%', '200px', '', 'width: 100%;'); ?>
						</div>
						
						<div class="clearfix margin-top-20 hidden">
							<label>
								<?php echo JText::_('LBL_END_MESSAGE');?>:
								<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_END_MESSAGE');?>"></i>
							</label>
							<?php //echo CJFunctions::load_editor($editor, 'endtext', 'endtext', $this->item->title, '5', '40', '100%', '200px', '', 'width: 100%;'); ?>
							<?php //echo CJFunctions::load_editor($editor, 'endtext', 'endtext', $this->item->title, '5', '40', '100%', '200px', '', 'width: 100%;'); ?>
							<input name="endtext" type="text" class="input-medium" value="Thank you for taking the survey">
						</div>
						
						<div class="clearfix margin-top-20 hidden">
							<label>
								<?php echo JText::_('LBL_CUSTOMHEADER');?>:
								<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_CUSTOMHEADER');?>"></i>
							</label>
							<?php echo CJFunctions::load_editor($editor, 'custom_header', 'custom_header', $this->item->custom_header, '5', '40', '99%', '200px', '', 'width: 100%;'); ?>
						</div>
					</div>
				</div>
				
				<legend><?php echo JText::_('LBL_SURVEY_OPTIONS');?></legend>
				
				<div class="row-fluid">
					<div class="form-horizontal">
						
						<div class="span6">
						<div class="control-group">
								<label class="control-label">
									<?php echo JText::_('LBL_STARTDATE');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_STARTDATE');?>"></i>
								</label>
								<div class="controls">
									<input id="datepicker" name="publish-up" type="calendar" class="input-medium hidden" value="<?php echo $this->escape($this->item->publish_1up);?>">
									
									<?php //custom date picker
									//echo JHTML::calendar('','cal_field_name','cal_field_id','%Y-%m-%d');
									echo JHTML::calendar(date("Y-m-d"),'publish_up', 'date', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','class'=>' validate[\'required\']',));
										?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">
									<?php echo JText::_('LBL_ENDDATE');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_ENDDATE');?>"></i>
								</label>
								<div class="controls">
									<input name="publish-down" type="text" class="input-medium hidden" value="<?php echo $this->escape($this->item->publish_1down);?>">

									<?php //custom date picker
									//echo JHTML::calendar('','cal_field_name','cal_field_id','%Y-%m-%d');
									echo JHTML::calendar(date("Y-m-d"),'publish_down', 'date', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','class'=>' validate[\'required\']',));
										?>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_SURVEYTYPE');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_SURVEYTYPE');?>"></i>
								</label>
								<div class="controls">
									<select name="survey-type" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->private_survey == '0' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_PRIVATE_SURVEY');?></option>
										<option value="0" <?php echo $this->item->private_survey == '1' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_PUBLIC_SURVEY');?></option>
									</select>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_SURVEY_RESPONSE_TYPE');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_SURVEY_RESPONSE_TYPE');?>"></i>
								</label>
								<div class="controls">
									<select name="response-type" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->anonymous == '1' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_ANONYMOUS');?></option>
										<option value="0" <?php echo $this->item->anonymous == '0' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_ONYMOUS');?></option>
									</select>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_DISPLAY_REPORT');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_DISPLAY_REPORT');?>"></i>
								</label>
								<div class="controls">
									<select name="show-result" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->public_permissions == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
										<option value="0" <?php echo $this->item->public_permissions == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
									</select>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_DISPLAY_SITE_TEMPLATE');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_DISPLAY_SITE_TEMPLATE');?>"></i>
								</label>
								<div class="controls">
									<select name="show-template" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->display_template == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
										<option value="0" <?php echo $this->item->display_template == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
									</select>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_SKIP_INTRO');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_SKIP_INTRO');?>"></i>
								</label>
								<div class="controls">
									<select name="skip-intro" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->skip_intro == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
										<option value="0" <?php echo $this->item->skip_intro == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
									</select>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_CONFIDENTIAL_NOTICE');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('LBL_CONFIDENTIAL_NOTICE_HELP');?>"></i>
								</label>
								<div class="controls">
									<select name="display-notice" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->display_notice == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
										<option value="0" <?php echo $this->item->display_notice == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
									</select>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_NOTIFICATION');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('LBL_NOTIFICATION_HELP');?>"></i>
								</label>
								<div class="controls">
									<select name="notification" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->notification == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
										<option value="0" <?php echo $this->item->notification == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
									</select>
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_BACKWARD_NAVIGATION');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_BACKWARD_NAVIGATION');?>"></i>
								</label>
								<div class="controls">
									<select name="backward-navigation" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->backward_navigation == '1' ? 'selected="selected"':'';?>><?php echo JText::_('JYES');?></option>
										<option value="0" <?php echo $this->item->backward_navigation == '0' ? 'selected="selected"':'';?>><?php echo JText::_('JNO');?></option>
									</select>
								</div>
							</div>
						</div>
					
						<div class="span6">
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_PROGRESS_BAR');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('LBL_PROGRESS_BAR_HELP');?>"></i>
								</label>
								<div class="controls">
									<select name="display-progress" size="1" class="input-medium">
										<option value="1" <?php echo $this->item->display_progress == '1' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_DISPLAY');?></option>
										<option value="0" <?php echo $this->item->display_progress == '0' ? 'selected="selected"':'';?>><?php echo JText::_('LBL_HIDE');?></option>
									</select>
								</div>
							</div>
							
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_MAXIMUM_RESPONSES');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_MAXIMUM_RESPONSES');?>"></i>
								</label>
								<div class="controls">
									<input name="max-responses" type="text" class="input-medium" value="<?php echo $this->escape($this->item->max_responses);?>">
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_REDIRECT_LINK');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_REDIRECT_LINK');?>"></i>
								</label>
								<div class="controls">
									<input name="redirect-url" type="text" class="input-medium" value="<?php echo $this->escape($this->item->redirect_url);?>">
								</div>
							</div>
							<div class="control-group hidden">
								<label class="control-label">
									<?php echo JText::_('LBL_RESTRICTION');?><sup>*</sup>:
									<i class="icon-info-sign tooltip-hover" title="<?php echo JText::_('HLP_RESTRICTION');?>"></i>
								</label>
								<div class="controls">
									<label class="checkbox">
										<input name="restriction[]" type="checkbox" value="cookie"
											<?php echo strpos($this->item->restriction, 'cookie') !== false ? 'checked="checked"' : '';?>> <?php echo JText::_('LBL_COOKIES')?>
									</label>
									<label class="checkbox">
										<input name="restriction[]" type="checkbox" value="username"
											<?php echo strpos($this->item->restriction, 'username') !== false ? 'checked="checked"' : '';?>> <?php echo JText::_('LBL_USERNAME')?>
									</label>
									<label class="checkbox">
										<input name="restriction[]" type="checkbox" value="ip"
											<?php echo strpos($this->item->restriction, 'ip') !== false ? 'checked="checked"' : '';?>> <?php echo JText::_('LBL_IP_ADDRESS')?>
									</label>
								</div>
							</div>
						</div>
													
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="well center">
							<a class="btn" href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$surveys_itemid)?>"><?php echo JText::_('LBL_CANCEL');?></a>
							<button type="button" class="btn-submit-form btn btn-primary"><i class="icon-arrow-right icon-white"></i> <?php echo JText::_('LBL_CONTINUE');?></button>
						</div>
					</div>
				</div>
			</fieldset>
			
			<input name="id" type="hidden" value="<?php echo $this->item->id;?>">
			<input name="userid" type="hidden" value="<?php echo $this->item->created_by;?>">
		</form>
		
		<div style="display: none;">
			<input type="hidden" name="cjpageid" id="cjpageid" value="create_edit_survey">
		</div>
	</div>
</div>
  <script>
  /*$(function() {
    $( "#datepicker" ).datepicker();
  });*/

  //mine
	  /*  jQuery ready function. Specify a function to execute when the DOM is fully loaded.  */
	$(document).ready(
	  
	  /* This is the function that will get executed after the DOM is fully loaded */
	  function () {
	    $( "#datepicker" ).datepicker({
	      changeMonth: true,//this option for allowing user to select month
	      changeYear: true //this option for allowing user to select from year range
	    });
	  }

	);
  </script>
