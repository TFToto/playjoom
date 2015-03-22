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
 * @date $Date: 2011-03-19 12:02:23 +0100 (Sa, 19 Mrz 2011) $
 * @revision $Revision: 117 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/adminplaylist/tmpl/deltrack.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

//get Playlist infos
$track_content = PlayJoomHelper::getTrackInfo(JRequest::getVar('id'));

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=adminplaylist&action=del&listid='.JRequest::getVar('listid').'&Itemid='.JRequest::getVar('Itemid')).'" method="post" name="adminForm" id="adminForm">';
   echo '<fieldset class="addtrack">';
	  echo $this->loadTemplate('toolbar');
		 echo '<div class="filter-select fltrt">';

		 if (JFile::exists($track_content->pathatlocal.DIRECTORY_SEPARATOR.$track_content->file)) {
		 	echo '<ul class="playlist">';
		       echo '<li id="playlist_info">'.JText::_('COM_PLAYJOOM_PLAYJOOM_HEADING_TITLE').':&nbsp;'.$track_content->title.'</li>';
		       echo '<li id="playlist_info">'.JText::_('COM_PLAYJOOM_PLAYJOOM_HEADING_ALBUM').':&nbsp;'.$track_content->album.'</li>';
		       echo '<li id="playlist_info">'.JText::_('COM_PLAYJOOM_PLAYJOOM_HEADING_ARTIST').':&nbsp;'.$track_content->artist.'</li>';
		    echo '</ul>';
		 }

		 echo '</div>';
	echo '</fieldset>';
	echo '<div>';
		echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="track_id" value="'.JRequest::getVar('id').'" />';
		echo JHtml::_('form.token');
	echo '</div>';
echo '</form>';