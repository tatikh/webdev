<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?><form action="<?php echo JRoute::_('index.php?option=com_bjimageslider&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="bjimageslider-form">
        <fieldset class="adminform">
                <legend><?php echo JText::_( 'COM_BJIMAGESLIDER_CATEGORY_DETAILS' ); ?></legend>
                <ul class="adminformlist">
					<li><?php echo $this->form->getLabel('name'); ?>
						<?php echo $this->form->getInput('name'); ?></li>
					<li><?php echo $this->form->getLabel('published'); ?>
						<?php echo $this->form->getInput('published'); ?></li>
                </ul>
				<div class="clr"></div>
				<?php echo $this->form->getLabel('description'); ?>
				<div class="clr"></div>
				<?php echo $this->form->getInput('description'); ?>
        </fieldset>
        <div>
                <input type="hidden" name="task" value="category.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
