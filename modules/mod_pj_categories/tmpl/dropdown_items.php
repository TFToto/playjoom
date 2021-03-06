<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_categories
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$class_li = null;
$num = null;

foreach ($list as $item) {
	
	$levelup = $item->level - $startLevel - 1;

	if ($item->numitems !=0 || $params->get('show_empty_item')) {

		if ($_SERVER['REQUEST_URI'] == JRoute::_(PlayJoomHelperRoute::getCategoryRoute($item->id))) {
			$class_li = 'class="active"';
		} else {
			$class_li = 'class="menuitem"';
		}

		if ($params->get('numitems')) {
			$num = ' ('.$item->numitems.')';
		}

		if ($params->get('show_children', 0) && (($params->get('maxlevel', 0) == 0) && count($item->getChildren()) >= 1)) {
			$class_li = 'class="has-dropdown menuitem"';
		}

		$level = (int)$params->get('item_heading') + $levelup;
		echo '<li '.$class_li.'>';
			echo '<a href="'.JRoute::_(PlayJoomHelperRoute::getCategoryRoute($item->id).'&amp;Itemid=0&amp;'.$query_itmes).'">'.$item->title.$num.'</a>';

			if ($params->get('show_description', 0)) {
				echo JHtml::_('content.prepare', $item->description, $item->getParams(), 'mod_pj_categories.content');
			}
			
			if ($params->get('show_children', 0) && (($params->get('maxlevel', 0) == 0)
			 || ($params->get('maxlevel') >= ($item->level - $startLevel))) && count($item->getChildren())) {
				
				echo '<ul class="dropdown">';
					$temp = $list;
					$list = $item->getChildren();
					require JModuleHelper::getLayoutPath('mod_pj_categories', $params->get('layout', 'default') . '_items');
					$list = $temp;
				echo '</ul>';
			}
		echo '</li>';
	}
}