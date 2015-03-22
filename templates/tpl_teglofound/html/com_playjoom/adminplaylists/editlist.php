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
 * @date $Date: 2013-05-13 21:31:31 +0200 (Mo, 13 Mai 2013) $
 * @revision $Revision: 780 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/adminplaylists/tmpl/editlist.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
   	
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JLoader::import( 'helpers.adminplaylists', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=adminplaylists&action=edit').'>" method="post" name="adminForm" id="adminForm">';
	echo '<fieldset class="addtrack">';
		echo $this->loadTemplate('toolbar');
		echo '<div class="adminplaylist">';			
			echo JText::_('COM_PLAYJOOM_EDITPLAYLIST_FORM').'<br />';
			echo '<input type="text" size="50" class="inputbox" name="new_playlist_name" value="'.$this->playlistinfo->name.'"/>';
		echo '</div>';
		
		echo '<div class="adminplaylist">';	
		    echo JText::_('COM_PLAYJOOM_EDITPLAYLIST_FORM_CAT').'<br />';
		    echo '<select name="category_id" class="inputbox">';
		        echo '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_CATEGORY').'</option>';
		        echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('catlist'), 'value', 'text', $this->playlistinfo->catid);
		    echo '</select>';
		echo '</div>';
		
		echo '<div class="adminplaylist">';
		    echo JText::_('COM_PLAYJOOM_EDITPLAYLIST_FORM_LEVEL').'<br />';
		    echo '<select name="access" class="inputbox">';
		        echo '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_ACCESSLEVEL').'</option>';
		        echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('accesslevel'), 'value', 'text', $this->playlistinfo->access);
		    echo '</select>';
		echo '</div>';
		
		echo '<div class="adminplaylist">';
		    echo JText::_('COM_PLAYJOOM_EDITPLAYLIST_FORM_ATTACH').'<br />';
		    echo '<select name="attach_artist" class="inputbox">';
		        echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
		        echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('artist'), 'value', 'text', $this->playlistinfo->attach_artist);
		    echo '</select>';
		    echo '<select name="attach_genre" class="inputbox">';
		        echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_GENRE').'</option>';
		        echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('genre'), 'value', 'text', $this->playlistinfo->attach_genre);
		    echo '</select>';
		echo '</div>';
		
	echo '</fieldset>';
	

		echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="list_id" class="inputbox" value="'.$this->playlistinfo->id.'" />';
		echo JHtml::_('form.token');

echo '</form>';