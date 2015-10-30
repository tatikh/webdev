<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 *
 * @package 	BJ Headline Roller
 * @subpackage	Parameter
 * @since		1.5
 */
jimport('joomla.html.html');
jimport('joomla.form.formfield');//import the necessary class definition for formfield

class JFormFieldColor extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'Color';
	protected function getInput()
	{
		// Initialize variables.
		$session = JFactory::getSession();
		$options = array();
		
		// Initialize some field attributes.
		$attr = '';
		$class = $this->element['class'] ? (string) $this->element['class'] : '';
  
		$document = &JFactory::getDocument();
		$document->addScript(JURI::base(). '../modules/mod_bj_imageslider_2/jscolor/jscolor.js' );
		return '<input type="text" class="color '.$class.'" value="'.$this->value.'" name="'.$this->name.'" id="'.$this->id.'"/>';
	}
}
