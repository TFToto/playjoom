<?php
/**
 * @package     Teglofound.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$author = $displayData['item']->author;
$author = ($displayData['item']->created_by_alias ? $displayData['item']->created_by_alias : $author);

echo '<div class="createdby">';
	echo '<i class="fa fa-user fa-1x"></i> ';
	
	if (!empty($displayData['item']->contactid ) && $displayData['params']->get('link_author') == true) {	
		echo JText::sprintf('COM_CONTENT_WRITTEN_BY',JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$displayData['item']->contactid), $author));
	} else {
		echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author);
	}
echo '</div>';