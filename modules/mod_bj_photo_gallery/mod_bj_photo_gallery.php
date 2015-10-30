<?php
/**
Author: HaDN - http://byjoomla.com
Version: 2.5.0 - July 22th, 2010
**/
?>
<!-- BEGIN BJ PHOTO GALLERY -->
<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// ============== GET PARAMETERS ============================== //
$category_id = $params->get( 'category_id', 0 );
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$item_count = $params->get('item_count', 4);
$columns = $params->get('columns', 2);
$background = "#".$params->get('background', 'FFFFFF');
$padding = $params->get('padding', '4');
$border_color = "#".$params->get('border_color', 'CCCCCC');
$border_width = $params->get('border_width', '1');
$margin = $params->get('margin', '5');
$need_JQuery = $params->get('need_jquery', 1);
$main_image = $params->get('main_image','ORG');
$txtImage = $params->get('txtImage','Image');
$txtOf = $params->get('txtOf','of');
$facebook_id = $params->get('facebook_id','');
$facebook_lang = $params->get('facebook_language','');
$facebook_comment_width = $params->get('comment_width',260);
// =============== END PARAMETERS ============================= //

require(JModuleHelper::getLayoutPath('mod_bj_photo_gallery'));

?>
