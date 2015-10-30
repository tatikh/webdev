<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);

// ============== INIT VALUES. DO NOT CHANGE ! ================ //
$COMPONENT_NAME = "com_bjimageslider";
$MODULE_NAME = "mod_bj_photo_gallery";
$MODULE_ABSOLUTE_PATH = JPATH_SITE . DS . "modules" . DS . $MODULE_NAME;
$MODULE_URL = JUri::base(true);
$MODULE_URL .= "/modules/".$MODULE_NAME;

// check if BJ Image Slider component is installed
if(!file_exists(JPATH_SITE . DS . "administrator" . DS . 'components' . DS . $COMPONENT_NAME)) {
	echo "This module only works when BJ Image Slider Component is installed.";
	return;
}
$id = rand(0,1000);

$document = &JFactory::getDocument();
if($facebook_id != '') {
	$document->addStyleSheet($MODULE_URL.'/media/commentbox/lightbox.css');
} else {
	$document->addStyleSheet($MODULE_URL.'/media/default/lightbox.css');
}
?>
<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/<?php echo ($facebook_lang == ""?"en_US":$facebook_lang);?>/all.js#xfbml=1&appId=<?php echo $facebook_id;?>";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript">
//<![CDATA[
var cssText = '#BJ_LightBox_<?php echo $id;?>.bjlightbox ul{background:none;}#BJ_LightBox_<?php echo $id;?>.bjlightbox li{list-style:none;background:none;display:block;float:left;<?php if($border_width>0){?>border:<?php echo $border_width;?>px solid <?php echo $border_color;?>;<?php }?>background:<?php echo $background;?>;margin:<?php echo $margin;?>px;padding:0}#BJ_LightBox_<?php echo $id;?>.bjlightbox ul a img{padding:0;margin:<?php echo $padding;?>px;float:left}';
var ref = document.createElement('style');
ref.setAttribute("rel", "stylesheet");
ref.setAttribute("type", "text/css");
document.getElementsByTagName("head")[0].appendChild(ref);
if(!!(window.attachEvent && !window.opera)) ref.styleSheet.cssText = cssText ;//this one's for ie
else ref.appendChild(document.createTextNode(cssText ));
// ]]>
</script>
<?php 
if($need_JQuery){?>
<script type="text/javascript" src="<?php echo $MODULE_URL;?>/media/js/jquery.js"></script>
<script type="text/javascript">
	jQuery.noConflict();
</script>
<?php }?>
<?php if($facebook_id != '') {?>
<script type="text/javascript" src="<?php echo $MODULE_URL;?>/media/commentbox/jquery.lightbox.js"></script>
<?php } else {?>
<script type="text/javascript" src="<?php echo $MODULE_URL;?>/media/default/jquery.lightbox.js"></script>
<?php }?>
<script type="text/javascript">
if(typeof jQuery == 'undefined') alert('JQuery is needed. Please choose to load JQuery at BJ LightBox backend');
else
{
	jQuery().ready(function($) {
		$('#BJ_LightBox_<?php echo $id;?> a').lightBox({
			imageLoading:			'<?php echo $MODULE_URL;?>/media/images/lightbox-ico-loading.gif',
			imageBtnPrev:			'<?php echo $MODULE_URL;?>/media/images/lightbox-btn-prev.gif',
			imageBtnNext:			'<?php echo $MODULE_URL;?>/media/images/lightbox-btn-next.gif',
			imageBtnClose:			'<?php echo $MODULE_URL;?>/media/images/lightbox-btn-close.gif',
			imageBlank:				'<?php echo $MODULE_URL;?>/media/images/lightbox-blank.gif',
			txtImage:				'<?php echo $txtImage;?>',
			txtOf:					'<?php echo $txtOf;?>',
			id:'BJ_LightBox_<?php echo $id;?>',
			facebook_id:'<?php echo $facebook_id;?>',
			commentBoxWidth:<?php echo $facebook_comment_width;?>
		});
	});
}
</script>
<div id="BJ_LightBox_<?php echo $id;?>" class="bjlightbox">
	<?php 
	$query = "SELECT i.id,i.name,i.description,i.cssclass,i.path,i.link,i.`is_default` "
			."FROM #__bj_ss_items i "
			."WHERE i.published = 1 "
			."AND i.cid = ".$category_id." "
			."ORDER BY i.ordering ASC"
			;
	if(is_numeric($item_count)) $query .= " LIMIT 0," . $item_count;
	$database = &JFactory::getDbo();
	$database->setQuery($query);
	$images = $database->loadObjectList();	
	if(count($images) > 0){
	$needClose = true;
	?>
	<ul>
		<?php $idx = 1;foreach($images as $image) {
		$link = subStr($image->path,1);
		$ext = subStr($link,-3);
		$link_thumb = str_replace("-","_",subStr($link,0,strLen($link)-4).'t'.'.'.$ext);
		$link_org = str_replace("-","_",($main_image == 'ORG' ? subStr($link,0,strLen($link)-4).'_org'.'.'.$ext : $link));
		// get full URL
		$link_full = "http://".$_SERVER['HTTP_HOST'].JUri::base(true)."/".$link_org;		
		
		list($width, $height) = @getimagesize($link_thumb);
		?>
		<li id="BJ_LightBox_<?php echo $id;?>_item_<?php echo $idx - 1;?>">
			<a href="<?php echo $link_org;?>" title="<?php echo stripslashes(strip_tags($image->description));?>"><img src="<?php echo $link_thumb;?>" alt="" height="<?php echo $height;?>" width="<?php echo $width;?>"/></a>
			<?php if($facebook_id != ''){?>
			<div class="fb-comments hide" data-href="<?php echo $link_full;?>" data-num-posts="2" data-width="<?php echo $facebook_comment_width;?>"></div>
			<?php }?>
		</li>
		<?php 
		if($idx % $columns == 0){
		$needClose = false;
			?>
		</ul><div class="clearer"></div><?php if($idx < count($images)){?><ul><?php $needClose = true;}?>
			<?php 
		}
		$idx++;
		}?>
	<?php if($needClose){?>
	</ul>
	<?php }?>
	<?php }?>
	<div class="clearer"></div>
</div>
<!-- END BJ PHOTO GALLERY. Visit http://byjoomla.com -->