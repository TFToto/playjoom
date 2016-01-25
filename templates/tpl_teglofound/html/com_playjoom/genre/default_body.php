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
	$document	= JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');
}

$counter = null;

$numbers_of_title_genre =  $this->params->get('numbers_of_titles_genre', 8);
$running_time_genre =  $this->params->get('show_running_time_genre', 0);
$hits_genre =  $this->params->get('show_hits_genre', 0);

foreach($this->items as $i => $item) {

	//Check for albumname as sampler
	$SamplerCheck = PlayJoomHelper::checkForSampler($item->album, $item->artist);

	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		//Get Album thumbnail
		if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
			$cover = new PlayJoomHelperCover();
			$coverthumb = $cover->getCoverHTMLTag($item, $SamplerCheck);
		}

		$albumsting = base64_encode($item->album);
		$artiststing = base64_encode($item->artist);

		if (isset($this->genre->title)) {
			$cat_string = '&cat='.base64_encode($this->genre->title);
		} else {
			$cat_string = null;
		}

		$albumlink = JRoute::_('index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').$cat_string.'&catid='.JRequest::getVar('catid'));
		$artistlink = JRoute::_('index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').$cat_string.'&catid='.JRequest::getVar('catid'));

		//Prepare for artist information
		//Check for albumname as sampler
		$checkResult = PlayJoomHelper::checkForSampler($item->album, $item->artist);

	    if ($checkResult)
	    {
		    $artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
	    }
	    else
	    {
		    $artistname = $item->artist;
        }

		$counter ++;

		echo '<tr class="row'. $i % 2 .'">';
		     echo '<td valign="top">'.$counter .'</td>';
		     echo '<td valign="top"><a href="'.$artistlink.'">'.$artistname.'</a></td>';
		     echo '<td valign="top"><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.$item->album.'</a></td>';

		     if ($numbers_of_title_genre >= 1)
		     {
		     	 echo '<td valign="top">';
		         //list of titles
		         $album_item = PlayJoomModelGenre::getAlbumItems($item->album);

		         echo '<ul class="circle">';
		         foreach($album_item as $j => $item_entrie)
		         {
		         	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		         		if ($j < $numbers_of_title_genre)
		                {
		                    //Check for displaying the running time
		                	if ($running_time_genre == 1)
                            {
        	                    $running_time = ' ['.PlayJoomHelper::Playtime($item_entrie->length).'&nbsp;'.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').']';
                            }
                            else
                            {
                            	$running_time = null;
                            }
		                    //Check for displaying the hits
		                	if ($hits_genre == 1)
                            {
        	                    $hits = ' ['.$item_entrie->hits.']';
                            }
                            else
                            {
                            	$hits = null;
                            }
                            //Plugins integration
                            $this->events = new stdClass();
                            $results = $dispatcher->trigger('onPrepareTrackLink', array(&$item_entrie, $this->params));
	                        $this->events->PrepareTrackLink = trim(implode("\n", $results));

	                        $results = $dispatcher->trigger('onBeforeTrackLink', array(&$item_entrie, $this->params));
	                        $this->events->BeforeTrackLink = trim(implode("\n", $results));

	                        $results = $dispatcher->trigger('onAfterTrackLink', array(&$item_entrie, $this->params));
	                        $this->events->AfterTrackLink = trim(implode("\n", $results));

		                	echo '<li>'.$this->events->BeforeTrackLink.
		                	            $this->events->PrepareTrackLink.'&nbsp;<span class="direct_link">'.$running_time.'</span>'.
		                	            $this->events->AfterTrackLink.
		                	     '</li>';
		                }
		         	}
		         	else
		            {
		            	$j = $j -1;
		            }
		         }
		         echo '</ul>';
		         echo '</td>';
		     }

		     echo '<td valign="top">'.$item->year.'</td>';
		echo '</tr>';
	}
}