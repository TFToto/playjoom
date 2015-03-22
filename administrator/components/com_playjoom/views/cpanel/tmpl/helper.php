<?php
/**
 * @package Joomla 2.5.x
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * @package		Joomla.Administrator
 * @subpackage	mod_quickicon
 * @since		1.6
 */
abstract class QuickIconHelper
{
	/**
	 * Stack to hold default buttons
	 *
	 * @since	1.6
	 */
	protected static $buttons = array();

	/**
	 * Helper method to generate a button in administrator panel
	 *
	 * @param	array	A named array with keys link, image, text, access and imagePath
	 *
	 * @return	string	HTML for button
	 * @since	1.6
	 */
	public static function button($button)
	{
		if (!empty($button['access'])) {
			if (is_bool($button['access']) && $button['access'] == false) {
				return '';
			}

			// Take each pair of permission, context values.
			for ($i = 0, $n = count($button['access']); $i < $n; $i += 2) {
				if (!JFactory::getUser()->authorise($button['access'][$i], $button['access'][$i+1])) {
					return '';
				}
			}
		}

		if (empty($button['imagePath'])) {
			$template = JFactory::getApplication()->getTemplate();
			$button['imagePath'] = '/templates/'. $template .'/images/header/';
		}

		ob_start();
		require JModuleHelper::getLayoutPath('mod_pj_quickicon', 'default');
		$html = ob_get_clean();
		return $html;
	}

	/**
	 * Helper method to return button list.
	 *
	 * This method returns the array by reference so it can be
	 * used to add custom buttons or remove default ones.
	 *
	 * @return	array	An array of buttons
	 * @since	1.6
	 */
	public static function &getButtons()
	{
		if (empty(self::$buttons)) {
			self::$buttons = array(
				array(
					'link' => JRoute::_('index.php?option=com_playjoom&view=audiotracks'),
					'params' => null,
					'imagePath' => '/administrator/components/com_playjoom/images/header/',
					'image' => 'icon-48-tracks-managment.gif',
					'text' => JText::_('COM_PLAYJOOM_AUDIOTRACK_MANAGER_PLAYJOOM'),
					'access' => array('core.manage', 'com_content', 'core.create', 'com_content', )
				),
				array(
					'link' => JRoute::_('index.php?option=com_playjoom&view=artists'),
					'params' => null,
					'imagePath' => '/administrator/components/com_playjoom/images/header/',
					'image' => 'icon-48-artists-managment.gif',
					'text' => JText::_('COM_PLAYJOOM_ARTIST_MANAGER_PLAYJOOM'),
					'access' => array('core.manage', 'com_content')
				),
				array(
					'link' => JRoute::_('index.php?option=com_playjoom&view=albums'),
				    'params' => null,
					'imagePath' => '/administrator/components/com_playjoom/images/header/',
					'image' => 'icon-48-albums-managment.gif',
					'text' => JText::_('COM_PLAYJOOM_ALBUM_MANAGER_PLAYJOOM'),
					'access' => array('core.manage', 'com_content')
				),
				array(
					'link' => JRoute::_('index.php?option=com_playjoom&view=covers'),
					'params' => null,
					'imagePath' => '/administrator/components/com_playjoom/images/header/',
					'image' => 'icon-48-cover-managment.gif',
					'text' => JText::_('COM_PLAYJOOM_COVER_MANAGER_PLAYJOOM'),
					'access' => array('core.manage', 'com_media')
				),
				array(
					'link' => JRoute::_('index.php?option=com_categories&view=categories&extension=com_playjoom'),
					'params' => null,
					'imagePath' => '/administrator/components/com_playjoom/images/header/',
					'image' => 'icon-48-category.png',
					'text' => JText::_('COM_PLAYJOOM_CATEGORY_MANAGER_PLAYJOOM'),
					'access' => array('core.manage', 'com_menus')
				),
			    array(
				    'link' => JRoute::_('index.php?option=com_categories&view=categories&extension=com_playjoom.playlist'),
					'params' => null,
			    	'imagePath' => '/administrator/components/com_playjoom/images/header/',
					'image' => 'icon-48-category.png',
					'text' => JText::_('COM_PLAYJOOM_PLAYLIST_CATEGORY_MANAGER_PLAYJOOM'),
					'access' => array('core.manage', 'com_menus')
				),
				array(
					'link' => JRoute::_('index.php?option=com_config&view=component&component=com_playjoom'),
					'params' => null,
					'imagePath' => '/administrator/components/com_playjoom/images/header/',
					'image' => 'icon-32-config.png',
					'text' => JText::_('COM_PLAYJOOM_PLAYJOOM_OPTIONS'),
					'access' => array('core.manage', 'com_config', 'core.admin', 'com_config')
				)
			);
		}

		return self::$buttons;
	}
}
