<?php 
/**
 * @version		$Id: default_basics.php 01 2012-04-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

$user = JFactory::getUser();
$itemid = CJFunctions::get_active_menu_id();

$editor = $user->authorise('core.wysiwyg', S_APP_NAME) ? $this->params->get('default_editor', 'bbcode') : 'none';
$invitation_body = $editor == 'wysiwyg' ? str_replace("\n", '<br>', JText::_('TXT_INVITE_DEFAULT_BODY')) : JText::_('TXT_INVITE_DEFAULT_BODY');
?>
<div class="invite-basics-wrapper">
	<label><?php echo JText::_('LBL_INVITATION_SUBJECT');?></label>
	<input type="text" name="invitation-subject" value="<?php echo JText::_('TXT_INVITE_DEFAULT_SUB');?>" class="input-xxlarge">
	
	<label class="margin-top-10"><?php echo JText::_('LBL_INVITATION_BODY');?></label>
	<?php echo CJFunctions::load_editor($editor, 'invitation-body', 'invitation-body', $invitation_body, '8', '40', '99%', '200px', '', 'width: 99%;'); ?>
	
	<p class="help-block margin-top-10"><?php echo JText::_('TXT_INVITATION_HELP');?></p>
</div>