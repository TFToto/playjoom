<?php
/**
 * Contains the default layouts methods for to get the filters in PlayJoom backend.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Layout
 * @subpackage layout.playjoom.searchtools
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2014 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('JPATH_BASE') or die;

$data = $displayData;

// Receive overridable options
$data['options'] = !empty($data['options']) ? $data['options'] : array();

if (is_array($data['options']))
{
	$data['options'] = new JRegistry($data['options']);
}

// Options
$filterButton = $data['options']->get('filterButton', true);
$searchButton = $data['options']->get('searchButton', true);
$filters = $data['view']->filterForm->getGroup('filter');
$filters_tools = $data['view']->filterForm->getGroup('filtertools');

if (!empty($filters['filter_search'])) {

	if ($searchButton) {
		echo '<label for="filter_search" class="element-invisible">';
			echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');
		echo '</label>';
		echo '<div class="btn-wrapper input-append">';
			echo $filters['filter_search']->input;
			echo '<button type="submit" class="btn hasTooltip" title="'.JHtml::tooltipText('JSEARCH_FILTER_SUBMIT').'">';
				echo '<i class="icon-search"></i>';
			echo '</button>';
		echo '</div>';

		if ($filterButton) {
			if ($filters_tools) {
				echo '<div class="btn-wrapper hidden-phone">';
					echo '<button type="button" class="btn hasTooltip js-stools-btn-filter" title="'.JHtml::tooltipText('JSEARCH_TOOLS_DESC').'">';
						echo JText::_('JSEARCH_TOOLS').' <i class="caret"></i>';
					echo '</button>';
				echo '</div>';
			}
		}

		echo '<div class="btn-wrapper">';
			echo '<button type="button" class="btn hasTooltip js-stools-btn-clear" title="'.JHtml::tooltipText('JSEARCH_FILTER_CLEAR').'">';
				echo JText::_('JSEARCH_FILTER_CLEAR');
			echo '</button>';
		echo '</div>';
	}
}