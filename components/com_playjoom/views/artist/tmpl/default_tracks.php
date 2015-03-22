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
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';
$dir_case   = JRequest::getVar( 'dir' );
$dirName   = $this->state->get('list.dirName');

echo '<form action="'. JRoute::_('index.php?option=com_playjoom&view=artist&artist='.JRequest::getVar('artist')).'" method="post" name="adminForm" id="adminForm">';
    echo '<table class="adminlist">';
            echo $this->loadTemplate('filter');
            echo '<thead>'.$this->loadTemplate('head').'</thead>';
            echo '<tbody>'.$this->loadTemplate('body').'</tbody>';
            echo '<tfoot>'.$this->loadTemplate('foot').'</tfoot>';
    echo '</table>';
    echo '<div>';
        echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="boxchecked" value="0" />';
        echo '<input type="hidden" name="filter_order" value="'.$listOrder.'" />';
		echo '<input type="hidden" name="filter_order_Dir" value="'.$listDirn.'" />';
        echo JHtml::_('form.token'); 
    echo '</div>';
echo '</form>';