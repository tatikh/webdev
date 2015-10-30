<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);

// ============== INIT VALUES. DO NOT CHANGE ! ================ //
$MODULE_NAME = "mod_bj_fb_recommend";
$MODULE_ABSOLUTE_PATH = JPATH_SITE . DS . "modules" . DS . $MODULE_NAME;
$MODULE_URL = JUri::base(true);
$MODULE_URL .= "/modules/".$MODULE_NAME;
?>

<?php if($codetype == 'XFBML'){ 
if($domain != '') $domain = 'site="'.$domain.'"';
?>
<div style="height:<?php echo $height;?>px">
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:recommendations <?php echo $domain;?> colorscheme="<?php echo $color;?>" font="<?php echo $font;?>" border_color="<?php echo $border_color;?>" width="<?php echo $width;?>" height="<?php echo $height;?>" header="<?php echo $header;?>"></fb:recommendations>
</div>
<?php } else {
if($domain != '') $domain = 'site='.urlencode($domain).'&amp;';
?>
<iframe src="http://www.facebook.com/plugins/recommendations.php?<?php echo $domain;?>width=<?php echo $width;?>&amp;height=<?php echo $height;?>&amp;header=<?php echo $header;?>&amp;colorscheme=<?php echo $color;?>&amp;font=<?php echo $font;?>&amp;border_color=<?php echo $border_color;?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo $width;?>px; height:<?php echo $height;?>px;" allowTransparency="true"></iframe>
<?php }?>