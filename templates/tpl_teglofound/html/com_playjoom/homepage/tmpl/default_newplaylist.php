<?php
/**
 * @package Joomla 3.0
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
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
 * @date $Date: 2013-03-17 13:00:06 +0100 (So, 17 Mrz 2013) $
 * @revision $Revision: 759 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/homepage/tmpl/default_newplaylist.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo '<h4>'.JText::_('COM_PLAYJOOM_HOMEPAGE_NEWPLAYLISTS').'</h4>';

echo '<ul class="circle">';
foreach($this->PlaylistItems as $i => $item) {
	
	$PlaylistEntries = PlayJoomHelper::getPlaylistEntries($item->id);
	
	if ($PlaylistEntries != 0) {
		
		//Check for category item
	    if ($item->category_title) {
	    	$categoryItem = ' ('.$item->category_title.')';
	    } else {
		    $categoryItem = null;	
	    }
	    
	    //create links for playlist
	    $link = 'index.php?option=com_playjoom&view=playlist&source=playlist&listid='.$item->id.'&name='.base64_encode($item->name).'&disposition=inline';
	    $linkwithorder = 'index.php?option=com_playjoom&view=playlist&source=playlist&listid='.$item->id.'&orderplaylist=RAND()&disposition=inline';
		
	    echo '<li><a href="index.php?option=com_playjoom&view=adminplaylist&listid='.$item->id.'&Itemid='.JRequest::getVar('Itemid').'" title="Continue to edit the playlist">'.
                  $item->name.'</a> '.
                  $categoryItem.
                  ' ['.$PlaylistEntries.']'.
                  ' <a href="'.$link.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="tiny button secondary" target="_blank">'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_ALL').'</a>'.
                  ' <a href="'.$linkwithorder.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="tiny button secondary" target="_blank">'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_RAND').'</a>'.
	
	         '</li>';
     }
}
echo '<ul>';