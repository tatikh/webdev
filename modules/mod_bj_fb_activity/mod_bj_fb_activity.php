<?php
/**
Author: BYJOOMLA - http://byjoomla.com
Version: 1.5.0 - November 06th, 2010
**/
?>
<!-- Begin BJ Social -->
<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// ============== GET PARAMETERS ============================== //
$moduleclass_sfx = $params->get('moduleclass_sfx', '');

$codetype = $params->get('codetype', 'XFBML');
$domain = $params->get('domain', "");
$header = $params->get('showheader', 'false');
$font = $params->get('font', '');
$color = $params->get('color', 'light');
$border_color = $params->get('border_color', 'CCCCCC');
$width = $params->get('width', 200);
$height = $params->get('height', 300);
$recommend = $params->get('recommend',1) == 1 ? "true":"false";
// =============== END PARAMETERS ============================= //

require(JModuleHelper::getLayoutPath('mod_bj_fb_activity'));

?>
