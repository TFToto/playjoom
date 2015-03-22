<?php
/**
 * Contains the helper methods for the admin PlayJoom module quickicon.
 *
  * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage modules.pj_quickicon
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * @package PlayJoom.Admin
 * @subpackage modules.pj_quickicon
 * @since		1.6
 */
abstract class modPJQuickIconHelper {

	/**
	 *
	 * @var array string
	 */
	protected static $buttons = array();
	protected static $PJbuttons = array();

	/**
	 * Helper method to return button list.
	 *
	 * This method returns the array by reference so it can be
	 * used to add custom buttons or remove default ones.
	 *
	 * @param	JRegistry	The module parameters.
	 *
	 * @return	array	An array of buttons
	 * @since	1.6
	 */
	public static function &getButtons($params)
	{
		$key = (string)$params;

		if (!isset(self::$buttons[$key])) {
			$context = $params->get('context', 'mod_pj_quickicon');

			if ($context == 'mod_pj_quickicon')
			{
				// Load mod_quickicon language file in case this method is called before rendering the module
			JFactory::getLanguage()->load('mod_pj_quickicon');

			self::$buttons[$key] = array(
					array(
						'link' => JRoute::_('index.php?option=com_playjoom&view=audiotracks'),
						'params' => null,
						'id'=> null,
						'imagePath' => null,
						'image' => ' fa fa-list-alt',
						'text' => JText::_('MOD_PJ_QUICKICON_TRACKSMANAGER'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_TRACKS'
					),
					array(
						'link' => JRoute::_('index.php?option=com_playjoom&view=media'),
						'params' => null,
						'id'=> null,
						'imagePath' => '/administrator/components/com_playjoom/images/header/',
						'image' => 'fa fa-music',
						'text' => JText::_('MOD_PJ_QUICKICON_ADD_TRACKS'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_TRACKS'
					),
					array(
						'link' => JRoute::_('index.php?option=com_playjoom&view=artists'),
						'params' => null,
						'id'=> null,
						'imagePath' => '/administrator/components/com_playjoom/images/header/',
						'image' => 'fa fa-users',
						'text' => JText::_('MOD_PJ_QUICKICON_ARTISTSMANAGER'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_TRACKS'
					),
					array(
						'link' => JRoute::_('index.php?option=com_playjoom&view=albums'),
						'params' => null,
						'id'=> null,
						'imagePath' => '/administrator/components/com_playjoom/images/header/',
						'image' => 'fa fa-picture-o',
						'text' => JText::_('MOD_PJ_QUICKICON_ALBUMSMANAGER'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_TRACKS'
					),
					array(
						'link' => JRoute::_('index.php?option=com_playjoom&view=covers'),
						'params' => null,
						'id'=> null,
						'imagePath' => '/administrator/components/com_playjoom/images/header/',
						'image' => 'fa fa-file-image-o',
						'text' => JText::_('MOD_PJ_QUICKICON_COVERSMANAGER'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_TRACKS'
					),
					array(
						'link' => JRoute::_('index.php?option=com_categories&view=categories&extension=com_playjoom'),
						'params' => null,
						'id'=> null,
						'imagePath' => '/administrator/components/com_playjoom/images/header/',
						'image' => 'fa fa-folder-o',
						'text' => JText::_('MOD_PJ_QUICKICON_GENRESMANAGER'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_CATEGORIES'
					),
					array(
						'link' => JRoute::_('index.php?option=com_categories&view=categories&extension=com_playjoom.playlist'),
						'params' => null,
						'id'=> null,
						'imagePath' => '/administrator/components/com_playjoom/images/header/',
						'image' => 'fa fa-play',
						'text' => JText::_('MOD_PJ_QUICKICON_PLAYLISTSMANAGER'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_CATEGORIES'
					),
					array(
						'link' => JRoute::_('index.php?option=com_categories&view=categories&extension=com_playjoom.trackfilter'),
						'params' => null,
						'id'=> null,
						'imagePath' => '/administrator/components/com_playjoom/images/header/',
						'image' => 'fa fa-filter',
						'text' => JText::_('MOD_PJ_QUICKICON_TRACKFILTERMANAGER'),
						'access' => array('core.manage', 'com_playjoom', 'core.create', 'com_playjoom', ),
						'group' => 'MOD_PJ_QUICKICON_CATEGORIES'
					)
			    );

			//Get user objects
			$user	= JFactory::getUser();

			if ($user->authorise('core.admin', 'com_playjoom')) {

				$PJbuttons = array(

						array(
								'link' => JRoute::_('index.php?option=com_users'),
								'params' => null,
								'id'=> null,
								'imagePath' => '/administrator/components/com_playjoom/images/header/',
								'image' => 'fa fa-user',
								'text' => JText::_('MOD_QUICKICON_USER_MANAGER'),
								'access' => array('core.manage', 'com_users'),
								'group' => 'MOD_PJ_QUICKICON_USERS'
						),
						array(
								'link' => JRoute::_('index.php?option=com_config'),
								'params' => null,
								'id'=> null,
								'imagePath' => '/administrator/components/com_playjoom/images/header/',
								'image' => 'fa fa-cogs',
								'text' => JText::_('MOD_QUICKICON_GLOBAL_CONFIGURATION'),
								'access' => array('core.manage', 'com_config', 'core.admin', 'com_config'),
								'group' => 'MOD_PJ_QUICKICON_CONFIGURATIONS'
						),
						array(
								'link' => JRoute::_('index.php?option=com_config&view=component&component=com_playjoom'),
								'params' => null,
								'id'=> null,
								'imagePath' => '/administrator/components/com_playjoom/images/header/',
								'image' => 'fa fa-cog',
								'text' => JText::_('MOD_PJ_QUICKICON_PLAYJOOM_OPTIONS'),
								'access' => array('core.manage', 'com_config', 'core.admin', 'com_config'),
								'group' => 'MOD_PJ_QUICKICON_CONFIGURATIONS'
						),
				);

				//Marge standard icons with PlayJoom admin icons
				self::$buttons[$key] = array_merge(self::$buttons[$key],$PJbuttons);

				// Include buttons defined by published quickicon plugins
				JPluginHelper::importPlugin('quickicon');
				$app = JFactory::getApplication();
				$arrays = (array) $app->triggerEvent('onGetIcons', array($context));

				foreach ($arrays as $response)
				{
					foreach ($response as $icon)
					{
						$default = array(
								'link' => null,
								'image' => 'cog',
								'text' => null,
								'access' => true,
								'group' => 'MOD_QUICKICON_EXTENSIONS'
						);
						$icon = array_merge($default, $icon);

						if (!is_null($icon['link']) && !is_null($icon['text']))
						{
							self::$buttons[$key][] = $icon;
						}
					}
				}
			}
		}

		else {
			self::$buttons[$key] = array();
		}
	}

	return self::$buttons[$key];
}

	/**
	* Classifies the $buttons by group
	*
	* @param   array  $buttons  The buttons
	*
	* @return  array  The buttons sorted by groups
	*
	* @since   3.2
	*/
	public static function groupButtons($buttons) {

		$groupedButtons = array();

		foreach ($buttons as $button) {
		$groupedButtons[$button['group']][] = $button;
		}
		return $groupedButtons;
	}
	/**
	 * Get the alternate title for the module
	 *
	 * @param	JRegistry	The module parameters.
	 * @param	object		The module.
	 *
	 * @return	string	The alternate title for the module.
	 */
	public static function getTitle($params, $module)
	{
		$key = $params->get('context', 'mod_pj_quickicon') . '_title';
		if (JFactory::getLanguage()->hasKey($key))
		{
			return JText::_($key);
		}
		else
		{
			return $module->title;
		}
	}
}
