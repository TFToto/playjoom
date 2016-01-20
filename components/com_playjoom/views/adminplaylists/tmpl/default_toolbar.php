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
	$document	= JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/toolbar.css');
}

$user = JFactory::getUser();

echo '<div class="toolbar-box">';			    
    echo '<div class="toolbar-list" id="toolbar">';
        echo '<ul>';
            echo '<li class="toolbar-button" id="toolbar-new"><a href="index.php?option=com_playjoom&view=adminplaylists&layout=addlist&Itemid='.JRequest::getVar('Itemid').'>"><span class="icon-32-new"></span>'.JText::_('COM_PLAYJOOM_PLAYLISTS_NEW').'</a></li>';
        echo '</ul>';
    echo '</div>';             
    
    echo '<div class="pagetitle icon-48-playjoom"><h2>'.JText::_('COM_PLAYJOOM_PLAYLISTS').'</h2>'.JText::_('COM_PLAYJOOM_PLAYLISTS_FOR').' '.$user->get('username').'</div>';
echo '</div>';                                    		    