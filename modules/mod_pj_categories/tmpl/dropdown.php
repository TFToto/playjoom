<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_categories
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

echo '<ul class="nav menu">';
	echo '<li class="has-dropdown '.$moduleclass_sfx.'">';
		echo '<a class="dropdown-link" data-dropdown="drop1" href="'.JRoute::_('index.php?option=com_playjoom&amp;view=genres').'">
				<i class="'.$params->get('icon_class').'"></i>'.
				$module->title.
			'</a>';
			echo '<ul class="dropdown">';
			echo '<li class="menuitem"><a href="'.JRoute::_('index.php?option=com_playjoom&amp;view=genres&amp;Itemid=0&amp;'.$query_itmes).'">'.JText::_('MOD_PJ_CATEGORIES_ALL_CATS').'</a></li>';
				require JModuleHelper::getLayoutPath('mod_pj_categories', $params->get('layout', 'dropdown') . '_items');
			echo '</ul>';
	echo '</li>';
echo '</ul>';