<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bjimageslider&view=categories'); ?>" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
		  <th class="categories">Category Manager</th>
		  <td nowrap="nowrap"></td>
	  </tr>
	  </table>
	  <table class="adminlist">
		<tr>
			<th width="5"> <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
			<th class="title">Category Name</th>
			<th width="60">Published</th>
			<th width="5%">Reorder</th>
			<th width="2%">Order</th>
			<th width="1%"><?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'categories.saveorder'); ?></th>
			<th width="5%" nowrap>Category ID</th>
			<th width="20%"></th>
		</tr>
		<?php foreach ($this->items as $i => $item) :
			jimport('joomla.html.html.grid');
			$checked = JHTML::_('grid.checkedout', $item, $i );
			$published 	= JHtml::_('jgrid.published', $item->published, $i, 'categories.', true, 'cb', $item->publish_up, $item->publish_down);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td><a href="<?php echo JRoute::_('index.php?option=com_bjimageslider&task=category.edit&id='.$item->id);?>"><?php echo stripslashes( $item->name ); ?> </a></td>
				<td align="center"><?php echo $published;?></td>
				<td class="order"><span><?php echo $this->pagination->orderUpIcon($i, true, 'categories.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span><span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'categories.orderdown', 'JLIB_HTML_MOVE_DOWN', true); ?></span></td>
				<td align="center" colspan="2"><input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="text_area" style="text-align: center" /></td>
				<td align="center"><?php echo $item->id; ?></td>
				<td></td>
			</tr>
			<?php endforeach; ?>
		</table>

		<?php echo $this->pagination->getListFooter(); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
		</form>