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
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/adminplaylist/tmpl/default_body.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$app = JFactory::getApplication();
$user = JFactory::getUser();

JPluginHelper::importPlugin('playjoom');
$dispatcher	= JDispatcher::getInstance();

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= & JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');
}

$length_counter = null;
$track_counter = null;

foreach($this->items as $i => $item) {

	//Check the right user for this following list items are logged in
	if ($item->user_id != $user->get('id')) {

		// Perform the log in.
		$error = $app->logout();

		// Check if the log out succeeded.
		if (!JError::isError($error)) {
			// Get the return url from the request and validate that it is internal.
			$return = JRequest::getVar('return', '', 'method', 'base64');
			$return = base64_decode($return);
			if (!JURI::isInternal($return)) {
				$return = '';
			}

			// Redirect the user.
			$app->redirect(JRoute::_($return, false));
		} else {
			$app->redirect(JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($uri), false));
		}
	}

	//Create PJ links
	$artiststing = base64_encode($item->artist);
	$albumsting = base64_encode($item->album);

	$album_link = PlayJoomHelperRoute::getPJlink('albums', '&album='.$albumsting.'&artist='.$artiststing);
	$artist_link = PlayJoomHelperRoute::getPJlink('artists','&artist='.$artiststing);

	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		echo '<li><a href="index.php?option=com_playjoom&view=broadcast&id='.$item->track_id.'" title="Continue to play this title" target="_blank" class="direct_link">'.$item->title.'</a>&nbsp;<span class="trackminutes">['.PlayJoomHelper::Playtime($item->length).']</span><img src="components/com_playjoom/images/icons/application_form_delete.png" alt="delete icon" width="16px" height="16px" style="margin-right:3px;margin-top:2px;vertical-align:bottom;" /><a href="index.php?option=com_playjoom&view=adminplaylist&layout=deltrack&id='.$item->track_id.'&listid='.JRequest::getVar('listid').'&Itemid='.JRequest::getVar('Itemid').'" title="Continue to delete this track from the playlist">Delete</a></li>';
	    echo '<li class="artistalbumline">'.JText::_('COM_PLAYJOOM_PLAYJOOM_HEADING_ARTIST').': <a href="'.$artist_link.'" title="Continue to the artist view">'.$item->artist.'</a>, '.JText::_('COM_PLAYJOOM_PLAYJOOM_HEADING_ALBUM').': <a href="'.$album_link.'" title="Continue to the album view">'.$item->album.'</a></li>';
	    echo '<li class="addline">'.JText::_('COM_PLAYJOOM_PLAYJOOM_HEADING_CREATE').' '.$item->add_date.'</li>';
	    echo '<li class="divider"></li>';

	    $length_counter = $length_counter + $item->length;
	    $track_counter = $track_counter +1;
    }
    else {
		echo '<li>'.$item->track_id.' '.JText::_('COM_PLAYJOOM_PLAYLIST_NOT_AVAILABLE').', '.$item->title.'<br><img src="components/com_playjoom/images/icons/application_form_delete.png" alt="delete icon" width="16px" height="16px" style="margin-right:3px;margin-top:2px;vertical-align:bottom;" /><a href="index.php?option=com_playjoom&view=adminplaylist&layout=deltrack&id='.$item->track_id.'&listid='.JRequest::getVar('listid').'&Itemid='.JRequest::getVar('Itemid').'" title="Continue to delete this track from the playlist">Delete</a></li>';
		echo '<li class="divider"></li>';
    }
}
echo '<li class="divider"></li>';
echo '<li>'.JText::_('COM_PLAYJOOM_PLAYLIST_TOTAL').'<br />'. $track_counter .' '.JText::_('COM_PLAYJOOM_PLAYLIST_TRACKS').' - '.PlayJoomHelper::Playtime($length_counter).' '.JText::_('COM_PLAYJOOM_PLAYLIST_MINUTES').'</li>';