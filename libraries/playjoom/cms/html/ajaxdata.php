<?php
/**
 * @package Joomla 3.0.x
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Plugin
 * @copyright Copyright (C) 2010-2015 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */


defined('_JEXEC') or die;

/**
 * playlist plugin.
 *
 * @package		PlayJoom
 * @subpackage	plg_barplayer
 */
abstract class JHtmlAjaxData {
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
		JHtml::_('script', 'lib_playjoom/tools/jquery.base64.js', false, true, false, false);
		JHtml::_('script', 'lib_playjoom/tools/verge.min.js', false, true, false, false);
		JHtml::_('script', 'lib_playjoom/ajaxdata/default.js', false, true, false, false);

		//get cover size
		//$pj_params = JComponentHelper::getParams('com_playjoom');
		$defaultcoverimg = getimagesize(JURI::base(false).'media/lib_playjoom/ajaxdata/images/cd-mono.png');
		$defaultcoverimg_height = round(self::calcImageSize($view, $defaultcoverimg[0], $defaultcoverimg[1])) -10;
		$defaultcoverimg_width = $params->get($view.'_cover_size',100) -10;
		$coverframe_height = $params->get($view.'_cover_size',100) +60;

		$styles  = '<style type="text/css">';
		$styles .= ' ul.list_of_albums li.loading_class {
						background-color: #ffffff;
						background: url("'.JURI::base(false).'media/lib_playjoom/ajaxdata/images/spinner.gif");
						background-repeat: no-repeat;
						background-position: center 25%;
					}
					 ul.list_of_albums li.default_cover_class {
						background-color: #ffffff;
						background: url("'.JURI::base(false).'media/lib_playjoom/ajaxdata/images/cd-mono.png");
						background-repeat: no-repeat;
						background-position: center 5px;
						background-size: '.$defaultcoverimg_width.'px '.$defaultcoverimg_height.'px;
					}
					ul.list_of_albums li.default_cover_class a {
						margin-top: '.$defaultcoverimg_height.'px;
						display: block;
					}
					ul.list_of_albums li {
						width: '.$params->get($view.'_cover_size',100).'px;
						height: '.$coverframe_height.'px;
					}
				';
		$styles .= '</style>';
		$document->addCustomTag($styles);

		static::$loaded[__METHOD__] = true;

		return;
	}
}
