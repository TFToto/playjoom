<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  lib_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */


defined('_JEXEC') or die;

/**
 * cover library.
 *
 * @package		PlayJoom
 * @subpackage	lib_cover
 */
abstract class JHtmlCover {
	/**
	 * @var    array  Array containing information for loaded files
	 * @since  3.0
	 */
	protected static $loaded = array();

	/**
	 * Method for calculate the image height with width as basis value
	 *
	 * @param integre $width
	 * @param integre $height
	 *
	 * @return integer calculated height value with correct image radio
	 */
	public static function calcImageSize($view, $width, $height) {

		//Get setting values from xml file
		$app		= JFactory::getApplication();

		//Get parameters for current menu item
		$menuitem   = $app->getMenu()->getActive();
		$params = $menuitem->params;

		//Calculate the smaller cover values
		if ($width && $height) {
			if ($width > $height) {
				$ratio = $width / $height;
				return $params->get($view.'_cover_size',100) / $ratio;
			} else {
				$ratio = $height / $width;
				return $params->get($view.'_cover_size',100) / $ratio;
			}
		} else {
			return $params->get($view.'_cover_size',100);
		}
	}
	/**
	 * Method to load the SoundManager JavaScript framework into the document head
	 *
	 *
	 * @return  void
	 *
	 * @since   0.9.10
	*/
	public static function library($view) {

		$document = JFactory::getDocument();

		//Get setting values from xml file
		$app		= JFactory::getApplication();

		//Get parameters for current menu item
		$menuitem   = $app->getMenu()->getActive();
		$params = $menuitem->params;

		if (!empty(static::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'lib_playjoom/cover/jquery.unveil.js', false, true, false, false);

		//get cover size
		//$pj_params = JComponentHelper::getParams('com_playjoom');
		$defaultcoverimg = getimagesize(JURI::base(false).'media/lib_playjoom/ajaxdata/images/cd-mono.png');
		$defaultcoverimg_height = round(self::calcImageSize($view, $defaultcoverimg[0], $defaultcoverimg[1])) -10;
		$defaultcoverimg_width = $params->get($view.'_cover_size',100) -10;
		$coverframe_height = $params->get($view.'_cover_size',100) +60;

		$styles  = '<style type="text/css">';
		$styles .= ' 
					 ul.list_of_albums li.album_item {
						background-color: #ffff00;
						background: url("'.JURI::base(false).'media/lib_playjoom/ajaxdata/images/spinner.gif");
						background-repeat: no-repeat;
						background-position: center 25%;
					}
					 ul.list_of_albums li.default_cover_class a {
						margin-top: '.$defaultcoverimg_height.'px;
						display: block;
					}
					 ul.list_of_albums li {
						width: '.$params->get($view.'_cover_size',100).'px;
						height: '.$coverframe_height.'px;
					}
					 img.cover {
						opacity: 0;
						transition: opacity .3s ease-in;
					}
				';
		$styles .= '</style>';
		$document->addCustomTag($styles);

		$js = "
				jQuery(function() {
					jQuery('img.cover').unveil(10, function() {
						jQuery(this).load(function() {
							this.style.opacity = 1;
  						});
					});
				});
		";
		//load external scripts
		$document->addScriptDeclaration($js);

		static::$loaded[__METHOD__] = true;

		return;
	}
}
