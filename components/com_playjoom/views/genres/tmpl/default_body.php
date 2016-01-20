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

$counter = null;

//Set Filter for genre items
$FilterYear = array();
$FilterArtist = array();
$FilterUserID = array();

$FilterYear = array('filter.year' => $this->state->get('filter.year'));
$FilterArtist = array('filter.artist' => $this->state->get('filter.artist'));
$FilterUserID  = array('filter.user_id' => $this->state->get('filter.user_id'));

$Filter = array_merge($FilterYear,$FilterArtist,$FilterUserID);

foreach($this->items as $i => $item)
{
	$genre_item = PlayJoomModelGenres::getGenreItems($item->catid, $Filter);

	if($genre_item)
	{
		//Create genre link
        $genrelink = JRoute::_('index.php?option=com_playjoom&view=genre&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid'));

		echo '<div class="albumsview">';
        echo '<h2 class="genres_title"><a href="'.$genrelink.'" title="Continue to the genre view">'.$item->category_title.'</a></h2>';
        echo '<ul class="list_of_albums'.$this->params->get('genres_show_cover').'">';

		foreach($genre_item as $j => $item_entrie)
		{
			if ($j < $this->params->get('NumberOfAlbums'))
		    {
		    	$SamplerCheck = PlayJoomHelper::checkForSampler($item_entrie->album, $item_entrie->artist);

		    	//Create album link
		    	$albumsting = base64_encode($item_entrie->album);
		    	$artiststing = base64_encode($item_entrie->artist);
                $albumlink = JRoute::_('index.php?option=com_playjoom&view=album&cat='.base64_encode($item->category_title).'&catid='.$item->catid.'&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid'));

				//Get Album thumbnail
				if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
					$cover = new PlayJoomHelperCover();
					$coverthumb = $cover->getCoverHTMLTag($item_entrie, $SamplerCheck);
				}

		        //Prepare for artist information
                //Check for albumname as sampler
	            if ($SamplerCheck) {
		            $artist = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
	            } else {
		            $artist = $item_entrie->artist;
                }

                //create artist string
                $NameLenght = strlen($artist);
                if ($NameLenght > 15)
                {
                	$ArtistName = substr($artist,0, 14).'...';
                }
                else
                {
                	$ArtistName = $artist;
                }

		        //create album string
                $AlbumLenght = strlen($item_entrie->album);
                if ($AlbumLenght > 36)
                {
                	$AlbumName = substr($item_entrie->album,0, 35).'...';
                }
                else
                {
                	$AlbumName = $item_entrie->album;
                }
                echo '<li class="genre_item'.$this->params->get('genres_show_cover').'"><a href="'.$albumlink.'" title="Continue to the album view">'.$ArtistName.'<br />'.$coverthumb.$AlbumName.'</a> ('.$item_entrie->year.')</li>';
		    }
		}

		echo '</ul>';
		echo '</div>';
    }
}