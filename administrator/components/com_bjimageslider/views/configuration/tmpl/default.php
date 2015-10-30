<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
require(JPATH_COMPONENT . DS . 'configuration.php');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bjimageslider'); ?>" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="config">Configuration</th>
		</tr>
		</table>
		<table class="adminlist" width="100%">
		<tr>
		  <th class="title" colspan="2">Image Settings</th>
		</tr>
		<tr>
		  <td width="120px">Image width: </td>
		  <td><input type="text" name="bj_ss_config[image_width]" class="text_area" size="10" value="<?php echo $bj_ss_image_width; ?>" /></td>
		</tr>
		<tr>
		  <td>Image height: </td>
		  <td><input type="text" name="bj_ss_config[image_height]" class="text_area" size="10" value="<?php echo $bj_ss_image_height; ?>" /></td>
		</tr>
		<tr>
		  <td>Thumbmail width: </td>
		  <td><input type="text" name="bj_ss_config[thumb_width]" class="text_area" size="10" value="<?php echo $bj_ss_thumb_width; ?>" /></td>
		</tr>
		<tr>
		  <td>Thumbmail height: </td>
		  <td><input type="text" name="bj_ss_config[thumb_height]" class="text_area" size="10" value="<?php echo $bj_ss_thumb_height; ?>" /></td>
		</tr>
		</table>
		<input type="hidden" name="task" value="" />		
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
		</form>