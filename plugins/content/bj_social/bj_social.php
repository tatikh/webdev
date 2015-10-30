<?php
/**
 * @version		$Id: bj_facebook.php 0001 2010-11-03 hadoanngoc@gmail.com
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2008 - 2010 BYJOOMLA.COM
 * @license		GNU/GPL, see LICENSE.php 
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * ByJoomla Social integrated buttons: LIKE and TWEET
 * @package		BJ Social
 * @subpackage	Content
 * @since 		1.5
 */
class plgContentBj_social extends JPlugin
{

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param object $params  The object that holds the plugin parameters
	 * @since 1.5
	 */
	function plgContentExample( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}

	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();
		
	}

	/**
	 * Example before display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 * @return	string
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();
		global $mainframe;
		
		$position = $this->params->def('position','0');
		$link = $this->params->def('link','0');
		$exclude_cats = ',' . $this->params->def('exclude_cat','') . ',';
		$exclude_articles = ',' . $this->params->def('exclude_article','') . ',';
		
		if(!(strpos($exclude_articles,','.$article->id.',') !== false) && !(strpos($exclude_cats,','.$article->catid.',') !== false))
		{
			if($link == '0'){
				// GET article link
				$slug = ($article->alias != '') ? ($article->id . ':' . $article->alias):($article->id);
				$catslug = ($article->cat_alias != '') ? ($article->catid . ':' . $article->cat_alias):($article->catid);
				$sectionid = ($article->section_alias != '') ? ($article->sectionid . ':' . $article->section_alias):($article->sectionid);
				
				require_once(JPATH_BASE . "/components/com_content/helpers/route.php");
				$domain = JURI::base();
				$href = substr($domain,0,strlen($domain)-1) . JRoute::_(ContentHelperRoute::getArticleRoute($slug, $catslug, $sectionid));
			} else {
				$href = '';
			}
					
			if($position == '1'){
				$article->text = $this->printButton($params,$href).$article->text;
			} else if($position == '2'){
				$article->text = $article->text.$this->printButton($params,$href);
			}
			
			if($article->params->def('show_intro')){
				if($position == '1'){
					$article->introtext = $this->printButton($params,$href).$article->introtext;
				} else if($position == '2'){
					$article->introtext = $article->introtext.$this->printButton($params,$href);
				}
			}
		}
	}

	/**
	 * Example after display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 * @return	string
	 */
	function onAfterDisplayContent( &$article, &$params, $limitstart )
	{
		global $mainframe;

		return '';
	}

	/**
	 * Example before save content method
	 *
	 * Method is called right before content is saved into the database.
	 * Article object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 * 	You can set the error by calling $article->setError($message)
	 *
	 * @param 	object		A JTableContent object
	 * @param 	bool		If the content is just about to be created
	 * @return	bool		If false, abort the save
	 */
	function onBeforeContentSave( &$article, $isNew )
	{
		global $mainframe;

		return true;
	}

	/**
	 * Example after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 *
	 * @param 	object		A JTableContent object
	 * @param 	bool		If the content is just about to be created
	 * @return	void
	 */
	function onAfterContentSave( &$article, $isNew )
	{
		global $mainframe;

		return true;
	}
	
	function printButton( &$params, $href){
		// get parameters		
		// Facebook params
		$show_facebook = $this->params->def('show_facebook','1');
		$layout = $this->params->def('layout','standard');
		$codetype = $this->params->def('codetype','XFBML');
		$font = $this->params->def('font','');
		$color = $this->params->def('color','light');
		$action = $this->params->def('verb','like');
		$face = $this->params->def('showface','true');
		$width = $this->params->def('width','450');
		
		// Tweet params
		$show_tweet = $this->params->def('show_tweet','1');
		$data_text = $this->params->def('tweet_text','');
		$data_count = $this->params->def('tweet_button','horizontal');; //vertical; horizontal; none
		$data_lang = $this->params->def('tweet_lang','');; // en;fr;de;it;ja;ko;ru;es;tr
		
		$link_button = "<div class='article_social_buttons'><table><tr>";	
		
		if($show_tweet){
		$link_button .= '<td class="bj-social-twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$href.'" ' . ($data_text == '' ? '' : 'data-text="'.$data_text.'"') . ' data-count="'.$data_count.'" ' . ($data_lang == ''? '' : 'data-lang="'.$data_lang.'"') . '>Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></td>';
		}
		if($show_facebook){
			$link_button .= '<td class="bj-social-facebook">';
		if($codetype == 'XFBML'){
			$href = ($href == '')? '' : 'href="'.$href.'"';
			
			$link_button .= '<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like layout="'.$layout.'" width="'.$width.'" font="'.$font.'" '.$href.' colorscheme="'.$color.'" action="'.$action.'"></fb:like>';
		} else {
			$href = ($href == '')? '' : 'href="'.$href.'"&amp;';
			
			$link_button .= '<iframe src="http://www.facebook.com/plugins/like.php?'.$href.'layout='.$layout.'&amp;show_faces='.$face.'&amp;width='.$width.'&amp;action='.$action.'&amp;font='.str_replace(' ','+',$font).'&amp;colorscheme='.$color.'&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:21px;" allowTransparency="true"></iframe>';
		}
			$link_button .= '</td>';
		}
		
		$link_button .= "</tr></table></div><div class='clearer'><!-- --></div>";
		
		return $link_button;
	}
}
