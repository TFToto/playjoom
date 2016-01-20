<?php
/**
 * @package Joomla 3.0.x
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Plugin
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * playlist plugin.
 *
 * @package		PlayJoom
 * @subpackage	plg_playlist
 */
class plgPlayjoomPlaylist extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $params) {
		parent::__construct($subject, $params);
		$this->loadLanguage();
	}

	public function onBeforePJContent(&$item, $params, $TitleName=null ) {

	}

	public function onInAlbumbox (&$item, $params, $source=null, $TitleName=null, $filter_id=null) {

		JHtml::_('formbehavior.chosen', 'select');

    	if(isset($item->id)) {
    		$item_id = '&listid='.$item->id;
    	} else {
    		$item_id = null;
    	}

    	if (isset($item->album)) {
    		$album_sting = '&name='.$item->album;
    		$item_album = $item->album;
    	} else {
    		$album_sting = null;
    		$item_album = null;
    	}

    	if (isset($item->artist)) {
    		$artist_sting = '&artist='.$item->artist;
    		$item_artist = $item->artist;
    	} else {
    		$artist_sting = null;
    		$item_artist = null;
    	}

    	if($filter_id) {
    		$filter_id_string = '&trackfilterid='.$filter_id;
    	} else {
    		$filter_id_string = null;
    	}
    	if ($source) {
    		$source_string = '&source='.$source;

    		switch($source) {

    			case 'artist' :
    				$item_artist = JRequest::getVar('artist');
    				$artist_sting = '&artist='.$item_artist;
    				$order_sting = '&orderplaylist=a.year,a.tracknumber';
    				break;
    		}

    	} else {
    		$source_string = null;
    	}

    	$link = 'index.php?option=com_playjoom&view=playlist'.$source_string.$album_sting.$artist_sting.$item_id.$order_sting.$filter_id_string;
    	$linkwithorder = 'index.php?option=com_playjoom&view=playlist'.$source_string.'&orderplaylist=RAND()'.$album_sting.$artist_sting.$item_id.$filter_id_string;

    	$html = '';
    	$html .= '<fieldset class="batch">';

    	$html .= '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_PLAYLIST').'</legend>';

    	$html .= '<div class="directplay">';
    	$html .= '<a href="'.$link.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="small button" target="_blank"><i class="fa fa-play"></i>'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_ALL').'</a>';
    	$html .= '<a href="'.$linkwithorder.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="small button" target="_blank"><i class="fa fa-random"></i>'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_RAND').'</a>';
    	$html .= '</div>';

    	$html .= '<form action="'.JRoute::_('index.php?option=com_playjoom&view=playlist').'" method="post" name="playlistForm" id="playlistForm">';
    	$html .= '<p>'.JText::_('COM_PLAYJOOM_PLAYLIST_DOWNLOAD_PLAYLIST').'</p>';

    	$html .= '<select name="attachment_playlist" class="PJ-filtermenu" id="playlisttype-'.$filter_id.'">';
    	$html .= '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_SELECT_TYPE').'</option>';
    	$html .= '<option value="m3u">M3U</option>';
    	$html .= '<option value="pls">PLS</option>';
    	$html .= '<option value="wpl">WPL</option>';
    	$html .= '<option value="xspf">XSPF</option>';
    	$html .= '</select>';

    	$html .= '<select name="orderplaylist" class="PJ-filtermenu" id="ordertype-'.$filter_id.'" onchange="this.form.submit()">';
    	$html .= '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_SELECT_ORDER').'</option>';
    	$html .= '<option value="a.tracknumber">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_TRACKNO').'</option>';
    	$html .= '<option value="RAND()">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_RAND').'</option>';
    	$html .= '<option value="a.title">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_TITLE').'</option>';
    	$html .= '<option value="a.hits">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_HITS').'</option>';
    	$html .= '</select>';

    	if (isset($item->id)) {
    		$html .= '<input type="hidden" name="listid" value="'.$item->id.'" />';
    	} elseif (JRequest::getVar('listid')) {
    		$html .= '<input type="hidden" name="listid" value="'.JRequest::getVar('listid').'" />';
    	}
    	if ($filter_id) {
    		$html .= '<input type="hidden" name="trackfilterid" value="'.$filter_id.'" />';
    	}
    	$html .= '<input type="hidden" name="name" value="'.$item_album.'" />';
    	$html .= '<input type="hidden" name="artist" value="'.$item_artist.'" />';
    	$html .= '<input type="hidden" name="source" value="'.$source.'" />';
    	$html .= '<input type="hidden" name="disposition" value="attachment" />';

    	$html .= '</form>';

    	$html .= '</fieldset>';

    	if (!empty($this->plitems)) {

    		$html .= '<fieldset class="batch">';

    		$html .= '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_PLAYLIST_ATTACH').'</legend>';
    		//Attachment lists
    		if (!empty($this->plitems)) {
    			$html .= '<ul class="circle">';

    			foreach($this->plitems as $i => $item) {
    				//create link for form
    				$PLlink = 'index.php?option=com_playjoom&view=playlist&source=playlist&listid='.$item->id.'&name='.base64_encode($item->name);
    				$html .= '<li><a href="'.$PLlink.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" target="_blank">'.$item->name.'</a></li>';
    			}
    			$html .= '</ul>';
    		}

    		$html .= '</fieldset>';
    	}

    	return $html;
    }
    public function onAfterPJContent(&$item, $params, $source=null, $TitleName=null) {

    	JHtml::_('formbehavior.chosen', 'select');

		if(isset($item->id)) {
			$item_id = '&listid='.$item->id;
		} else {
			$item_id = null;
		}

		if (isset($item->album)) {
			$album_sting = '&name='.$item->album;
			$item_album = $item->album;
		} else {
			$album_sting = null;
			$item_album = null;
		}

    	if (isset($item->artist)) {
    		$artist_sting = '&artist='.$item->artist;
    		$item_artist = $item->artist;
    	} else {
    		$artist_sting = null;
    		$item_artist = null;
    	}

    	$source_string = null;
    	$artist_string = null;

		if ($source) {
			$source_string = '&source='.$source;

			switch($source) {

				case 'album' :
					$item_album = JRequest::getVar('album');
					$item_artist = JRequest::getVar('artist');
					$album_string = '&name='.$item_album;
					$artist_string = '&artist='.$item_artist;
					$order_string = null;
				break;

				case 'playlist' :
					$item_id = '&listid='.JRequest::getVar('listid');
					$album_string = '&name='.$TitleName;
					$order_string = null;
				break;

				case 'artist' :
					$item_artist = JRequest::getVar('artist');
					$artist_string = '&artist='.$item_artist;
					$order_string = '&orderplaylist=a.year,a.tracknumber';
					$album_string = null;
				break;

				case 'genre' :
					if(!JRequest::getVar('cat') == '' || !JRequest::getVar('catid') == '') {
						$artist_string ='&name='.JRequest::getVar('cat');
						$order_string = '&orderplaylist=a.year,a.artist,a.album,a.tracknumber';
						$album_string = null;
					}
				break;

				default :
					$order_string = null;
					$artist_string = null;
					$album_string = null;
				break;
			}

		}

		$link = 'index.php?option=com_playjoom&view=playlist'.$source_string.$album_string.$artist_string.$item_id.$order_string;
		$linkwithorder = 'index.php?option=com_playjoom&view=playlist'.$source_string.'&orderplaylist=RAND()'.$album_string.$artist_string.$item_id;

		$html = '';
		$html .= '<fieldset class="batch">';

			$html .= '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_PLAYLIST').'</legend>';

				$html .= '<div class="directplay">';
					$html .= '<a href="'.$link.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="small button" target="_blank"><i class="fa fa-play"></i>'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_ALL').'</a>';
					$html .= '<a href="'.$linkwithorder.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="small button" target="_blank"><i class="fa fa-random"></i>'.JText::_('COM_PLAYJOOM_PLAYLIST_PLAY_RAND').'</a>';
				$html .= '</div>';

				$html .= '<form action="'.JRoute::_('index.php?option=com_playjoom&view=playlist').'" method="post" name="playlistForm" id="playlistForm">';
					$html .= '<p>'.JText::_('COM_PLAYJOOM_PLAYLIST_DOWNLOAD_PLAYLIST').'</p>';

					$html .= '<select name="attachment_playlist" class="PJ-filtermenu" id="playlisttype">';
	         			$html .= '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_SELECT_TYPE').'</option>';
	         			$html .= '<option value="m3u">M3U</option>';
	         			$html .= '<option value="pls">PLS</option>';
	         			$html .= '<option value="wpl">WPL</option>';
	         			$html .= '<option value="xspf">XSPF</option>';
	     			$html .= '</select>';

	     			$html .= '<select name="orderplaylist" class="PJ-filtermenu" id="ordertype" onchange="this.form.submit()">';
	         			$html .= '<option value="">'.JText::_('COM_PLAYJOOM_PLAYLIST_SELECT_ORDER').'</option>';
	         			$html .= '<option value="a.tracknumber">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_TRACKNO').'</option>';
	         			$html .= '<option value="RAND()">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_RAND').'</option>';
	         			$html .= '<option value="a.title">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_TITLE').'</option>';
	         			$html .= '<option value="a.hits">'.JText::_('COM_PLAYJOOM_PLAYLIST_ORDER_TYPE_HITS').'</option>';
	     			$html .= '</select>';

	     			if (isset($item->id)) {
	     				$html .= '<input type="hidden" name="listid" value="'.$item->id.'" />';
	     			} elseif (JRequest::getVar('listid')) {
	     				$html .= '<input type="hidden" name="listid" value="'.JRequest::getVar('listid').'" />';
	     			}
	     			$html .= '<input type="hidden" name="name" value="'.$item_album.'" />';
	     			$html .= '<input type="hidden" name="artist" value="'.$item_artist.'" />';
	     			$html .= '<input type="hidden" name="source" value="'.$source.'" />';
	     			$html .= '<input type="hidden" name="disposition" value="attachment" />';

				$html .= '</form>';

			$html .= '</fieldset>';

			if (!empty($this->plitems)) {

				$html .= '<fieldset class="batch">';

         			$html .= '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_PLAYLIST_ATTACH').'</legend>';
         			//Attachment lists
         			if (!empty($this->plitems)) {
             			$html .= '<ul class="circle">';

                 		foreach($this->plitems as $i => $item) {
                    		//create link for form
                    		$PLlink = 'index.php?option=com_playjoom&view=playlist&source=playlist&listid='.$item->id.'&name='.base64_encode($item->name);
	                		$html .= '<li><a href="'.$PLlink.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" target="_blank">'.$item->name.'</a></li>';
                 		}
             			$html .= '</ul>';
         			}

    			$html .= '</fieldset>';
			}

			return $html;
	}
}
