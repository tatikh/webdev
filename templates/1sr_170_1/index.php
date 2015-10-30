<?php
	
	defined('_JEXEC') or die('Restricted access');
	
	//JHtml::_('behavior.framework', true);

	// get params
	$color              = $this->params->get('templateColor');
	$logo               = $this->params->get('logo');
	$navposition        = $this->params->get('navposition');
	$app                = JFactory::getApplication();
	$doc				= JFactory::getDocument();
	$templateparams     = $app->getTemplate(true)->params;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
	<head>
		<jdoc:include type="head" />
		
		<?php 
			$predefinedTemplateColor = $this->params->get('predefinedTemplateColor');
			$useCustomTemplateColor = $this->params->get('useCustomTemplateColor');
			$customTemplateColor = $this->params->get('customTemplateColor');
			$useCustomTemplateColorGradient = $this->params->get('useCustomTemplateColorGradient');
			$customTemplateColor2 = $this->params->get('customTemplateColor2');
			$customLogo = $this->params->get('customLogo');
			$showSiteName = $this->params->get('showSiteName');
			$showTopNavArrows = $this->params->get('showTopNavArrows');
			$topNavSubOpacity = $this->params->get('topNavSubOpacity');
			$topNavCloseDelay = $this->params->get('topNavCloseDelay');
			
			$facebook = $this->params->get('facebook');
			$facebookUrl = $this->params->get('facebookUrl');
			$twitter = $this->params->get('twitter');
			$twitterUrl = $this->params->get('twitterUrl');
			
			$googleCustomSearch = $this->params->get('googleCustomSearch');
			$googleCustomSearchSnippet = str_replace("1srQuot", "\"", str_replace("1srGt", ">", str_replace("1srLt", "<", $this->params->get('googleCustomSearchSnippet'))));
			$googleWebFonts = $this->params->get('googleWebFonts');
			$googleWebFont = $this->params->get('googleWebFont');
			
			if(($useCustomTemplateColor == "yes") && ($customTemplateColor != ""))
			{
				if($customLogo != "")
				{
					$logoUrl = $_SERVER['DOCUMENT_ROOT'].$this->baseurl."/".$customLogo;
				}
				else
				{
					$logoUrl = $_SERVER['DOCUMENT_ROOT'].$this->baseurl."/templates/".$this->template."/images/custom/logo.jpg";
				}
			}
			else
			{
				$logoUrl = $_SERVER['DOCUMENT_ROOT'].$this->baseurl."/templates/".$this->template."/images/".$predefinedTemplateColor."/logo.jpg";
				
			}
			
			$logoSize = getimagesize($logoUrl);
			
			
			
			
		?>

		<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/stylechanger.js"></script>
		<?php echo ($googleWebFonts == "yes") ? "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://fonts.googleapis.com/css?family=".str_replace(" ", "+", $googleWebFont)."\">" : ""; ?>
		
		<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/1008-24-static-grid.css" type="text/css" />
		
		<?php if(($useCustomTemplateColor == "yes") && ($customTemplateColor != "")) : ?>
			<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template_custom.css" type="text/css" />
			<link href="templates/<?php echo $this->template ?>/favicon.ico" rel="shortcut icon" />
		<?php else : ?>
			<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template_<?php echo $predefinedTemplateColor; ?>.css" type="text/css" />
			<link href="templates/<?php echo $this->template ?>/images/<?php echo $predefinedTemplateColor; ?>/favicon.ico" rel="shortcut icon" />
		<?php endif; ?>		
		
		<link rel="stylesheet" type="text/css" href="templates/<?php echo $this->template ?>/css/superfish.css" media="screen" />
		<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/hoverIntent.js"></script>
		<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/superfish.js"></script>
		<script type="text/javascript">

			// initialise plugins
			jQuery(function(){
				jQuery('#header-nav ul.menu').addClass("sf-menu");
				
				var sfOptions = {
					delay : <?php echo $topNavCloseDelay; ?>, 
					autoArrows : <?php echo ($showTopNavArrows == "yes") ? "true" : "false"; ?>
				};
				
				jQuery('#header-nav ul.menu').superfish(sfOptions);
			});

		</script>
	</head>
	<body>
		
		<style type="text/css">

		
		
		<?php if ($googleWebFonts == "yes") : ?>
		
			h1 {font-family: '<?php echo $googleWebFont; ?>', arial, serif;}
			h2 {font-family: '<?php echo $googleWebFont; ?>', arial, serif;}
			h3 {font-family: '<?php echo $googleWebFont; ?>', arial, serif;}
			#bread {font-family: '<?php echo $googleWebFont; ?>', arial, serif;}
		
		<?php endif; ?>
		
		<?php if(($useCustomTemplateColor == "yes") && ($customTemplateColor != "")) : ?>
			
			<?php if(($useCustomTemplateColorGradient == "yes") && ($customTemplateColor2 != "")) : ?>
			#header-top {background: <?php echo $customTemplateColor2; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x top;  /* fallback */  background-image: -moz-linear-gradient(top, #fff 0%, #ccc 20%, <?php echo $customTemplateColor; ?> 20%, <?php echo $customTemplateColor2; ?> 100%) ; background-image: -webkit-linear-gradient(top, #fff 0%, #ccc 20%, <?php echo $customTemplateColor; ?> 20%, <?php echo $customTemplateColor2; ?> 100%); background-image: -ms-linear-gradient(top, #fff 0%, #ccc 20%, <?php echo $customTemplateColor; ?> 20%, <?php echo $customTemplateColor2; ?> 100%); background-image: -o-linear-gradient(top, #fff 0%, #ccc 20%, <?php echo $customTemplateColor; ?> 20%, <?php echo $customTemplateColor2; ?> 100%); background: linear-gradient(to bottom, #fff 0%, #ccc 20%, <?php echo $customTemplateColor; ?> 20%, <?php echo $customTemplateColor2; ?> 100%);}
			<?php else : ?>
			#header-top {background: <?php echo $customTemplateColor; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x top;}
			<?php endif ?>
			#logo a {height: <?php echo $logoSize[1]; ?>px;}
			
			#social {position: relative; z-index: 2;}
			#search {position: relative; z-index: 2;}
			#fontsize {position: relative; z-index: 2;}
			
			
			
			
			#header-main {height: <?php echo $logoSize[1]; ?>px;}
			#sitename-logo {margin-top: -<?php echo $logoSize[1] - 20; ?>px;}
			
			<?php if(($useCustomTemplateColorGradient == "yes") && ($customTemplateColor2 != "")) : ?>
			
			h1 {color: <?php echo $customTemplateColor2; ?>;}
			h2 {color: <?php echo $customTemplateColor2; ?>;}
			h3 {color: <?php echo $customTemplateColor2; ?>;}
			a {color: <?php echo $customTemplateColor2; ?>;}
			
			#mod-search-searchword.inputbox {color: <?php echo $customTemplateColor2; ?>;}
			
			<?php else : ?>
			
			h1 {color: <?php echo $customTemplateColor; ?>;}
			h2 {color: <?php echo $customTemplateColor; ?>;}
			h3 {color: <?php echo $customTemplateColor; ?>;}
			a {color: <?php echo $customTemplateColor; ?>;}
			
			#mod-search-searchword.inputbox {color: <?php echo $customTemplateColor; ?>;}
			
			<?php endif ?>

			<?php if(($useCustomTemplateColorGradient == "yes") && ($customTemplateColor2 != "")) : ?>
			#header-nav {background: <?php echo $customTemplateColor2; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x bottom;  /* fallback */  background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%);}
			
			#header-nav ul li.active a:link, #header-nav ul li.active a:visited {color: <?php echo $customTemplateColor2; ?>;}
			#header-nav ul.menu li li a:link, #header-nav ul.menu li li a:visited {color: <?php echo $customTemplateColor2; ?>;}
			#header-nav ul.menu li a:hover, #header-nav ul.menu li a:active, #header-nav ul.menu li a:focus {color: <?php echo $customTemplateColor2; ?>;}
			<?php else : ?>
			#header-nav {background: <?php echo $customTemplateColor; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x bottom;}
			
			#header-nav ul li.active a:link, #header-nav ul li.active a:visited {color: <?php echo $customTemplateColor; ?>;}
			#header-nav ul.menu li li a:link, #header-nav ul.menu li li a:visited {color: <?php echo $customTemplateColor; ?>;}
			#header-nav ul.menu li a:hover, #header-nav ul.menu li a:active, #header-nav ul.menu li a:focus {color: <?php echo $customTemplateColor; ?>;}
			<?php endif ?>
			
			
			/* override superfish.css */
			
			.sf-menu a {border: none; padding: 8px 10px}
			
			<?php if(($useCustomTemplateColorGradient == "yes") && ($customTemplateColor2 != "")) : ?>
			.sf-menu li {background: <?php echo $customTemplateColor2; ?>; /* fallback */ background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%);}
			<?php else : ?>
			.sf-menu li {background: <?php echo $customTemplateColor; ?>}
			<?php endif ?>
			
			.sf-menu li.sfHover ul {top: 30px}
			.sf-menu li li, .sf-menu li li li {background: #eeeeee;}
			
			.sf-menu li:hover, .sf-menu li li.sfHover, .sf-menu a:focus, .sf-menu a:hover, .sf-menu a:active {background: #ffffff;}
			
			<?php if(($useCustomTemplateColorGradient == "yes") && ($customTemplateColor2 != "")) : ?>
			.sf-menu li.sfHover {background: <?php echo $customTemplateColor2; ?>; /* fallback */ background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%);}
			<?php else : ?>
			.sf-menu li.sfHover {background: <?php echo $customTemplateColor; ?>}
			<?php endif ?>
			
			.sf-menu li li {zoom: 1; filter: alpha(opacity=<?php echo $topNavSubOpacity; ?>); opacity: <?php echo $topNavSubOpacity/100; ?>;}
			
			.sf-sub-indicator {background: url('<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/custom/header-nav-arrows.png') no-repeat -10px -100px; /* 8-bit indexed alpha png. IE6 gets solid image only */}
			
			
			
			<?php if(($useCustomTemplateColorGradient == "yes") && ($customTemplateColor2 != "")) : ?>
			
			#bread {background: <?php echo $customTemplateColor2; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x bottom;  /* fallback */  background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 77%, #fff 77%, #ccc 100%);}
			#left h3 {background: <?php echo $customTemplateColor2; ?>;  /* fallback */  background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%);}
			#content_big .componentheading, #content_big .item-page h1, #content_big .blog-featured h1, #content_big .blog h1, #content .componentheading, #content .item-page h1, #content .blog-featured h1, #content .blog h1 {background: <?php echo $customTemplateColor2; ?>;  /* fallback */  background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%);}
			#right h3, #right .moduletable_text h3 {background: <?php echo $customTemplateColor2; ?>;  /* fallback */  background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 100%);}
			#foot {background: <?php echo $customTemplateColor2; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x bottom;  /* fallback */  background-image: -moz-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 80%, #fff 80%, #ccc 100%) ; background-image: -webkit-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 80%, #fff 80%, #ccc 100%); background-image: -ms-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 80%, #fff 80%, #ccc 100%); background-image: -o-linear-gradient(top, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 80%, #fff 80%, #ccc 100%); background: linear-gradient(to bottom, <?php echo $customTemplateColor; ?> 0%, <?php echo $customTemplateColor2; ?> 80%, #fff 80%, #ccc 100%);}
			
			#left .moduletable_menu ul a:hover, #left .moduletable ul a:hover, #left .moduletable ul.menu li#current.active a , #left .moduletable_menu ul.menu li#current.parent a {color: <?php echo $customTemplateColor2; ?>;}
			#left .moduletable_menu ul.menu li#current.parent ul li a:hover {color: <?php echo $customTemplateColor2; ?>;}
			
			#content ul li a, #right ul li a {color: <?php echo $customTemplateColor2; ?>;}
			#content ul li a:hover, #right ul li a:hover {color: <?php echo $customTemplateColor2; ?>;}
			.contentheading, .buttonheading {color: <?php echo $customTemplateColor2; ?>;}
			.button {color: <?php echo $customTemplateColor2; ?>;}
			p.counter {color: <?php echo $customTemplateColor2; ?>;}
			#footer {color: <?php echo $customTemplateColor2; ?>;}
			#user1, #user2, #user5 {color: <?php echo $customTemplateColor2; ?>;}
			
			<?php else : ?>

			#bread {background: <?php echo $customTemplateColor; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x bottom;}
			#left h3 {background: <?php echo $customTemplateColor; ?>;}
			#content_big .componentheading, #content_big .item-page h1, #content_big .blog-featured h1, #content_big .blog h1, #content .componentheading, #content .item-page h1, #content .blog-featured h1, #content .blog h1 {background: <?php echo $customTemplateColor; ?>;}
			#right h3, #right .moduletable_text h3 {background: <?php echo $customTemplateColor; ?>;}
			#foot {background: <?php echo $customTemplateColor; ?> url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/border.jpg) repeat-x bottom;}
			
			#left .moduletable_menu ul a:hover, #left .moduletable ul a:hover, #left .moduletable ul.menu li#current.active a , #left .moduletable_menu ul.menu li#current.parent a {color: <?php echo $customTemplateColor; ?>;}
			#left .moduletable_menu ul.menu li#current.parent ul li a:hover {color: <?php echo $customTemplateColor; ?>;}
			
			#content ul li a, #right ul li a {color: <?php echo $customTemplateColor; ?>;}
			#content ul li a:hover, #right ul li a:hover {color: <?php echo $customTemplateColor; ?>;}
			.contentheading, .buttonheading {color: <?php echo $customTemplateColor; ?>;}
			.button {color: <?php echo $customTemplateColor; ?>;}
			p.counter {color: <?php echo $customTemplateColor; ?>;}
			#footer {color: <?php echo $customTemplateColor; ?>;}
			#user1, #user2, #user5 {color: <?php echo $customTemplateColor; ?>;}
			<?php endif ?>
			
		<?php else : ?>
			
			/* override superfish.css */
			
			.sf-menu a {border: none; padding: 8px 10px}
			
			.sf-menu li {background: url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/<?php echo $predefinedTemplateColor; ?>/h3-background.jpg) repeat-x;}
			
			.sf-menu li.sfHover ul {top: 30px}
			.sf-menu li li, .sf-menu li li li {background: #eeeeee;}
			
			.sf-menu li:hover, .sf-menu li li.sfHover, .sf-menu a:focus, .sf-menu a:hover, .sf-menu a:active {background: #ffffff;}
			
			.sf-menu li.sfHover {background: url(<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/<?php echo $predefinedTemplateColor; ?>/h3-background.jpg) repeat-x;}
			
			.sf-menu li li {zoom: 1; filter: alpha(opacity=<?php echo $topNavSubOpacity; ?>); opacity: <?php echo $topNavSubOpacity/100; ?>;}
			
			.sf-sub-indicator {background: url('<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/<?php echo $predefinedTemplateColor; ?>/header-nav-arrows.png') no-repeat -10px -100px; /* 8-bit indexed alpha png. IE6 gets solid image only */}
			
		<?php endif; ?>
		
		</style>
		
		<div class="container_24">
			<!-- header -->
			
			<div class="grid_24" id="header-top">
				<?php if(($twitter == "on") || ($facebook == "on")) : ?>
					<div id="social">
						<?php if($twitter == "on") : ?>
							<?php if($twitterUrl != ""): ?>
							<a href="<?php echo $twitterUrl ?>" title="<?php echo $app->getCfg('sitename'); ?> @ Twitter">
							<?php endif; ?>
								<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/twitter-button.png" />
							<?php if($twitterUrl != "") : ?>
							</a>
							<?php endif; ?>
						<?php endif; ?>
						<?php if($facebook == "on"): ?>
							<?php if($facebookUrl != "") : ?>
							<a href="<?php echo $facebookUrl ?>" title="<?php echo $app->getCfg('sitename'); ?> @ Facebook">
							<?php endif; ?>
								<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/facebook-button.png" />
							<?php if($facebookUrl != "") : ?>
							</a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php if($googleCustomSearch == "yes") : ?>
						<div id="custom-search"><?php echo $googleCustomSearchSnippet; ?></div>
					<?php else : ?>
						<div id="search"><jdoc:include type="modules" name="position-0" type="xhtml" /></div>
					<?php endif; ?>
					<div id="fontsize">
						<script type="text/javascript">
							//<![CDATA[
								document.write('<a href="index.php" title="gro&szlig;e Schrift" onclick="bigFontSize(); return false;"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/font_size_big.png" alt="gro&szlig;e Schrift" /></a>');
								document.write('<a href="index.php" title="Schrift zur&uuml;cksetzen" onclick="resetFontSize(); return false;"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/font_size_normal.png" alt="Schrift zur&uuml;cksetzen" /></a>');
								document.write('<a href="index.php" title="kleine Schrift" onclick="smallFontSize(); return false;"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/font_size_small.png" alt="kleine Schrift" /></a></p>');
							//]]>
						</script>
					</div>
			</div>  
			<div class="clear"></div>
			
			<!-- logo -->
			<div class="grid_24" id="logo">
				<a href="<?php echo $this->baseurl ?>" title="Home">
					<?php if(($useCustomTemplateColor == "yes") && ($customTemplateColor != "")) : ?>
						<?php if($customLogo != "") : ?>
							<img src="<?php echo $this->baseurl ?>/<?php echo $customLogo ?>" alt="wedding, marriage, engagement, rings, wedding dress, flowers, roses logo" title="<?php echo $app->getCfg('sitename'); ?>" />
						<?php else : ?>
							<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/custom/logo.jpg" alt="wedding, marriage, engagement, rings, wedding dress, flowers, roses logo" title="<?php echo $app->getCfg('sitename'); ?>" />
						<?php endif; ?>
					<?php else: ?>
						<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/<?php echo $predefinedTemplateColor; ?>/logo.jpg" alt="wedding, marriage, engagement, rings, wedding dress, flowers, roses logo" title="<?php echo $app->getCfg('sitename'); ?>" />
					<?php endif; ?>
				</a>
				
			</div>
			<div class="clear"></div>
			<?php if($showSiteName == "yes") : ?>
				<div class="grid_24" id="sitename-logo" <?php echo ($googleWebFonts == "yes") ? "style=\"font-family: '".$googleWebFont."', arial, serif;\"" : "" ; ?>>
					<div class="content"><?php //echo $app->getCfg('sitename'); ?></div>
				</div>
			<?php endif; ?>
			<div class="clear"></div>
			
			
			
			
			
			<!-- header navigation -->
			<div class="grid_24" id="header-nav">
				<jdoc:include type="modules" name="position-1" style="xhtml" />
			</div>
			<div class="clear"></div>
			
			<!-- breadcrumb -->
			<?php if ($this->countModules('position-2')) : ?>
			<div class="grid_24" id="bread">
				<jdoc:include type="modules" name="position-2" style="xhtml" />
			</div>
			<div class="clear"></div>
			<?php endif; ?>
			
			<?php if ($this->countModules('position-6')) : ?>				
				<div class="grid_5" id="left"><jdoc:include type="modules" name="position-7" style="xhtml" /></div>
				<div class="grid_14" id="content"><jdoc:include type="message" /><?php require_once(dirname(__FILE__) .'/css/system.php'); ?>
				<jdoc:include type="component" /></div>
				<div class="grid_5" id="right"><div class="insideright"><jdoc:include type="modules" name="position-6" style="xhtml" /></div></div>
			<?php else : ?>
				<div class="grid_5" id="left"><jdoc:include type="modules" name="position-7" style="xhtml" /></div>
				<div class="grid_19" id="content_big"><jdoc:include type="message" /><jdoc:include type="component" /></div>
			<?php endif; ?>
			<div class="clear"></div>
			
			<div class="grid_8" id="user1"><div class="content"><jdoc:include type="modules" name="position-9" style="xhtml" /></div></div>
			<div class="grid_8" id="user2"><jdoc:include type="modules" name="position-10" style="xhtml" /></div>
			<div class="grid_8" id="user5"><div class="content"><jdoc:include type="modules" name="position-11" style="xhtml" /></div></div>
			<div class="clear"></div>
			
			
			<!-- footer -->
			<div class="grid_24" id="footer">
				<div id="foot">
					&copy; <?php echo date('Y'); ?> <?php echo $app->getCfg('sitename'); ?> 					
					<?php if(($useCustomTemplateColor == "no") || ($customTemplateColor == "")) : ?>
							<?php if(in_array($predefinedTemplateColor, array('wedding1', 'wedding2', 'wedding3'))) : ?>
								| <?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?><a href="http://joomla3x.ru/" target="_blank" title="joomla 3">joomla3x</a><?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?>
							<?php elseif(in_array($predefinedTemplateColor, array('wedding4'))) : ?>
								| <?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?><a href="http://joomla3x.ru/" target="_blank" title="joomla 3">joomla3x</a><?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?>
							<?php else : ?>
								| <?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?><a href="http://joomla3x.ru/" target="_blank" title="joomla 3">joomla3x</a><?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?>
							<?php endif; ?>					   
					 
					 <?php 
						switch($predefinedTemplateColor)
						{
							case "general1": $showLogoCopyright = true;
											 $logoUserUrl = "http://www.pixelio.de/index.php?ACTION=profile&amp;user_id=28197";
											 $logoUser = "Telemarco";
											 $logoOriginalUrl = "http://www.pixelio.de/media/485485";
											 $logoOriginalName = "Sonne am Dachstein";
											 break;
							case "general2": $showLogoCopyright = true;
											 $logoUserUrl = "http://www.pixelio.de/index.php?ACTION=profile&amp;user_id=291435";
											 $logoUser = "martelo";
											 $logoOriginalUrl = "http://www.pixelio.de/media/386127";
											 $logoOriginalName = "Sonnenuntergang";
											 break;
							case "general3": $showLogoCopyright = true;
											 $logoUserUrl = "http://www.pixelio.de/index.php?ACTION=profile&amp;user_id=29435";
											 $logoUser = "falco";
											 $logoOriginalUrl = "http://www.pixelio.de/media/125927";
											 $logoOriginalName = "Urlaub 2006";
											 break;
							case "general4": $showLogoCopyright = true;
											 $logoUserUrl = "http://www.pixelio.de/index.php?ACTION=profile&amp;user_id=235345";
											 $logoUser = "AndreasD200";
											 $logoOriginalUrl = "http://www.pixelio.de/media/245429";
											 $logoOriginalName = "Kalifornien";
											 break;
							default: $showLogoCopyright = false; 		
						
						}
					 ?>
					 <?php if($showLogoCopyright) : ?>
						| <?php /* Dieser Link muss entfernt werden, wenn das Logo nicht verwendet wird! This link must be removed if logo is not in use! */ ?>Logo by 
						<a href="<?php echo $logoUserUrl;?>" rel="nofollow" target="_blank" ><?php echo $logoUser;?></a> - <a href="http://www.pixelio.de" rel="nofollow" target="_blank" style="font-weight: normal; text-decoration: none;">pixelio.de</a><!-- / <a href="<?php echo $logoOriginalUrl;?>" rel="nofollow" target="_blank"><?php echo $logoOriginalName;?></a>-->
						<?php /* Dieser Link muss entfernt werden, wenn das Logo nicht verwendet wird! This link must be removed if logo is not in use! */ ?> 
					 <?php endif; ?>
					 
					<?php else : ?>
					 | <?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?>Template by <a href="http://www.1sr.de" target="_blank" title="Projekte zu Suchmaschinenoptimierung, Webseitenerstellung, Internet Marketing - Agentur aus Thüringen">1sr.de</a><?php /* Dieser Link darf NICHT entfernt werden! This link may NOT be removed! */ ?>
					<?php endif; ?>
					
					
				</div>
			</div>
			<div class="clear"></div>			
		</div>
	</body>
</html>