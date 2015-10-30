<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');

define('BJImageSlider_Path',JURI::base().'components/com_bjimageslider/');

/**
 * General Controller of HelloWorld component
 */
class BJImageSliderController extends JController
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false) 
        {
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd('view', 'default')); 
                // call parent behavior
                parent::display($cachable);
        }
}