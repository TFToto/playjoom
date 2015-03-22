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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('formbehavior.chosen', 'select');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$Cover = new PlayJoomHelperCover();

//In Albumbox plugins configurations
JPluginHelper::importPlugin('playjoom');
$dispatcher	= JDispatcher::getInstance();

$this->events = new stdClass;

echo '<div class="section-container auto" data-section>';

	//All track tabs
	echo '<section>';
		echo '<p class="title" data-section-title><a href="#panel0">'.JText::_('JALL').' ('.count($this->albumitems).')</a></p>';
		echo '<div class="content" data-section-content>';
			echo '<ul class="list_of_albums">';
				foreach($this->albumitems as $i => $item) {
					$albumsting = base64_encode($item->album);
					$artiststing = base64_encode($item->artist);
					$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').'&cat='.base64_encode($item->category_title).'&catid='.$item->catid;

					//Check for albumname as sampler
					$SamplerCheck = PlayJoomHelper::checkForSampler($item->album, $item->artist);

					//Get Album thumbnail
					if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
						$cover = new PlayJoomHelperCover();
						$coverthumb = $cover->getCoverHTMLTag($item, $SamplerCheck);
					}

	           		echo '<li class="genre_item"><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.$item->album.'</a> ('.$item->year.')</li>';
           		}
       		echo '</ul>';

       		$results = $dispatcher->trigger('onInAlbumbox', array(&$items, &$this->params, 'artist'));
       		echo trim(implode("\n", $results));

       		//echo $item_onInAlbumbox;
		echo '</div>';
	echo '</section>';

	//Trackfilter tabs
  	foreach($this->TrackFilter as $i => $filteritem) {

  		//Get filter items
  		$this->filteritems = PlayJoomModelArtist::getFilteritems($filteritem->id);

  		if(count($this->filteritems) > 0) {
  			echo '<section>';
  				echo '<p class="title" data-section-title><a href="#panel'.($i+1).'">'.$filteritem->title.' ('.count($this->filteritems).')</a></p>';
  				echo '<div class="content" data-section-content>';
  					//Get filter items
  					$this->filteritems = PlayJoomModelArtist::getFilteritems($filteritem->id);
  					if(count($this->filteritems) > 0) {
  						echo '<div class="content">';
  							echo '<ul class="list_of_albums">';
  								foreach($this->filteritems as $i => $item) {

  									$albumsting = base64_encode($item->album);
  									$artiststing = base64_encode($item->artist);
  									$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').'&cat='.base64_encode($item->category_title).'&catid='.$item->catid;

  									//Get Album thumbnail
									if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
										$cover = new PlayJoomHelperCover();
										$coverthumb = $cover->getCoverHTMLTag($item, $SamplerCheck);
									}
	           						echo '<li class="genre_item"><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.$item->album.'</a> ('.$item->year.')</li>';
  								}
  							echo '</ul>';

  							$results = $dispatcher->trigger('onInAlbumbox', array(&$items, &$this->params, 'artist', null, $filteritem->id));
  							echo trim(implode("\n", $results));
  						echo '</div>';
  					}
  				echo '</div>';
  			echo '</section>';
  		}
  	}

echo '</div>';