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
 * @subpackage	plg_download
 */
class plgPlayjoomDownload extends JPlugin
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

    public function onAfterPJContent(&$item, $params, $source=null, $TitleName=null) {

    	JHtml::_('formbehavior.chosen', 'select');

    	if ($source == 'genre') {
    		return null;
    	}

		if(isset($item->id)) {
			$item_id = '&listid='.$item->id;
		} else {
    		$item_id = null;
    	}

		if (isset($item->album)) {
			$name_string = '&name='.base64_encode($item->album);
		} else {
			$name_string = '&name='.JRequest::getVar('view');
		}

    	if (isset($item->artist)) {
    		$artist_sting = '&artist='.base64_encode($item->artist);
    	} else {
    		$artist_sting = null;
    	}

		if ($source) {
			$source_string = '&source='.$source;

			switch($source) {
				case 'album' :
					$item_album = JRequest::getVar('album');
					$item_artist = JRequest::getVar('artist');
					$name_string = '&name='.$item_album;
					$artist_sting = '&artist='.$item_artist;
				break;

				case 'playlist' :
					$item_id = '&listid='.JRequest::getVar('listid');
					$album_sting = '&name='.$TitleName;
				break;

			}

		} else {
			$source_string = null;
		}

		$link = 'index.php?option=com_playjoom&view=download'.$source_string.$name_string.$artist_sting.$item_id;

		$html = '';
		$html .= '<fieldset class="batch">';
			$html .= '<legend>'.JText::_('COM_PLAYJOOM_PLAYLIST_LABEL_DOWNLOAD').'</legend>';
			$html .= '<div class="directplay">';
	    		$html .= '<a href="'.$link.'" title="'.JText::_('COM_PLAYJOOM_PLAYLIST_CONTINUE_PLAYLIST').'" class="small button" target="_blank"><i class="fa fa-download"></i>'.JText::_('COM_PLAYJOOM_PLAYLIST_DOWNLOAD_ALBUM').'</a>';
			$html .= '</div>';
		$html .= '</fieldset>';

		return $html;
	}
}
