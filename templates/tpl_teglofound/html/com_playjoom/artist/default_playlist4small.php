<?php
/**
 * Contains the default template for playlist output in the artist viewer.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package Joomla 1.6.x and PlayJoom 0.9.x
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2013-09-08 14:20:12 +0200 (So, 08 Sep 2013) $
 * @revision $Revision: 829 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/artist/tmpl/default_playlist.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$artiststing = JRequest::getVar('artist'); 
$link = 'index.php?option=com_playjoom&view=playlist&source=artist&orderplaylist=a.year,a.tracknumber&name='.$artiststing;
$linkwithorder = 'index.php?option=com_playjoom&view=playlist&source=artist&orderplaylist=RAND()&name='.$artiststing;

echo '<fieldset class="batch">';

	echo '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_PLAYLIST').'</legend>';
	
	echo '<div class="directplay">';
	    echo '<a href="'.$link.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="button" target="_blank">'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_ALL').'</a>';
	    echo '<a href="'.$linkwithorder.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="button" target="_blank">'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_RAND').'</a>';
	echo '</div>';
	

echo '</fieldset>';
if (!empty($this->plitems)) {
	
	echo '<fieldset class="batch">';

         echo '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_PLAYLIST_ATTACH').'</legend>';
         //Attachment lists
         if (!empty($this->plitems)) {	          
             echo '<ul class="circle">';
              
                 foreach($this->plitems as $i => $item) {
                    //create link for form
                    $PLlink = 'index.php?option=com_playjoom&view=playlist&source=playlist&listid='.$item->id.'&name='.base64_encode($item->name);
	                echo '<li><a href="'.$PLlink.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" target="_blank">'.$item->name.'</a></li>';
                 }  
             echo '</ul>';
         }
    echo '</fieldset>';
}