<?php
/**
* @version		mod_bj_dropdown_menu.php 2010-07-22 11:20 PM
* @package		BJ! Venus
* @copyright	ByJoomla.com
* @author		hadoanngoc@byjoomla.com
**/
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
//require_once (dirname(__FILE__).DS.'helper.php');

// define parameters
$params->def('menuclass_sfx',		'');
$params->def('menutype', 			'mainmenu');
$params->def('legacy_mode',			0);
$params->def('active_id', 			1);
$params->def('full_active_id',		1);
$params->def('activate_parent', 	1);

$params->def('rootmenu_count',		6);
$params->def('submenu_deep',		2);
$params->def('expand_all',			1);
$params->def('sf_menu',				0);

$params->def('menu_images', 		0);
$params->def('menu_images_align', 	0);

$params->def('animate_dropdown',	1);
$params->def('dropdown_opacity',	0.9);

require(JModuleHelper::getLayoutPath('mod_bj_dropdown_menu', $params->get('layout', 'default')));