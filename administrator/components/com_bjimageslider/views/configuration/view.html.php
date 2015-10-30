<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Configuration View
 */
class BJImageSliderViewConfiguration extends JView
{
        /**
         * Configuration view display method
         * @return void
         */
        function display($tpl = null) 
        {
			// Check for errors.
			if (count($errors = $this->get('Errors'))) 
			{
					JError::raiseError(500, implode('<br />', $errors));
					return false;
			}

			// Set the toolbar
			$this->addToolBar();

			// Display the template
			parent::display($tpl);
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('COM_BJIMAGESLIDER_MANAGER_CONFIGURATION'));
                JToolBarHelper::save('configuration.save');
				JToolBarHelper::apply('configuration.apply');
				JToolBarHelper::cancel('configuration.cancel');
        }
}
