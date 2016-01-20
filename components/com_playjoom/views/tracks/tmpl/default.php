<?php
/**
 * Contains the default template for the artist output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Site
 * @subpackage views.tracks.tmpl
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
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=tracks').'" method="post" name="adminForm" id="adminForm">';
    
//Page Title configuration
if ($this->params->get('show_page_heading') == 1) {

	echo '<div class="item-page'.$this->params->get('pageclass_sfx').'">';

	if (!$this->escape($this->params->get('page_heading'))) {
		echo '<h3 class="subheader">'.ucfirst(JRequest::getVar('view')).' | '.JText::_('COM_PLAYJOOM_HEADER_TITEL_TOTAL').' '.$this->pagination->total.'</h3>';
	}
	else {
		echo '<h3 class="subheader">'.$this->escape($this->params->get('page_heading')).' | '.JText::_('COM_PLAYJOOM_HEADER_TITEL_TOTAL').' '.$this->pagination->total.'</h3>';
	}

	echo '</div>';
}
    echo $this->loadTemplate('filter');    
    
    echo '<table width="100%">';
        echo '<thead>'.$this->loadTemplate('head').'</thead>';
        echo '<tbody>'.$this->loadTemplate('body').'</tbody>';
        echo '<tfoot>'.$this->loadTemplate('foot').'</tfoot>';
    echo '</table>';
    
    echo '<input type="hidden" name="task" value="" />';
    echo '<input type="hidden" name="boxchecked" value="0" />';
    echo '<input type="hidden" name="filter_order" value="'.$listOrder.'" />';
    echo '<input type="hidden" name="filter_order_Dir" value="'.$listDirn.'" />';
    echo JHtml::_('form.token');  

echo '</form>';