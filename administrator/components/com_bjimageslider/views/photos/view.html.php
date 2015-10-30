<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Photos View
 */
class BJImageSliderViewPhotos extends JView
{
        /**
         * Categories view display method
         * @return void
         */
        function display($tpl = null) 
        {
			// Get data from the model
			$items = $this->get('Items');
			$pagination = $this->get('Pagination');
			$this->state = $this->get('State');	

			// Check for errors.
			if (count($errors = $this->get('Errors'))) 
			{
					JError::raiseError(500, implode('<br />', $errors));
					return false;
			}
			// Assign data to the view
			$this->items = $items;
			$this->pagination = $pagination;

			// Set the toolbar
			$this->addToolBar();

			// Display the template
			require_once JPATH_COMPONENT.'/models/fields/category.php';
			parent::display($tpl);
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('COM_BJIMAGESLIDER_MANAGER_PHOTOS'));
                JToolBarHelper::deleteListX('', 'photos.delete');
                JToolBarHelper::addNewX('photo.add');
        }
}
