<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('formbehavior.chosen', 'select');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

//In Albumbox plugins configurations
JPluginHelper::importPlugin('playjoom');
$dispatcher	= JDispatcher::getInstance();

$this->events = new stdClass;

echo '<div class="section-container auto" data-section>';

	//All track tabs
	echo '<section>';
		echo '<p class="title" data-section-title><a class="tab" href="#panel0">'.JText::_('JALL').' ('.count($this->albumitems).')</a></p>';
		echo '<div class="content" data-section-content>';
			echo '<ul class="list_of_albums">';
				foreach($this->albumitems as $i => $item) {
					$albumsting = base64_encode($item->album);
					$artiststing = base64_encode($item->artist);
					$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').'&cat='.base64_encode($item->category_title).'&catid='.$item->catid;

					//Check for albumname as sampler
					$SamplerCheck = PlayJoomHelper::checkForSampler($item->album, $item->artist);

				echo '<li class="album_item"><a title="Continue to the album view" href="'.$item->albumlink.'"><img class="cover" data-src="'.$item->coverlink.'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" />'.$item->itemtitle.'</a></li>';
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
  				echo '<p class="title" data-section-title><a class="tab" href="#panel'.($i+1).'">'.$filteritem->title.' ('.count($this->filteritems).')</a></p>';
  				echo '<div class="content" data-section-content>';
  					//Get filter items
  					$this->filteritems = PlayJoomModelArtist::getFilteritems($filteritem->id);
  					if(count($this->filteritems) > 0) {
  						echo '<div class="content">';
  							echo '<ul class="list_of_albums">';
  								foreach($this->filteritems as $i => $item) {

  									$albumsting = base64_encode($item->album);
  									$artiststing = base64_encode($item->artist);
									$categorystring = base64_encode($item->category_title);
  									$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').'&cat='.base64_encode($item->category_title).'&catid='.$item->catid;

									echo '<li class="album_item"><a title="Continue to the album view" href="'.PlayJoomHelper::createAlbumlink($item,$albumsting,$artiststing,$categorystring).'"><img class="cover" data-src="'.PlayJoomHelper::createCoverlink($item,$albumsting,$artiststing,$categorystring,JFactory::getApplication()->input->get('view')).'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" />'.PlayJoomHelper::createItemtitle($item, PlayJoomHelper::checkForSampler($item->album, $item->artist)).'</a></li>';
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
