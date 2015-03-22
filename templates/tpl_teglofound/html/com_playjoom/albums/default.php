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
 * @date $Date: 2011-04-16 11:08:33 +0200 (Sa, 16. Apr 2011) $
 * @revision $Revision: 145 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/sections/tmpl/default.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=albums').'" method="post" name="adminForm" id="adminForm">';
    
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
    
    echo '<div class="hide-for-small">';
    	echo $this->loadTemplate('filter');
    	echo '<table width="100%">';
        	echo '<thead>'.$this->loadTemplate('head').'</thead>';
        	echo '<tbody>'.$this->loadTemplate('body').'</tbody>';
        	echo '<tfoot>'.$this->loadTemplate('foot').'</tfoot>';
    	echo '</table>';
    echo '</div>';
    
    echo '<div class="show-for-small">';
    	echo '<ul class="side-nav-4small">';
    		echo $this->loadTemplate('listitems');
    	echo '</ul>';
    echo '</div>';
    
    echo '<input type="hidden" name="task" value="" />';
    echo '<input type="hidden" name="boxchecked" value="0" />';
    echo '<input type="hidden" name="filter_order" value="'.$this->state->get('list.ordering').'" />';
    echo '<input type="hidden" name="filter_order_Dir" value="'.$this->state->get('list.direction').'" />';
    echo JHtml::_('form.token');  

echo '</form>';