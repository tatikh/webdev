<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Configuration Controller
 */
class BJImageSliderControllerConfiguration extends JControllerAdmin
{
        /**
         * Proxy for getModel.
         * @since       1.6
         */
        public function save(){
			$this->saveConfiguration();
			$option = JRequest::getCmd('option');
			$this->setMessage('Configuration saved');
			$this->setRedirect('index.php?option=' . $option);
		}
		
		public function apply(){
			$this->saveConfiguration();
			$option = JRequest::getCmd('option');
			$this->setMessage('Configuration saved');
			$this->setRedirect('index.php?option=' . $option . '&view=configuration');
		}
		
		private function saveConfiguration(){
			$database = &JFactory::getDbo();
			$option = JRequest::getCmd('option');
			$task = JRequest::getCmd('task');

			$config = JRequest::getVar('bj_ss_config');
			ksort($config);

			$content = '<?php
			/**
			* @package BJ ImageSlider
			* @copyright (C) 2008-2011 byjoomla.com
			* @author byjoola.com
			* @version 2011-March-13rd v.1.6.0
			* 
			* --------------------------------------------------------------------------------
			* All rights reserved. BJ ImageSlider for Joomla!
			*
			* --------------------------------------------------------------------------------
			**/

			defined( \'_JEXEC\' ) or die( \'Restricted access\' );
			if(!defined(\'DS\'))
			define(\'DS\', DIRECTORY_SEPARATOR);
			$bj_ss_path = \'/images/stories/_bj_imageslider\';
			$bj_ss_absolute_path = DS . \'images\' . DS . \'stories\' . DS . \'_bj_imageslider\';
			';
			$keys = array_keys($config);
			for($i=0,$n=count($keys);$i<$n;$i++){
			if (!ini_get('magic_quotes_gpc')){
			  $config[$keys[$i]] = addslashes($config[$keys[$i]]);
			}
			$content .= '$bj_ss_' . $keys[$i] . ' = \'' . $config[$keys[$i]] . "';\n";
			}
			$content .= "\n?>";

			if(!is_writable(JPATH_COMPONENT . DS . 'configuration.php')){
			$app = &JFactory::getApplication();
			$this->setMessage('File is not writable');
			$this->setRedirect('index.php?option=' . $option . '&view=configuration');
			return;
			}

			$fp = fopen(JPATH_COMPONENT . DS . 'configuration.php', 'w');
			fwrite($fp, $content);
			fclose($fp);
		}
}
