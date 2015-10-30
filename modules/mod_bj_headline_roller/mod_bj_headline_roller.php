<?php
/**
Author: hadoanngoc@byjoomla.com - www.byjoomla.com
Version: 2.5.0 - 22th July 2010
**/
?>
<!-- BEGIN HEADLINE ROLLER -->
<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// ============== GET PARAMETERS ============================== //
$category_id = $params->get( 'category_id', array());
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$item_count = $params->get('item_count', 3);
$order_by = (int)$params->get('order_by',0);//created DESC;created ASC;ordering ASC;ordering DESC
$static_text = $params->get('static_text','Headlines:');
$headline_type = $params->get('headline_type','title');
$headline_link = $params->get('headline_link',1);
$headline_icon = $params->get('headline_icon','star');
$headline_icon_show = $params->get('headline_icon_show',1);
$roller_interval = $params->get('roller_interval','5000');
$theme = $params->get('theme','venus');
$need_jquery = $params->get('need_jquery',0);
// =============== END PARAMETERS ============================= //

require(JModuleHelper::getLayoutPath('mod_bj_headline_roller'));

?>
