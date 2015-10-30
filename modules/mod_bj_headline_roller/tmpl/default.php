<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.parameter' );

if($category_id == 0) {
	echo "No category is choosen.";
	return;
}

$contents = modBJHeadlineRollerHelper::getContent($category_id, $item_count, $order_by);

$mosConfig_live_site = JUri::base();
$MODULE_URL = $mosConfig_live_site . '/modules/mod_bj_headline_roller';

$icons = explode(",",$icons);

$document = &JFactory::getDocument();
$document->addStyleSheet($MODULE_URL.'/media/themes/'.$theme.'/'.$theme.'.css');
?>
<!--[if IE 9]>

<style type="text/css">

.venus-headline .text{width:750px}

</style>

<![endif]-->

<?php $id = rand(0,1000);

if($headline_icon_show == "0"){
	// show icon random
	$i = rand(0,4);
	$array = array("star","love","thumb","warning","sticker");
	$headline_icon = $array[$i];
}
?>
<div id="BJ_Headline_<?php echo $id;?>" class="<?php echo $theme;?>-headline">
	<div class="static"><?php echo $static_text;?></div>
	<div class="control"><span class="prev">&nbsp;</span><span class="next">&nbsp;</span></div><span class="icon-<?php echo $headline_icon;?>"><!-- --></span><div class="text"><?php for($i = 0; $i < count($contents); $i++) {
		switch($headline_type){
			case 'title':
				$text = $contents[$i]->title;
				break;
			case 'intro':
				$text = $contents[$i]->introtext;
				break;
		}
		
		?>
		<div class="content">
			<?php if($headline_link) {
				$readmore = $contents[$i]->link;
			?>
				<a href="<?php echo $readmore;?>"><?php echo $text;?></a>
			<?php } else {?>
				<?php echo $text;?>
			<?php }?>
		</div>
		<?php }?>
	</div>
	<div class="clearer"></div>
</div>
<?php 

if (!defined('_BJ_HEADLINE_FUNC_INCLUDED')) {
	define('_BJ_HEADLINE_FUNC_INCLUDED', 1);
	
if($need_jquery){?>
<script type="text/javascript" src="<?php echo $MODULE_URL;?>/media/js/jquery-1.4.2.js">
</script>
<script type="text/javascript">
jQuery.noConflict();
</script>
<?php
}?>
<script type="text/javascript">
if(typeof jQuery == 'undefined') alert('JQuery is needed. Please choose to load JQuery at BJ Headline Roller module backend');
</script>
<script type="text/javascript" src="<?php echo $MODULE_URL;?>/media/js/jquery-headline.js"></script>
<?php }?>
<script type="text/javascript">
jQuery(document).ready(function($) {
		$("#BJ_Headline_<?php echo $id;?>").bj_headline({roller_interval:<?php echo $roller_interval;?>});
	});
</script>
<!-- END HEADLINE ROLLER -->