<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_categories
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_playjoom/helpers/route.php';

/**
 * Helper for mod_articles_categories
 *
 * @package     Joomla.Site
 * @subpackage  mod_articles_categories
 *
 * @since       1.5
 */
abstract class ModPJCategoriesHelper {
	/**
	 * Get list of articles
	 *
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return  array
	 *
	 * @since   1.5
	 */
	public static function getList(&$params) {
		
		$options['countItems'] = $params->get('numitems', 0);
		$options['extension'] = $params->get('cat_extension', 'system');
		
		//Matching table to extension
		switch ($params->get('cat_extension', 'system')) {
			case 'com_playjoom':
				$options['table'] = '#__jpaudiotracks';
				$options['field'] = 'catid';
				break;
			case 'com_playjoom.playlist':
				$options['table'] = '#__jpplaylists';
				$options['field'] = 'catid';
				break;
			case 'com_playjoom.trackfilter':
				$options['table'] = '#__jpaudiotracks';
				$options['field'] = 'trackfilterid';
				break;
			case 'com_banners':
				$options['table'] = '#__banners';
				$options['field'] = 'catid';
				break;
			case 'com_contact':
				$options['table'] = '#__banners';
				$options['field'] = 'catid';
				break;
			case 'com_content':
				$options['table'] = '#__content';
				break;
			case 'com_newsfeeds':
				$options['table'] = '#__newsfeeds';
				$options['field'] = 'catid';
				break;
			case 'com_users.notes':
				$options['table'] = '#__user_notes';
				$options['field'] = 'catid';
				break;
			default:
				$options['table'] = '#__jpaudiotracks';
				$options['field'] = 'catid';
			break;
		}

		$categories = JCategories::getInstance('Playjoom', $options);
		$category   = $categories->get($params->get('parent', 'root'));

		if ($category != null) {
			$items = $category->getChildren();

			if ($params->get('count', 0) > 0 && count($items) > $params->get('count', 0)) {
				$items = array_slice($items, 0, $params->get('count', 0));
			}

			return $items;
		}
	}
}
