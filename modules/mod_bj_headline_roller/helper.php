<?php
/**
* @version		helper.php 2011-02-12 10:59 PM 
* @package		BJ Headline Roller
* @author       hadoanngoc@byjoomla.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

jimport('joomla.application.component.model');

JModel::addIncludePath(JPATH_SITE.'/components/com_content/models');

class modBJHeadlineRollerHelper
{
	function getContent($category_id, $item_count, $order_by)
	{
		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic articles model
		$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', $item_count);
		$model->setState('filter.published', 1);

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		
		$userId = JFactory::getUser()->get('id');
		$authorised = JAccess::getAuthorisedViewLevels($userId);
		$model->setState('filter.access', $access);

		$model->setState('filter.category_id', $category_id);
		
		// Filter by language
		$model->setState('filter.language',$app->getLanguageFilter());
		$dir = "DESC";
		switch($order_by){
			case 0:
				$order_by = "a.created";
				break;
			case 1:
				$order_by = "a.created";
				$dir = "ASC";
				break;
			case 2:
				$order_by = "a.ordering";
				$dir = "ASC";
				break;
			case 3:
				$order_by = "a.ordering";
				break;
		}
		$model->setState('list.ordering', $order_by);
		$model->setState('list.direction', $dir);
	
		$items = $model->getItems();
		
		foreach ($items as &$item) {
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid.':'.$item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			else {
				$item->link = JRoute::_('index.php?option=com_user&view=login');
			}

			$item->introtext = JHtml::_('content.prepare', $item->introtext);
		}
		return $items;
	}
}
