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
 * @date $Date: 2013-09-08 14:20:12 +0200 (So, 08 Sep 2013) $
 * @revision $Revision: 829 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/artist/tmpl/default_artist.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Load JavaScripts for light box
JHtml::_('behavior.modal');

//Load PlayJoom Slider
JLoader::import( 'helpers.pjslider', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

$artist = base64_decode(JRequest::getVar('artist'));
$artist_infos = PlayJoomModelArtist::getArtistInfos($artist);
$artist_count = PlayJoomModelArtist::getArtistCount($artist);

$number_of_albums = PlayJoomArtistHelper::getOptions('album',base64_decode(JRequest::getVar('artist')));
    
if ($artist_count->counter == 1 && $artist_infos->infotxt != ''
 || $artist_count->counter == 1 && $artist_infos->members != ''
 || $artist_count->counter == 1 && $artist_infos->formation != '0000-00-00') {
	
	$artist_content = '<div class="infotxt">'.$artist_infos->infotxt.'</div>'.
	                     '<ul class="infolist">';
	                  if ($artist_infos->members != '') {
	                  	 $artist_content .= '<li>'.JText::_('COM_PLAYJOOM_ALBUM_MEMBERS').': '.$artist_infos->members.'</li>';
	                  }
	                  if ($artist_infos->formation != '0000-00-00') {
	                   	 $artist_content .= '<li>'.JText::_('COM_PLAYJOOM_ALBUM_FOUNDED').': '.$artist_infos->formation.'</li>';
	                  }
	$artist_content .= '</ul>';
	
	echo '<div class="trackbox">';
    echo '<h3 class="subheader">'.$artist.' | '.$this->pagination->total.' Tracks</h3>';   
    echo JHtml::_('PlayJoomSliders.start');
	echo JHtml::_('PlayJoomSliders.panel',JText::_('COM_PLAYJOOM_ALBUM_MORE_INFO'),'more');
	echo $artist_content;
	echo JHtml::_('PlayJoomSliders.end');
	echo '</div>';
}

else {
	echo '<div class="trackboxx">';
    echo '<h3 class="subheader">'.$artist.' | '.$this->pagination->total.' Tracks</h3>';   
	echo '</div>';
}
echo '<div class="totalline_time"></div>';
?>