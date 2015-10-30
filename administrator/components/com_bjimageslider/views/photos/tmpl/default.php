<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bjimageslider&view=photos'); ?>" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
		  <th class="categories">Manage photo's</th>
		  <td nowrap="nowrap">Category: <select name="filter_cid" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JFormFieldBJImageSliderCategory::getOptions(), 'value', 'text', $this->state->get('filter.cid'));?>
			</select></td>
	  </tr>
	  </table>
	  <table class="adminlist">
		<tr>
			<th width="5"> <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
			<th class="title">Name</th>
			<th width="5%">Reorder</th>
			<th width="2%">Order</th>
			<th width="1%"><?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'photos.saveorder'); ?></th>
			<th class="title">Category</th>
			<th class="title" width="75">Cat Thumb</th>
			<th class="title" width="75">Published</th>
			<th></th>
		</tr>
		<?php foreach ($this->items as $i => $item) :
			jimport('joomla.html.html.grid');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td>
				  <a href="<?php echo JRoute::_('index.php?option=com_bjimageslider&task=photo.edit&id='.$item->id);?>">
				  <?php echo  $item->name ; ?>
				  </a>
				</td>
				<td class="order"><span><?php echo $this->pagination->orderUpIcon($i, true, 'photos.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span><span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'photos.orderdown', 'JLIB_HTML_MOVE_DOWN', true); ?></span></td>
				<td align="center" colspan="2"><input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="text_area" style="text-align: center" /></td>
				<td><?php echo $item->category; ?></td>
				<td><?php echo ($item->is_default ? '<img src="'.BJImageSlider_Path.'assets/tick.png" border="0" />' : '<img src="'.BJImageSlider_Path.'assets/publish_x.png" border="0" />'); ?></td>
				<td><?php echo JHtml::_('jgrid.published', $item->published, $i, 'photos.', true, 'cb', $item->publish_up, $item->publish_down); ?>
				</td>
				<td></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php echo $this->pagination->getListFooter(); ?>

		<input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <?php echo JHtml::_('form.token'); ?>
		</form>