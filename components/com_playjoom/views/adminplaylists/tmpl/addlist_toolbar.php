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
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= & JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/toolbar.css');
}

//Load JavaScripts for light box
JHtml::_('behavior.modal'); 

echo '<div class="toolbar-box">';			    
	echo '<div class="toolbar-list" id="toolbar">';
        echo '<ul>';
            echo '<li class="toolbar-button" id="toolbar-save-new"><a href="#" onclick="javascript:submitbutton()" class="toolbar"><span class="icon-32-save-new"></span>'.JText::_('COM_PLAYJOOM_PLAYLISTS_SAVE_NEW').'</a></li>';
            echo '<li class="toolbar-button" id="toolbar-cancel"><a href="index.php?option=com_playjoom&view=adminplaylists&Itemid='.JRequest::getVar('Itemid').'" class="toolbar"><span class="icon-32-cancel"></span>'.JText::_('COM_PLAYJOOM_PLAYLISTS_CANCEL').'</a></li>';
        echo '</ul>';
    echo '</div>';             
           
    echo '<div class="pagetitle icon-48-playjoom">';
        echo '<h2>'.JText::_('COM_PLAYJOOM_ADDPLAYLIST_LABEL').'</h2><br/ >';
    echo '</div>';
echo '</div>';   