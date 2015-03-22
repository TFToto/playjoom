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
 * @date $Date: 2012-12-25 17:41:19 +0100 (Di, 25 Dez 2012) $
 * @revision $Revision: 644 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/adminplaylists/tmpl/addlist.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//Get user objects
$user = JFactory::getUser();;    
   	
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JLoader::import( 'helpers.adminplaylists', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=adminplaylists&action=add').'" method="post" name="adminForm" id="adminForm">';
	
    echo '<fieldset class="addtrack">';
		 echo $this->loadTemplate('toolbar');
		 echo '<div class="adminplaylist">';			
			 echo JText::_('COM_PLAYJOOM_ADDPLAYLIST_FORM').'<br />';
			 echo '<input type="text" size="50" class="inputbox" name="new_playlist_name" />';
		 echo '</div>';
		 
		 echo '<div class="adminplaylist">';
		     echo JText::_('COM_PLAYJOOM_EDITPLAYLIST_FORM_CAT').'<br />';
		     echo '<select name="category_id" class="inputbox">';
		         echo '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_CATEGORY').'</option>';
		         echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('catlist'), 'value', 'text', null);
		     echo '</select>';
		 echo '</div>';
		 
		 echo '<div class="adminplaylist">';
		     echo JText::_('COM_PLAYJOOM_EDITPLAYLIST_FORM_LEVEL').'<br />';
		     echo '<select name="access" class="inputbox">';
		         echo '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_ACCESSLEVEL').'</option>';
		         echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('accesslevel'), 'value', 'text', null);
		     echo '</select>';
		 echo '</div>';
		 
		 echo '<div class="adminplaylist">';
		     echo JText::_('COM_PLAYJOOM_EDITPLAYLIST_FORM_ATTACH').'<br />';
		     echo '<select name="attach_artist" class="inputbox">';
		         echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
		         echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('artist'), 'value', 'text', null);
		     echo '</select>';
		     echo '<select name="attach_genre" class="inputbox">';
		         echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
		         echo JHtml::_('select.options', PlayJoomAdminplaylistsHelper::getOptions('genre'), 'value', 'text', null);
		     echo '</select>';
		 echo '</div>';
		 
	echo '</fieldset>';
	
	echo '<input type="hidden" name="task" value="" />';
    echo '<input type="hidden" name="user_id" value="'.$user->get('id').'" />';
    echo JHtml::_('form.token');

echo '</form>';