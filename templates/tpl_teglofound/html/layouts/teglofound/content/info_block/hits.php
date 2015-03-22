<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

echo '<div class="hits">';
	echo '<i class="fa fa-eye fa-1x"></i> ';
	echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $displayData['item']->hits);
echo '</div>';