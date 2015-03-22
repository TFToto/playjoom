<?php
/**
 * Contains the default template for the list of audiotracks output.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage views.audiotracks.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';
$dirName   = $this->state->get('list.dirName');

?>
<script type="text/javascript">
Joomla.orderTable = function() {
	table = document.getElementById("sortTable");
	direction = document.getElementById("directionTable");
	order = table.options[table.selectedIndex].value;
	if (order != '<?php echo $listOrder; ?>') {
		dirn = 'asc';
	} else {
		dirn = direction.options[direction.selectedIndex].value;
	}
	Joomla.tableOrdering(order, dirn, '');
}
</script>

<?php

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=audiotracks').'" method="post" name="adminForm" id="adminForm">';

if (!empty( $this->sidebar)) {

	echo '<div id="j-sidebar-container" class="span2">';
		echo $this->sidebar;
	echo '</div>';
	echo '<div id="j-main-container" class="span10">';
} else {
	echo '<div id="j-main-container">';
}
	// Search tools bar
	// Instantiate a new JLayoutFile instance and render the layout
	$layout = new JLayoutFile('searchtools.default');
	echo $layout->render(array('view' => $this));

    echo '<table class="table table-striped" id="articleList">';
        echo '<thead>'.$this->loadTemplate('head').'</thead>';
        echo '<tfoot>'.$this->loadTemplate('foot').'</tfoot>';
        echo '<tbody>'.$this->loadTemplate('body').'</tbody>';
    echo '</table>';

    echo $this->loadTemplate('batch');

    echo '<div>';
        echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="boxchecked" value="0" />';
        echo JHtml::_('form.token');
    echo '</div>';
echo '</form>';