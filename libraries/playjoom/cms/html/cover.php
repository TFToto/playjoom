<?php
/**
 * @package     PlayJoom.Library
 * @subpackage  Cover
 *
 * @copyright Copyright (C) 2010-2016 by teglo. All rights reserved.
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
	 * @param integre $cover_size Value of the width size of a cover
	 * @param integre $width
	 * @param integre $height
	 * @param string $view current view as string value
	 *
	 * @return integer calculated height value with correct image radio
	 */
	public static function calcImageSize($cover_size, $view, $width, $height) {

		//Calculate the smaller cover values
		if ($width && $height) {
			if ($width > $height) {
				$ratio = $width / $height;
				return $cover_size / $ratio;
			} else {
				$ratio = $height / $width;
				return $cover_size / $ratio;
			}
		} else {
			return $cover_size;
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

		$dispatcher	= JDispatcher::getInstance();
		
		$document = JFactory::getDocument();
		//Get setting values from xml file
		$app		= JFactory::getApplication();

		//If link comes of a module, then get also the params of this module
		if ($moduletype = base64_decode($app->input->get('moduletype')) 
		    && $moduletitle = base64_decode($app->input->get('moduletitle'))) {

		    $module = JModuleHelper::getModule($moduletype,$moduletitle);
		    $module_params = new JRegistry($module->params);
		} else {
		    $module_params = null;
		}

		//Get parameters for current menu item
		$menuitem   = $app->getMenu()->getActive();
		if ($menuitem) {
		    $params = $menuitem->params;
		} else {
		    $params = JComponentHelper::getParams( 'com_playjoom' );;
		}

		if (!empty(static::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'lib_playjoom/cover/jquery.unveil.js', false, true, false, false);

		if (isset($params)) {
		    $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'coversize is: '.$params->get($view.'_cover_size',100), 'priority' => JLog::INFO, 'section' => 'site')));
		    $coverframe_height = $params->get($view.'_cover_size',100) +60;
		    $cover_width = $params->get($view.'_cover_size',100);
		} else {
		    if ($module_params) {
			$coverframe_height = $module_params->get($view.'_cover_size',100) +60;
			$cover_width = $module_params->get($view.'_cover_size',100);
		    } else {
			$coverframe_height = 100 +60;
			$cover_width = 100;
		    }
		}

		//get cover size
		$defaultcoverimg = getimagesize(JURI::base(false).'media/lib_playjoom/ajaxdata/images/cd-mono.png');
		$defaultcoverimg_height = round(self::calcImageSize($cover_width, $view, $defaultcoverimg[0], $defaultcoverimg[1])) -10;

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
						width: '.$cover_width.'px;
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
					jQuery('section').click(function() {
						jQuery('img.cover').unveil(10, function() {
							jQuery(this).load(function() {
								this.style.opacity = 1;
							});
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
