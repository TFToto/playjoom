<?php
/**
 * @package Joomla 1.6.x
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-09-08 14:20:12 +0200 (So, 08 Sep 2013) $
 * @revision $Revision: 829 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/genre/tmpl/default.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

//Get plugin contents
$item_onBeforePJContent = $this->events->onBeforePJContent;
$item_onAfterPJContent  = $this->events->onAfterPJContent;

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';
$dir_case   = JRequest::getVar( 'dir' );
$dirName   = $this->state->get('list.dirName');

if (isset($this->genre->title)) {
	echo '<h3 class="subheader">'.$this->genre->title.'</h3>';
}

echo $item_onBeforePJContent;

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=genre&catid='.JRequest::getVar('catid').'&Itemid='.JRequest::getVar('Itemid')).'" method="post" name="adminForm" id="adminForm">';

	echo '<div class="hide-for-small">';
		echo $this->loadTemplate('filter');
		echo '<table width="100%">';
        	echo '<thead>'.$this->loadTemplate('head').'</thead>';
        	echo '<tfoot>'.$this->loadTemplate('foot').'</tfoot>';
        	echo '<tbody>'.$this->loadTemplate('body').'</tbody>';
    	echo '</table>';
    echo '</div>';

    echo '<div class="show-for-small">';
    	echo '<ul class="side-nav-4small">';
    		echo $this->loadTemplate('listitems');
    	echo '</ul>';
    echo '</div>';

    echo '<div>';
      	echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="boxchecked" value="0" />';
        echo '<input type="hidden" name="filter_order" value="'.$listOrder.'" />';
		echo '<input type="hidden" name="filter_order_Dir" value="'.$listDirn.'" />';
        echo JHtml::_('form.token');
    echo '</div>';
echo '</form>';

/*
echo '<div class="show-for-small">';
	echo $this->loadTemplate('playlist4small');
echo '</div>';
echo '<div class="hide-for-small">';
echo $this->loadTemplate('playlist');
echo '</div>';
*/
echo $item_onAfterPJContent;