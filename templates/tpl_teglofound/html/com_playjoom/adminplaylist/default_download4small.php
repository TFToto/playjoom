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
 * @date $Date: 2013-02-09 13:58:51 +0100 (Sa, 09 Feb 2013) $
 * @revision $Revision: 721 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/album/tmpl/default_playlist.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$albumsting = JRequest::getVar('album');
$artiststing = JRequest::getVar('artist');
$playlist_id = JRequest::getVar('listid');

$link = 'index.php?option=com_playjoom&view=download&source=playlist&listid='.$playlist_id.'&name='.base64_encode($this->playlistinfo->name);

echo '<fieldset class="batch">';

	echo '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_DOWNLOAD').'</legend>';
	
	echo '<div class="directplay">';
	    echo '<a href="'.$link.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="button" target="_blank">'.JText::_('COM_PLAYJOOM_PLAYLIST_DOWNLOAD_ALBUM').'</a>';
	echo '</div>';

echo '</fieldset>';