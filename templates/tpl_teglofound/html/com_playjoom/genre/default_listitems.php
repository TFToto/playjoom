<?php
/**
 * Contains the default body template for the genre output.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Site
 * @subpackage views.genre.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/genre/tmpl/default_body.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

$dispatcher	= JDispatcher::getInstance();

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= & JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');
}

$counter = null;

//$coverwidth_genre =  $this->params->get('maxsize_cover_genre');
//$coverstate_genre =  $this->params->get('show_cover_genre');
$numbers_of_title_genre =  $this->params->get('numbers_of_titles_genre', 8);
$running_time_genre =  $this->params->get('show_running_time_genre', 0);
$hits_genre =  $this->params->get('show_hits_genre', 0);

foreach($this->items as $i => $item) {

	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		$albumsting = base64_encode($item->album);
		$artiststing = base64_encode($item->artist);

		$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').'&cat='.base64_encode($this->genre->title).'&catid='.JRequest::getVar('catid');
		$artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').'&cat='.base64_encode($this->genre->title).'&catid='.JRequest::getVar('catid');

		//Prepare for artist information
		//Check for albumname as sampler
		$checkResult = PlayJoomHelper::checkForSampler($item->album, $item->artist);

	    if ($checkResult) {
		    $artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
	    } else {
		    $artistname = $item->artist;
        }

		$counter ++;

		echo '<li valign="top">'.sprintf ("%02d", $counter) .' <a href="'.$artistlink.'">'.$artistname.'</a> - <a href="'.$albumlink.'" title="Continue to the album view">'.$item->album.'</a> ('.$item->year.')<br />';

		if ($numbers_of_title_genre >= 1) {

			//list of titles
		    $album_item = PlayJoomModelGenre::getAlbumItems($item->album);

		    foreach($album_item as $j => $item_entrie) {

		    	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		    		if ($j < $numbers_of_title_genre) {

		    			//Check for displaying the running time
		                if ($running_time_genre == 1) {
		                	$running_time = ' ['.PlayJoomHelper::Playtime($item_entrie->length).'&nbsp;'.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').']';
                        } else {
                        	$running_time = null;
                        }
		                //Check for displaying the hits
		                if ($hits_genre == 1) {
        	                $hits = ' ['.$item_entrie->hits.']';
                        } else {
                            $hits = null;
                        }
                        //Plugins integration
                        $this->events = new stdClass();
                        $results = $dispatcher->trigger('onPrepareTrackLink', array(&$item_entrie, $this->params));
	                    $this->events->PrepareTrackLink = trim(implode("\n", $results));

		                echo '<span class="trackline">'.$this->events->PrepareTrackLink.'&nbsp;<span class="trackline_time">'.$running_time.'</span></span>';
		            }
		        } else {
		        	$j = $j -1;
		        }
		    }
		}

		echo '</li><li class="divider"></li>';
	}
}