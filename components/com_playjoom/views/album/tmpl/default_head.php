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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$album = base64_decode(JRequest::getVar('album'));
$artist = null;
$genre = null;
$artiststing = null;	

require_once JPATH_COMPONENT.'/apis/lastfm.php';

    
foreach($this->items as $i => $item)
{
	//Prepare for artist information
    //Check for albumname as sampler
	$SamplerCheck = PlayJoomHelper::checkForSampler($item->album, $item->artist);
	
	if ($SamplerCheck)
	{
		$artist = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
		$artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
	}
	else 
	{
		$artistname = $item->artist;
	    $artiststing = base64_encode($artistname); 
		$artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
		$artist = '<a href="'.$artistlink.'">'.$artistname.'</a>';
    }
         
    //Prepare for genre information
    if ($genre == "")
    {
    	$genresting = base64_encode($item->category); 
		$genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid');
    	$genre = '<a href="'.$genrelink.'">'.$item->category.'</a>';
    }
    else if($genre != "" 
         && $genresting <> base64_encode($item->category)) {
         	$genre = JText::_('COM_PLAYJOOM_ALBUM_DIFF_GENRE');
    }
     
    $year = $item->year;
    $add = $item->add_datetime;
}

//Configuration of the modal windows
$modal_artist_config = "{handler: 'iframe', size: {x: ".$this->params->get('modal_infoabout_size_v', 600).", y: ".$this->params->get('modal_infoabout_size_h', 500)."}}";
$modal_album_config = "{handler: 'iframe', size: {x: ".$this->params->get('modal_infoabout_size_v', 600).", y: ".$this->params->get('modal_infoabout_size_h', 500)."}}";
$modal_genre_config = "{handler: 'iframe', size: {x: ".$this->params->get('modal_infoabout_size_v', 600).", y: ".$this->params->get('modal_infoabout_size_h', 500)."}}";

/*
 * - Check for additional infos
 * - and whether the button is active
 */
if (isset($genresting)) {
	$AboutInfo = array('artist' => $artiststing, 'album' => JRequest::getVar('album'), 'genre' => $genresting);
}

if ($this->params->get('show_additional_info_arist', 1) == 1
 && !PlayJoomHelper::checkForSampler($item->album, $item->artist)) {
 	$ArtistChecker = PlayJoomHelper::getInfoButton('artist', $AboutInfo, $modal_artist_config, $this->params);
}
else {
	$ArtistChecker = null;
}


if ($this->params->get('show_additional_info_album', 1) == 1) {
	$AlbumChecker = PlayJoomHelper::getInfoButton('album', $AboutInfo, $modal_album_config, $this->params);
}
else {
	$AlbumChecker = null;
}

if ($this->params->get('show_additional_info_genre') == 0) {
	$GenreChecker = PlayJoomHelper::getInfoButton('genre', $AboutInfo, $modal_genre_config, $this->params);
}
else {
	$GenreChecker = null;	
}

if (isset($album, $year, $artist, $artistname, $genre)) {
	echo '<h4 class="subheader">'.$artist.$ArtistChecker.'</h4>';
    
    echo PlayJoomModelAlbum::getAlbumNavigator($album, $artistname, $year, $add, 'past').
     '<div class="albumtitle_albumviewer">'.$album.$AlbumChecker.'</div>'.
     PlayJoomModelAlbum::getAlbumNavigator($album, $artistname, $year, $add, 'future');
    echo '<div class="genre_div">Genre: <b>'.$genre.'</b>'.$GenreChecker.'</div>';	
}
?>