<?php
/**
 * Contains the Route helper methods for the PlayJoom.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Contains the Route helper methods for the PlayJoom.
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.helpers
 */
abstract class PlayJoomHelperRoute {

	protected static $lookup;
	/**
	 * @param	int	The route of the newsfeed
	 */
	public static function getPlayJoomRoute($id, $catid) {

		//Create the link
		$link = 'index.php?option=com_playjoom&view=playjoom&id='. $id;
		if ($catid > 1)	{
			$link .= '&catid='.$catid;
		}

		return $link;
	}

	/**
	 * Method for to create a link for tag items
	 *
	 * @param	int	The route of the newsfeed
	 */
	public static function getPlayJoomTrackTagRoute($id, $catid) {

		//Create the link
		$link = 'index.php?option=com_playjoom&view=broadcast&format=raw&id='. $id;
		return $link;
	}

	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;
			$category = $catid;
		}
		else
		{
			$id = (int) $catid;
			$category = JCategories::getInstance('PlayJoom')->get($id);
		}

		if($id < 1)
		{
			$link = '';
		}
		else
		{
			$needles = array(
				'category' => array($id)
			);

			if ($item = self::_findItem($needles))
			{
				$link = 'index.php?Itemid='.$item;
			}
			else
			{
				//Create the link
				$link = 'index.php?option=com_playjoom&view=category&id='.$id;
				if($category)
				{
					$catids = array_reverse($category->getPath());
					$needles = array(
						'category' => $catids,
						'categories' => $catids
					);
					if ($item = self::_findItem($needles)) {
						$link .= '&Itemid='.$item;
					}
					elseif ($item = self::_findItem()) {
						$link .= '&Itemid='.$item;
					}
				}
			}
		}

		return $link;
	}

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_playjoom');
			$items		= $menus->getItems('component_id', $component->id);
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					if (!isset(self::$lookup[$view])) {
						self::$lookup[$view] = array();
					}
					if (isset($item->query['id'])) {
						self::$lookup[$view][$item->query['id']] = $item->id;
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach($ids as $id)
					{
						if (isset(self::$lookup[$view][(int)$id])) {
							return self::$lookup[$view][(int)$id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active) {
				return $active->id;
			}
		}

		return null;
	}
	/**
	 * Method for to get the proper ID for viewer name
	 *
	 * @param string $viewer name of the PlayJoom Viewer
	 * @param string $KeyValues to add additional url methods
	 * @return string link to the viewer
	 */
	public static function getPJlink($viewer, $KeyValues=null) {

		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();

		$component	= JComponentHelper::getComponent('com_playjoom');
		$items		= $menus->getItems('component_id', $component->id);

		if ($items) {
			foreach ($items as $item)
			{
				switch ($viewer)
				{
					case 'artists':
						if ($viewer == $item->query['view']) {
							if ($KeyValues == null) {
								return 'index.php?option=com_playjoom&view=artist&artist='.JRequest::getVar('artist').'&Itemid='.$item->id;
							} else {
								if (preg_match("/cat/i", $KeyValues)) {
									return 'index.php?option=com_playjoom&view=artist&artist='.JRequest::getVar('artist').'&Itemid='.JRequest::getVar('Itemid').$KeyValues;
								} else {
									return 'index.php?option=com_playjoom&view=artist&Itemid='.$item->id.$KeyValues;
								}
							}
				        }
						break;
					case 'albums':
						if ($viewer == $item->query['view']) {
						if ($KeyValues == null) {
					    		return 'index.php?option=com_playjoom&view=album&Itemid='.$item->id;
					    	} else {
					    		return 'index.php?option=com_playjoom&view=album&Itemid='.$item->id.$KeyValues;
					    	}
				        }
						break;
				    case 'genres':
					    if ($viewer == $item->query['view'] ) {
					    	if ($KeyValues == null) {
					    		return 'index.php?option=com_playjoom&view=genres&Itemid='.$item->id;
					    	} else {
					    		return 'index.php?option=com_playjoom&view=genre&Itemid='.$item->id.$KeyValues;
					    	}
						}
						break;
					case 'alphabetical':
				        if ($viewer == $item->query['view']) {
				        	if ($KeyValues == null) {
				        		return 'index.php?option=com_playjoom&view=alphabetical&Itemid='.$item->id;
						    } else {
							    return 'index.php?option=com_playjoom&view=alphabetical&Itemid='.$item->id.$KeyValues;
							    }
				        }
					break;
					default:
						return null;

				}
			}
		}
	}

	/**
	 * Tries to load the router for the component and calls it. Otherwise uses getTagRoute.
	 *
	 * @param   integer  $contentItemId     Component item id
	 * @param   string   $contentItemAlias  Component item alias
	 * @param   integer  $contentCatId      Component item category id
	 * @param   string   $language          Component item language
	 * @param   string   $typeAlias         Component type alias
	 * @param   string   $routerName        Component router
	 *
	 * @return  string  URL link to pass to JRoute
	 *
	 * @since   3.1
	 */
	public static function getItemRoute($contentItemId, $contentItemAlias, $contentCatId, $language, $typeAlias, $routerName)
	{
		$link = '';
		$explodedAlias = explode('.', $typeAlias);
		$explodedRouter = explode('::', $routerName);
		if (file_exists($routerFile = JPATH_BASE . '/components/' . $explodedAlias[0] . '/helpers/route.php'))
		{
			JLoader::register($explodedRouter[0], $routerFile);
			$routerClass = $explodedRouter[0];
			$routerMethod = $explodedRouter[1];
			if (class_exists($routerClass) && method_exists($routerClass, $routerMethod))
			{
				if ($routerMethod == 'getCategoryRoute')
				{
					$link = $routerClass::$routerMethod($contentItemId, $language);
				}
				else
				{
					$link = $routerClass::$routerMethod($contentItemId . ':' . $contentItemAlias, $contentCatId, $language);
				}
			}
		}
		if ($link == '')
		{
			// create a fallback link in case we can't find the component router
			$router = new JHelperRoute;
			$link = $router->getRoute($contentItemId, $typeAlias, $link, $language, $contentCatId);
		}
		return $link;
	}

	/**
	 * Tries to load the router for the component and calls it. Otherwise calls getRoute.
	 *
	 * @param   integer  $id  The ID of the tag
	 *
	 * @return  string  URL link to pass to JRoute
	 *
	 * @since   3.1
	 */
	public static function getTagRoute($id) {

		$needles = array(
				'tag'  => array((int) $id)
		);
		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			if (!empty($needles) && $item = self::_findItem($needles))
			{
				$link = 'index.php?Itemid=' . $item;
			}
			else
			{
				// Create the link
				$link = 'index.php?option=com_playjoom&view=tag&id=' . $id;
			}
		}

		return $link;
	}
}
