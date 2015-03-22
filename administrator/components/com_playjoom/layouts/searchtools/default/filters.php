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
 * @date $Date: 2014-03-13 14:27:32 +0100 (Do, 13 Mrz 2014) $
 * @revision $Revision: 913 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/layouts/playjoom/searchtools/default/filters.php $
 */

defined('JPATH_BASE') or die;

$data = $displayData;
$user = JFactory::getUser();

// Load the form filters
$filters = $data['view']->filterForm->getGroup('filtertools');
$adminfilters = $data['view']->filterForm->getGroup('adminfiltertools');

if ($filters) {
	foreach ($filters as $fieldName => $field) {
		if ($fieldName != 'filter_search') {
			echo '<div class="js-stools-field-filter">';
				echo $field->input;
			echo '</div>';
		}
	}
}
if ($adminfilters && JAccess::check($user->get('id'), 'core.admin') == 1) {
	foreach ($adminfilters as $fieldName => $field) {
		echo '<div class="js-stools-field-filter">';
			echo $field->input;
		echo '</div>';
	}
}