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
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/adminplaylists/tmpl/default_body.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');
}

foreach($this->items as $i => $item) {
	
	echo '<tr class="row'.$i % 2 .'">';
        echo '<td>'.$item->id.'</td>';
        echo '<td><a href="index.php?option=com_playjoom&view=adminplaylist&listid='.$item->id.'&Itemid='.JRequest::getVar('Itemid').'" title="Continue to edit the playlist">'.
                  $item->name.'</a>
                  <br />
                  <img src="components/com_playjoom/images/icons/application_form_edit.png" alt="edit icon" width="16px" height="16px" style="margin-right:3px;margin-top:2px;vertical-align:bottom;" />&nbsp;<a href="index.php?option=com_playjoom&view=adminplaylists&layout=editlist&id='.$item->id.'&Itemid='.JRequest::getVar('Itemid').'" title="Continue to change the name of this playlist">'.JText::_('COM_PLAYJOOM_PLAYLIST_CHANGE').'</a>&nbsp;|&nbsp;<img src="components/com_playjoom/images/icons/application_form_delete.png" alt="edit icon" width="16px" height="16px" style="margin-right:3px;margin-top:2px;vertical-align:bottom;" />&nbsp;<a href="index.php?option=com_playjoom&view=adminplaylists&layout=dellist&id='.$item->id.'&Itemid='.JRequest::getVar('Itemid').'" title="Continue to delete this playlist">'.JText::_('COM_PLAYJOOM_PLAYLIST_DELETE').'</a>';
        echo '</td>';
        echo '<td>'.$item->category_title.'</td>';
        echo '<td>'.$item->access_title.'</td>';
        echo '<td>'.JHtml::_('date', $item->create_date, JText::_('DATE_FORMAT_LC2')).'</td>';
        echo '<td>'.PlayJoomHelper::getPlaylistEntries($item->id).'</td>';
    echo '</tr>';
}