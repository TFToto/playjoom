<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

echo '<div class="items-more">';
	echo '<ul class="side-nav">';
	
	foreach ($this->link_items as &$item) {
		echo '<li>';
			echo '<a href="'.JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)).'">';
				echo $item->title;
			echo '</a>';
		echo '</li>';
		echo '<li class="divider"></li>';
	}
	echo '</ul>';
echo '</div>';