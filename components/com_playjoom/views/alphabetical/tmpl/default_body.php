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
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

$dispatcher	= JDispatcher::getInstance();

//Load JavaScripts for light box
JHtml::_('behavior.modal');

$counter = null;

switch($this->params->get('show_alphabetical')) {

	case 'artist' :
		//Placeholder for artist
	break;
	case 'album' :
		//Placeholder for album
	break;
	default :
		$cover_counter = null;

		foreach($this->items as $i => $item) {

			//Get list of albums for this artist
			$album_list = PlayJoomModelAlphabetical::getAlbumList($item->artist);

			//Check for albumname as sampler
			$SamplerCheck = PlayJoomHelper::checkForSampler($item->album, $item->artist);

			if (!$SamplerCheck) {
				$artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
			} else {
				$artistname = $item->artist;
			}

			$genresting = base64_encode($item->category_title);
			$artiststing = base64_encode($item->artist);

			$genrelink = PlayJoomHelperRoute::getPJlink('genres','&catid='.$item->catid);
			$artistlink = PlayJoomHelperRoute::getPJlink('artists','&artist='.$artiststing);

			//Configuration of the modal windows
			$modal_artist_config = "{handler: 'iframe', size: {x: ".$this->params->get('modal_infoabout_size_v', 600).", y: ".$this->params->get('modal_infoabout_size_h', 500)."}}";
			$modal_album_config = "{handler: 'iframe', size: {x: ".$this->params->get('modal_infoabout_size_v', 600).", y: ".$this->params->get('modal_infoabout_size_h', 500)."}}";
			$modal_genre_config = "{handler: 'iframe', size: {x: ".$this->params->get('modal_infoabout_size_v', 600).", y: ".$this->params->get('modal_infoabout_size_h', 500)."}}";

			$AboutInfo = array('artist' => base64_encode($item->artist), 'album' => null, 'genre' => $genresting);
			/*
			* - Check for additional artist infos
			* - and whether the button is active
			*/
			if ($this->params->get('show_additional_info_artist_alphabetical', 0) == 1) {
				$ArtistChecker = PlayJoomHelper::getInfoButton('artist', $AboutInfo, $modal_artist_config, $this->params);
			} else {
				$ArtistChecker = null;
			}

			/*
			* - Check for additional genre infos
			* - and whether the button is active
			*/
			if ($this->params->get('show_additional_info_genre_alphabetical', 0) == 1) {
				$GenreChecker = PlayJoomHelper::getInfoButton('genre', $AboutInfo, $modal_genre_config, $this->params);
			} else {
				$GenreChecker = null;
			}

			$counter = $counter + 1;
			echo '<fieldset class="batch">';
				echo '<legend><a href="'.$artistlink.'" title="Continue to the artist view" class="ArtistLink">'.$item->artist.'</a>'.$ArtistChecker.'&nbsp;|&nbsp;<a href="'.$genrelink.'" class="GenreLink" title="Continue to the genre view">'.$item->category_title.'</a>'.$GenreChecker.'</legend>';
				echo '<ul class="list_of_albums">';

					foreach($album_list as $j => $albenitem) {

						$cover_counter = $cover_counter +1;
						$albumstring = base64_encode($albenitem->album);
						$artiststring = base64_encode($albenitem->artist);
						$categorysting = base64_encode($albenitem->category_title);
						$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumstring.'&artist='.$artiststring.'&Itemid='.JRequest::getVar('Itemid');

						//create album string
						$AlbumLenght = strlen($albenitem->album);
						if ($AlbumLenght > 14) {
							$AlbumName = substr($albenitem->album,0, 11).'...';
						} else {
							$AlbumName = $albenitem->album;
						}

						echo '<li class="album_item"><a title="Continue to the album view" href="'.$albumlink.'"><img class="cover" data-src="'.JRoute::_(PlayJoomHelperRoute::getCoverRoute($albenitem,$albumstring,$artiststring,$categorysting, JFactory::getApplication()->input->get('view'))).'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" />'.PlayJoomHelper::createItemtitle($albenitem, PlayJoomHelper::checkForSampler($albenitem->album, $albenitem->artist)).'</a></li>';
						//echo '<li class="AlbumList"><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.'<br />'.$AlbumName.'</a> ('.$albenitem->year.')</li>';
						}
				echo '</ul>';
			echo '</fieldset>';

		}
	break;
}
