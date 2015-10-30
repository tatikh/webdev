<?php
/*
 * BJ ImageSlider 2 Free version for Joomla 1.5( jQuery version)
 * Version 1.5.0 July 22th 2010
 * Author: www.byjoomla.com
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if(!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);

// ============== INIT VALUES. DO NOT CHANGE ! ================ //
$COMPONENT_NAME = "com_bjimageslider";
$MODULE_NAME = "mod_bj_imageslider_2";
$MODULE_ABSOLUTE_PATH = JPATH_SITE . DS . "modules" . DS . $MODULE_NAME;
$MODULE_URL = JUri::base();
$MODULE_URL .= "modules/".$MODULE_NAME;
$category_id = $params->get( 'category_id', 0 );

// ============== DEFAULT VALUES. CHANGE IS NECESSARY ========= //
$SS_WIDTH = 400; // px
$SS_HEIGHT = 300; // px
$SS_DELAY = 750; // millisecond
$SS_DURATION = 2000; // millisecond
$SS_THUMBNAIL_HEIGHT = 300; // px
$SS_IMAGE_MARGIN = 5; // px		

// ============== GET PARAMETERS ============================== //
$ss_width = $params->get('ss_width', $SS_WIDTH);
$ss_height = $params->get('ss_height', $SS_HEIGHT);
$ss_delay = $params->get('ss_delay', $SS_DELAY);
$ss_duration = $params->get('ss_duration', $SS_DURATION);
$ss_caption = $params->get('ss_caption', 1);
$ss_filmstrip = $params->get('ss_filmstrip', 1);
$ss_filmstrip_position = $params->get('ss_filmstrip_position', 'bottom');
$ss_overlay_position = $params->get('ss_overlay_position', 'right');
$ss_caption_thickness = $params->get('ss_caption_thickness', 300);
$ss_caption_opacity = $params->get('ss_caption_opacity', 0.7);
$ss_caption_outside = $params->get('ss_caption_outside', 0);
$ss_frame_gap = $params->get('ss_frame_gap', 8);
$ss_show_next_item_title = $params->get('ss_show_next_item_title', 1);
$ss_pause_on_hover = $params->get('ss_pause_on_hover', 0);
$ss_controller = $params->get('ss_controller', 1);
$ss_auto_hide_filmstrip = $params->get('ss_auto_hide_filmstrip', 0);
$ss_play_at_first = $params->get('ss_paused', 0);
$ss_filmstrip_size = $params->get('ss_filmstrip_size',5);
$ss_show_title_in_overlay = $params->get('ss_show_title_in_overlay',0);
$ss_panel_gradient = $params->get('ss_panel_gradient',1);
$ss_background = "#".$params->get('ss_background','333');
$ss_overlay_background = "#".$params->get('ss_overlay_background','333');
$need_JQuery = $params->get('need_jquery',0);
$gallery_padding = $params->get('ss_gallery_padding',1);
$ss_image_transition_effect = $params->get('ss_image_transition_effect','blur');
$ss_text_transition_effect = $params->get('ss_text_transition_effect','blur');
$ss_easing_method = $params->get('ss_easing_method','swing');
$ss_panel_on_click = $params->get('ss_panel_on_click','none');
// ============== THE CODE ==================================== //
$id = "BJ_ImageSlider_".rand(0,1000);

// check if BJ Image Slider component is installed
if(!file_exists(JPATH_SITE . DS . "administrator" . DS . 'components' . DS . $COMPONENT_NAME)) {
	echo "This module only works when BJ Image Slider Component is installed.";
	return;
}

include JPATH_SITE. DS ."administrator".DS."components".DS.$COMPONENT_NAME.DS."configuration.php";

if($category_id == 0) {
	echo "No category is choosen.";
	return;
}
?>
<script language="javascript" type="text/javascript">
<?php if(!defined('BJ_IMAGESLIDER_LIB_LOADED')){
$document = &JFactory::getDocument();
$document->addStyleSheet($MODULE_URL.'/mod_bj_imageslider/styles/default/css/galleryview.css');
?>
//<![CDATA[
<?php }?>

var cssText = '#<?php echo $id;?>_out.gallery_out{<?php echo ($ss_background != "" ? "background:".$ss_background : ""); ?>}#<?php echo $id;?>.gallery .overlay-background,#<?php echo $id;?>.gallery .overlay-background-left,#<?php echo $id;?>.gallery .overlay-background-right{<?php echo ($ss_overlay_background != "" ? "background-color:".$ss_overlay_background . "" : ""); ?>}';
<?php if($ss_panel_on_click == 'none'){?>
cssText += '#<?php echo $id;?>_out .mouseover{cursor:default}';
<?php } else {?>
cssText += '#<?php echo $id;?>_out .mouseover{cursor:pointer}';
<?php }?>
var ref = document.createElement('style');
ref.setAttribute("rel", "stylesheet");
ref.setAttribute("type", "text/css");
document.getElementsByTagName("head")[0].appendChild(ref);
if(!!(window.attachEvent && !window.opera)) ref.styleSheet.cssText = cssText ;//this one's for ie
else ref.appendChild(document.createTextNode(cssText));

// ]]>
</script>
<!--[if lt IE 7.]>
<script language="javascript" type="text/javascript">
//<![CDATA[

// ]]>
</script>
<![endif]-->
<?php require_once($MODULE_ABSOLUTE_PATH.DS."mod_bj_imageslider".DS."styles".DS."default".DS."bj_style_default.php");?>
<?php if($need_JQuery){?>
<script language="javascript" type="text/javascript" src="<?php echo $MODULE_URL;?>/mod_bj_imageslider/js/jquery.js"></script>
<script language="javascript" type="text/javascript">
jQuery.noConflict();
</script>
<?php }?>
<script type="text/javascript">
if(!jQuery) alert('JQuery is needed. Please choose to load JQuery at BJ Image Slider backend');
</script>
<?php if(!defined('BJ_IMAGESLIDER_LIB_LOADED')){?>
<script language="javascript" type="text/javascript" src="<?php echo $MODULE_URL;?>/mod_bj_imageslider/js/jquery.galleryview.js"></script>
<?php if($ss_panel_on_click == "viewfull"){?>
<script language="javascript" type="text/javascript" src="<?php echo $MODULE_URL;?>/mod_bj_imageslider/js/jquery.lightbox.js"></script>
<?php 
$document->addStyleSheet($MODULE_URL.'/mod_bj_imageslider/styles/default/css/lightbox.css');
}?>
<?php 
define('BJ_IMAGESLIDER_LIB_LOADED',1);
}?>
<script type="text/javascript">

  jQuery().ready(function($){
<?php 	
	$query = "SELECT i.id,i.name,i.description,i.cssclass,i.path,i.link,i.`is_default` "
			."FROM #__bj_ss_items i "
			."WHERE i.published = 1 "
			."AND i.cid = ".$category_id." "
			."ORDER BY i.ordering ASC LIMIT 0,5"
			;
	$database = &JFactory::getDbo();
	$database->setQuery($query);
	$images = $database->loadObjectList();
?>
    $('#<?php echo $id;?>').galleryView({
		panel_width: <?php echo ($ss_width - $gallery_padding * 2);?>,
		panel_height: <?php echo ($ss_height - $gallery_padding * 2);?>,
		transition_speed: <?php echo $ss_duration;?>,
		transition_interval: <?php echo $ss_delay;?>,
		show_filmstrip:<?php echo $ss_filmstrip;?>,
		show_captions: <?php echo $ss_caption;?>,
		filmstrip_position:'<?php echo $ss_filmstrip_position;?>',
		overlay_position:'<?php echo $ss_overlay_position;?>',
		caption_thickness:<?php echo $ss_caption_thickness;?>,
		caption_outside:<?php echo $ss_caption_outside;?>,
		overlay_opacity:<?php echo $ss_caption_opacity;?>,
		show_next_item_title:<?php echo $ss_show_next_item_title;?>,
		frame_gap:<?php echo $ss_frame_gap;?>,
		pause_on_hover: <?php echo $ss_pause_on_hover;?>,
		show_controller: <?php echo $ss_controller;?>,
		auto_hide_filmstrip: <?php echo $ss_auto_hide_filmstrip;?>,
		play_at_first: <?php echo $ss_play_at_first;?>,
		filmstrip_size: <?php echo $ss_filmstrip_size;?>,
		panel_gradient:<?php echo $ss_panel_gradient;?>,
		gallery_padding:<?php echo $gallery_padding;?>,
		easing: '<?php echo $ss_easing_method;?>',
		image_transition_effect: '<?php echo $ss_image_transition_effect;?>',
		text_transition_effect: '<?php echo $ss_text_transition_effect;?>',
		panel_on_click:'<?php echo $ss_panel_on_click; ?>',// link or viewfull or none
		frame_opacity:0.3,
		pointer_size: 8,
		show_panels: true,
		frame_width: 27,
		frame_height: 27,
		frame_margin: 8,
		start_frame: 1,
		nav_theme: 'dark',
		panel_scale: '',
		frame_scale: '',
		fade_panels: true,
		auto_hide_overlay: true
	});
	});
</script>
<ul id="<?php echo $id;?>" class="galleryview hide">
	<?php foreach($images as $image) {?>
	<li>
		<div class="panel-overlay">
			<div class="overlay-title" style="<?php echo $ss_show_title_in_overlay == 0?"display:none":"";?>"><?php echo $image->name;?></div>
			<div class="overlay-content"><?php echo stripslashes($image->description);?></div>
		</div>
		<img style="display:none" src="" alt="<?php echo $image->link;?>" title="<?php echo JUri::base();?><?php echo subStr(str_replace("-","_",$image->path),1);?>"/>
	</li>
	<?php }?>
</ul>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://byjoomla.com/byjoomla-extensions/byjoomla-image-slider-2.html" title="ByJoomla's BJ! Image Slider 2"><em>ByJoomla's BJ! Image Slider 2, a Joomla extension developed by ByJoomla.com</em></a>, to create image slideshow.</p>
<p><em>ByJoomla's BJ! Image Slider 2 - a Joomla Extension</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox 3</a></em> is recommended, or enable JavaScript support in your browser for best experience with slideshow created by <em>ByJoomla's BJ! Image Slider 2</em>.</p>
<p>Visit <em><a href="http://byjoomla.com/" title="Download ByJoomla.com's BJ! ImageSlider 2 - an image slideshow maker">ByJoomla.com to download BJ! ImageSlider 2</a></em>.</p>
</noscript>