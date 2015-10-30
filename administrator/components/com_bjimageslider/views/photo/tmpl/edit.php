<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?><form action="<?php echo JRoute::_('index.php?option=com_bjimageslider&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="bjimageslider-form" enctype="multipart/form-data">
        <fieldset class="adminform">
                <legend><?php echo JText::_( 'COM_BJIMAGESLIDER_PHOTO_DETAILS' ); ?></legend>
                <ul class="adminformlist">
					<li><?php echo $this->form->getLabel('name'); ?>
						<?php echo $this->form->getInput('name'); ?></li>
					<li><?php echo $this->form->getLabel('published'); ?>
						<?php echo $this->form->getInput('published'); ?></li>
						<li><?php echo $this->form->getLabel('cssclass'); ?>
					<?php echo $this->form->getInput('cssclass'); ?></li>
					<li><?php echo $this->form->getLabel('link'); ?>
					<?php echo $this->form->getInput('link'); ?></li>
					</li><label id="jform_category-lbl" for="cid" class="hasTip required" title="">Category <span class="star">&nbsp;*</span></label><select name="cid" class="inputbox required">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JFormFieldBJImageSliderCategory::getOptions(), 'value', 'text', $this->item->cid);?>
			</select></li>
                </ul>
				<div class="clr"></div>
				<?php echo $this->form->getLabel('description'); ?>
				<div class="clr"></div>
				<?php echo $this->form->getInput('description'); ?>
				<div class="clr"></div>
				<img src='<?php echo JUri::base(true) . '/../' . $this->item->path;?>' alt=''/>
				<table class="adminlist" width="100%">
		<tr>
		  <th class="title" colspan="2">Choose File to Upload (as Main Image)</th>
		</tr>
		<tr>
		  <td width="150">File: </td>
		  <td><input type="file" name="photo" class="text_area" size="30" value="" /></td>
		</tr>
		<tr>
		  <th class="title" colspan="2">Choose File to Upload (as Thumnail. If not, a thumbnail is created by default)</th>
		</tr>
		<tr>
		  <td width="150">Thumbnail image: </td>
		  <td><input type="file" name="thumb" class="text_area" size="30" value="" /></td>
		</tr>
		</table>
        </fieldset>
        <div>
                <input type="hidden" name="task" value="photo.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
