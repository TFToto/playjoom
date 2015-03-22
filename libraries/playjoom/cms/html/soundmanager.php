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
abstract class JHtmlSoundManager {
	/**
	 * @var    array  Array containing information for loaded files
	 * @since  3.0
	 */
	protected static $loaded = array();

	/**
	 * Method to load the SoundManager JavaScript framework into the document head
	 *
	 *
	 * @return  void
	 *
	 * @since   0.9.10
	*/
	public static function sm_framework() {
		// Only load once
		if (!empty(static::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'lib_playjoom/soundmanager/soundmanager2-jsmin.js', false, true, false, false);

		static::$loaded[__METHOD__] = true;

		return;
	}
	/**
	 * Method to load the JavaScripts for barplayer ui into the document head
	 *
	 *
	 * @return  void
	 *
	 * @since   0.9.10
	 */
	public static function bar_ui() {
		// Only load once
		if (!empty(static::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'lib_playjoom/soundmanager/bar-ui.js', false, true, false, false);
		JHtml::_('stylesheet', 'lib_playjoom/soundmanager/bar-ui.css', false, true);

		static::$loaded[__METHOD__] = true;

		return;
	}
	/**
	 * Method to load the JavaScripts for barplayer ui into the document head
	 *
	 *
	 * @return  void
	 *
	 * @since   0.9.10
	 */
	public static function threehundredsixtyplayer() {
		// Only load once
		if (!empty(static::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'lib_playjoom/soundmanager/berniecode-animator.js', false, true, false, false);
		JHtml::_('script', 'lib_playjoom/soundmanager/360player.js', false, true, false, false);
		JHtml::_('script', 'lib_playjoom/soundmanager/excanvas.js', false, true, false, false);
		JHtml::_('stylesheet', 'lib_playjoom/soundmanager/360player.css', false, true);
		JHtml::_('stylesheet', 'lib_playjoom/soundmanager/360player-visualization.css', false, true);

		static::$loaded[__METHOD__] = true;

		return;
	}
	/**
	 * Method to load the JavaScripts for playbutton ui into the document head
	 *
	 *
	 * @return  void
	 *
	 * @since   0.9.10
	 */
	public static function playbutton() {
		// Only load once
		if (!empty(static::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'lib_playjoom/soundmanager/mp3-player-button.js', false, true, false, false);
		JHtml::_('stylesheet', 'lib_playjoom/soundmanager/mp3-player-button.css', false, true);

		static::$loaded[__METHOD__] = true;

		return;
	}
}
