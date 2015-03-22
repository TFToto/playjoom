<?php
/**
 * Contains the helper methods for the PlayJoom playlist.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 * Contains the helper methods for the PlayJoom playlist.
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.helpers
 */
class PlayJoomHelperPlaylist {

	var $data = array();

	/**
	 * Method for to create a info readme file about the album
	 *
	 * @param array $items album items
	 */
	public function createInfoFile($items) {

		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Starting for create info text file.', 'priority' => JLog::INFO, 'section' => 'site')));

		$PJContent  = "Album Infos:\r\n";
		$PJContent .= base64_decode(JRequest::getVar('name')).", ".base64_decode(JRequest::getVar('artist'))."\r\n";
		$PJContent .= "------------------------------------------------------------------------------\r\n";
		foreach($items as $i => $item) {

			if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

				$PJContent .= $item->artist.", ".$item->album." - ".$item->tracknumber." - ".$item->title." (".PlayJoomHelper::Playtime($item->length).")\r\n";

			} else {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Track '.$item->title.' isn´t available!', 'priority' => JLog::WARNING, 'section' => 'site')));
			}

		}

		$PJContent .= "\r\n";
		$PJContent .= "\r\n";

		$About =  array('artist' => JRequest::getVar('artist'),'album' => JRequest::getVar('name'),'type' => JRequest::getVar('type'));
		require_once JPATH_COMPONENT.'/apis/lastfm.php';

		$ContentExist = PlayJoomLastfmHelper::CheckLastfmContent('album', $About);
        if ($ContentExist == true) {
        	$PJContent .= "About the album:\r\n";
        	$PJContent .= "------------------------------------------------------------------------------\r\n";
        	$PJContent .= PlayJoomLastfmHelper::GetLastfmContent('album', $About)->album->wiki->content;
        	$PJContent .= "------------------------------------------------------------------------------\r\n";
        }
        $ContentExist = PlayJoomLastfmHelper::CheckLastfmContent('artist', $About);
        if ($ContentExist == true) {
        	$PJContent .= "About the artist / band:\r\n";
        	$PJContent .= "------------------------------------------------------------------------------\r\n";
        	$PJContent .= PlayJoomLastfmHelper::GetLastfmContent('artist', $About)->artist->bio->content;
        	$PJContent .= "------------------------------------------------------------------------------\r\n";
        }

		$PJContent .= "\r\n";
		$PJContent .= "\r\n";
		$PJContent .= "------------------------------------------------------------------------------\r\n";
		$PJContent .= "Archiv created by PlayJoom Server.\r\n";
		$PJContent .= PlayJoomHelper::GetInstallInfo("description","playjoom.xml")."\r\n";
		$PJContent .= "\r\n";
		$PJContent .= "\r\n";
		$PJContent .= "Version: ".PlayJoomHelper::GetInstallInfo("version","playjoom.xml")."\r\n";
		$PJContent .= "copyright: ".PlayJoomHelper::GetInstallInfo("copyright","playjoom.xml")."\r\n";
		$PJContent .= "web: ".PlayJoomHelper::GetInstallInfo("authorUrl","playjoom.xml")."\r\n";

		$this->data[] = $PJContent;
	}

	/**
	 * Method for to create a m3u playlist into a array
	 *
	 * @param array $items
	 */
	public function CreateM3UList ($items, $archiv=false) {

		$session = JFactory::getSession();

		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Starting for create the playlist data for server address: '.$_SERVER['SERVER_NAME'], 'priority' => JLog::INFO, 'section' => 'site')));

		$PJContent = "#EXTM3U\r\n";

    	foreach($items as $i => $item) {

    		if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

    			$PJContent .= "#EXTINF:".round($item->length).",".htmlentities($item->title, ENT_QUOTES, 'UTF-8')."\r\n";

    			if ($archiv == true) {
    				if (JRequest::getVar('source') == 'album') {
    					$PJContent .= $item->tracknumber.' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype)."\r\n";
    				} else {
    					$PJContent .= $i +1 .' - '.JApplication::stringURLSafe($item->artist).' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype)."\r\n";
    				}
    			} else {
    				$PJContent .= SERVER_REF."?option=com_playjoom&view=broadcast&format=raw&tlk=".hash('sha256',$session->getId()."+".PlayJoomHelper::getUserIP()."+".$item->id)."&id=".$item->id."\r\n";
    			}

            } else {
               	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Track '.$item->title.' isn´t available!', 'priority' => JLog::WARNING, 'section' => 'site')));
            }

        }
        $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Done to write playlist file.', 'priority' => JLog::INFO, 'section' => 'site')));
        $this->data[] = $PJContent;
	}

	/**
	 * Method for to create a pls playlist into a array
	 *
	 * @param array $items
	 */
	public function CreatePLSList ($items, $archiv=null) {

		$PJContent = null;
		$session = JFactory::getSession();
		$dispatcher	= JDispatcher::getInstance();

		$Entriecounter = count($items);
		$filecounter = 0;

		$PJContent .= "[playlist]\r\nNumberOfEntries=".$Entriecounter."\r\n";

		foreach($items as $i => $item) {

			if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {
				$filecounter = $filecounter +1;
				if ($archiv == true) {
					if (JRequest::getVar('source') == 'album') {
						$PJContent .=  "File".$filecounter."=".$item->tracknumber.' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype)."\r\n";
					} else {
						$PJContent .=  "File".$filecounter."=".$i +1 .' - '.JApplication::stringURLSafe($item->artist).' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype)."\r\n";
					}
				} else {
					$PJContent .=  "File".$filecounter."=".SERVER_REF."?option=com_playjoom&view=broadcast&format=raw&tlk=".hash('sha256',$session->getId()."+".PlayJoomHelper::getUserIP()."+".$item->id)."&id=".$item->id."\r\n";
				}
				$PJContent .=  "Title".$filecounter."=". htmlentities($item->title, ENT_QUOTES, 'UTF-8') ."\r\n";
				$PJContent .=  "Length=". round($item->length) ."\r\n";
			} else {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Track '.$item->title.' isn´t available!', 'priority' => JLog::WARNING, 'section' => 'site')));
			}
		}
		$PJContent .= "Version=2";
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Done to write playlist file.', 'priority' => JLog::INFO, 'section' => 'site')));

		$this->data[] = $PJContent;
	}

	/**
	 * Method for to create a xspf playlist into a array
	 *
	 * @param array $items
	 */
	public function CreateXSPFList ($items, $archiv=null) {

		$dispatcher	= JDispatcher::getInstance();
		$session = JFactory::getSession();

		$xml = new DOMDocument('1.0', 'utf-8');
		$xml->formatOutput = true;

		// Create root element
		$xml_playlist = $xml->createElement( "playlist" );
		$xml_trackList = $xml->createElement('trackList');
		// Set the attributes for root.
		$xml_playlist->setAttribute( "xmlns", "http://xspf.org/ns/0/" );
		$xml_playlist->setAttribute( "version", "1" );

		foreach($items as $i => $item) {

			if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {
				$xml_track = $xml->createElement("track");
				$xml_tracktitle = $xml->createElement("title", htmlspecialchars($item->title));
				$xml_trackcreator = $xml->createElement("creator", htmlspecialchars($item->artist));
				if ($archiv == true) {
					if (JRequest::getVar('source') == 'album') {
						$xml_tracklocation = $xml->createElement("location", $item->tracknumber.' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype));
					} else {
						$xml_tracklocation = $xml->createElement("location", $i +1 .' - '.JApplication::stringURLSafe($item->artist).' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype));
					}
				} else {
					$xml_tracklocation = $xml->createElement("location", SERVER_REF."?option=com_playjoom&amp;view=broadcast&amp;format=raw&amp;tlk=".hash('sha256',$session->getId()."+".PlayJoomHelper::getUserIP()."+".$item->id)."&amp;id=".$item->id);
				}
				$xml_trackalbum = $xml->createElement("album", htmlspecialchars($item->album));
				$xml_trackno = $xml->createElement("trackNum", $item->tracknumber);
				$xml_trackduration = $xml->createElement("duration", $item->length * 1000);
				$xml_track->appendChild($xml_tracktitle);
				$xml_track->appendChild($xml_trackcreator);
				$xml_track->appendChild($xml_tracklocation);
				$xml_track->appendChild($xml_trackalbum);
				$xml_track->appendChild($xml_trackno);
				$xml_track->appendChild($xml_trackduration);
				$xml_trackList->appendChild($xml_track);
			}
			else {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Track '.$item->title.' isn´t available!', 'priority' => JLog::ERROR, 'section' => 'site')));
			}
		}

		$xml_playlist->appendChild($xml_trackList);
		$xml->appendChild( $xml_playlist );

		$this->data[] = $xml->saveXML() . "\n";
	}

	/**
	 * Method for to create a wpl playlist into a array
	 *
	 * @param array $items
	 */
	public function CreateWPLList ($items, $archiv=null) {

		$PJContent = null;
		$session = JFactory::getSession();
		$dispatcher	= JDispatcher::getInstance();

		$Entriecounter = count($items);
		$filecounter = 0;

		$PJContent = null;
		$PJContent .= '<?wpl version="1.0"?>'."\r\n";
		$PJContent .= '<smil>'."\r\n";
		$PJContent .= '<head>'."\r\n";
		$PJContent .= '<meta name="Generator" content="PlayJoom Audio Server"/>'."\r\n";
		$PJContent .= '<meta name="ItemCount" content="'.$Entriecounter.'"/>'."\r\n";
		$PJContent .= '<title>Playlist</title>'."\r\n";
		$PJContent .= '</head>'."\r\n";
		$PJContent .= '<body>'."\r\n";
		$PJContent .= '<seq>'."\r\n";
		foreach($items as $i => $item) {

			if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {
				$filecounter = $filecounter +1;
				if ($archiv == true) {
					if (JRequest::getVar('source') == 'album') {
						$PJContent .=  '<media src="'.$item->tracknumber.' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype).'"/>'."\r\n";
					} else {
						$PJContent .=  '<media src="'.$i +1 .'-'.JApplication::stringURLSafe($item->artist).'-'.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype).'"/>'."\r\n";
					}
				} else {
					$PJContent .=  '<media src="'.SERVER_REF."?option=com_playjoom&view=broadcast&format=raw&tlk=".hash('sha256',$session->getId()."+".PlayJoomHelper::getUserIP()."+".$item->id)."&id=".$item->id.'"/>'."\r\n";
				}
			} else {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Track '.$item->title.' isn´t available!', 'priority' => JLog::WARNING, 'section' => 'site')));
			}
		}
		$PJContent .= '</seq>'."\r\n";
		$PJContent .= '</body>'."\r\n";
		$PJContent .= '</smil>'."\r\n";
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Done to write playlist file.', 'priority' => JLog::INFO, 'section' => 'site')));

		$this->data[] = $PJContent;
	}

    /**
     * Method for to create the playlist file for sending
     *
     * @return string data
     */
    public function file() {
    	$data = implode('', $this->data);
		return $data;
    }
    /**
     * Method for to send the created zip archive
     *
     * @param string $data
     */
    public function send($data) {

    	print $data;
    	exit;
    }
	/**
	 * Method for sending the page header
	 *
	 * @param string $playlistFileName Name of the playlist file
	 * @param string $playlistType     Mimetype of the playlist
	 * @param int    $playlistSize     Data size of the created playlist
	 *
	 * @return void
	 */
    public function setHeader($playlistFileName,$playlistType,$playlistSize) {

    	//Setup web server
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			$sf = $_SERVER['SERVER_SOFTWARE'];
		} else {
			$sf = getenv('SERVER_SOFTWARE');
		}

		if (!strpos($sf, 'IIS')) {
			@apache_setenv('no-gzip', 1);
		}
    	@ini_set('zlib.output_compression', 0);

    	ignore_user_abort(false);

    	JResponse::clearHeaders();
    	header("HTTP/1.1 200 OK");

    	JResponse::setHeader('Accept-Range','bytes', true);
    	JResponse::setHeader('X-Pad','avoid browser bug');
    	JResponse::setHeader('Content-Type', $playlistType, true);
    	JResponse::setHeader('Content-Disposition', 'inline; filename='.$playlistFileName.';', true);
    	JResponse::setHeader('Content-Range','bytes 0-'.($playlistSize -1).'/'.$playlistSize, true);
    	JResponse::setHeader('Content-Length',(string)$playlistSize,true);
    	JResponse::setHeader('Content-Transfer-Encoding','binary', true);
    	JResponse::setHeader('Cache-Control','no-cache, must-revalidate', true);
    	JResponse::setHeader('Pragma','no-cache', true);
    	JResponse::setHeader('Connection','close', true);

    	JResponse::sendHeaders();
    }
}