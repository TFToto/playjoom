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
 * @date $Date: 2013-05-13 21:31:31 +0200 (Mo, 13 Mai 2013) $
 * @revision $Revision: 780 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/adminplaylist/tmpl/default_playlist.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$playlist_id = JRequest::getVar('listid');

//create link for form
$link = 'index.php?option=com_playjoom&view=playlist&source=playlist&listid='.$playlist_id.'&name='.base64_encode($this->playlistinfo->name).'&disposition=inline';
$linkwithorder = 'index.php?option=com_playjoom&view=playlist&source=playlist&listid='.$playlist_id.'&orderplaylist=RAND()&disposition=inline';

echo '<fieldset class="batch">';

echo '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_PLAYLIST').'</legend>';

echo '<div class="directplay">';
echo '<a href="'.$link.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="small button" target="_blank">'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_ALL').'</a>';
echo '<a href="'.$linkwithorder.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="small button" target="_blank">'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_RAND').'</a>';
echo '</div>';

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=playlist').'" method="post" name="playlistForm" id="playlistForm">';
echo '<p>'.JText::_('COM_PLAYJOOM_PLAYLIST_DOWNLOAD_PLAYLIST').'</p>';

echo '<select name="attachment_playlist" class="PJ-filtermenu" id="playlisttype">';
echo '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_SELECT_TYPE').'</option>';
echo '<option value="m3u">M3U</option>';
echo '<option value="pls">PLS</option>';
echo '<option value="xspf">XSPF</option>';
echo '</select>';
 
echo '<select name="orderplaylist" class="PJ-filtermenu" id="ordertype" onchange="this.form.submit()">';
echo '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_SELECT_ORDER').'</option>';
echo '<option value="t.tracknumber,t.year">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_TRACKNO').'</option>';
echo '<option value="RAND()">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_RAND').'</option>';
echo '<option value="t.title">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_TITLE').'</option>';
echo '<option value="t.hits,t.year">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_HITS').'</option>';
echo '</select>';

echo '<input type="hidden" name="listid" value="'.$playlist_id.'" />';
echo '<input type="hidden" name="source" value="playlist" />';
echo '<input type="hidden" name="disposition" value="attachment" />';

echo '</form>';

echo '</fieldset>';