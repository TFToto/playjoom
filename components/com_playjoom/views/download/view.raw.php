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
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the PlayJoom Component
 *
 * @package   PlayJoom.Site
 * @subpackage  com_playjoom.views
 */
class PlayJoomViewDownload extends JViewLegacy {

        // Overwriting JView display method
        function display($tpl = null) {

        	$dispatcher	= JDispatcher::getInstance();

        	// Get data from the model
            $items       = $this->get('Items');

            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
            	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Problem with datadase request: '.implode("\r\n", $errors), 'priority' => JLog::ERROR, 'section' => 'site')));
            	return false;
            }

            //Load helper files
            JLoader::import( 'helpers.download', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');
            JLoader::import( 'helpers.playlist', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

            // Create folder name
            if (JRequest::getVar('source') == 'album') {
            	$foldername = JApplication::stringURLSafe(base64_decode(JRequest::getVar('artist'))).' - '.JApplication::stringURLSafe(base64_decode(JRequest::getVar('name')));
            } else {
            	$foldername = JApplication::stringURLSafe(base64_decode(JRequest::getVar('name')));
            }

            $CreateArchivFile = new PlayJoomHelperDownload();

            // Add the info file into the archive
            $CreateInfoFile = new PlayJoomHelperPlaylist();
            $CreateInfoFile->createInfoFile($items);
            $CreateArchivFile->addFile($CreateInfoFile->file(), $foldername.DIRECTORY_SEPARATOR.'readme.txt');

            // Add the playlist files into the archive
            $CreateM3UPlaylistFile = new PlayJoomHelperPlaylist();
            $CreateM3UPlaylistFile->CreateM3UList($items,true);
            $CreateArchivFile->addFile($CreateM3UPlaylistFile->file(), $foldername.DIRECTORY_SEPARATOR.'playlist.m3u');

            $CreatePLSPlaylistFile = new PlayJoomHelperPlaylist();
            $CreatePLSPlaylistFile->CreatePLSList($items,true);
            $CreateArchivFile->addFile($CreatePLSPlaylistFile->file(), $foldername.DIRECTORY_SEPARATOR.'playlist.pls');

            $CreateXSPFPlaylistFile = new PlayJoomHelperPlaylist();
            $CreateXSPFPlaylistFile->CreateXSPFList($items,true);
            $CreateArchivFile->addFile($CreateXSPFPlaylistFile->file(), $foldername.DIRECTORY_SEPARATOR.'playlist.xspf');

            $CreateWPLPlaylistFile = new PlayJoomHelperPlaylist();
            $CreateWPLPlaylistFile->CreateWPLList($items,true);
            $CreateArchivFile->addFile($CreateWPLPlaylistFile->file(), $foldername.DIRECTORY_SEPARATOR.'playlist.wpl');

            $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Ready for to create a download archiv file: '.JApplication::stringURLSafe(JRequest::getVar('name')).'.zip', 'priority' => JLog::INFO, 'section' => 'site')));

            // Add the audio files into the archive
            foreach($items as $i => $item) {
            	if (JRequest::getVar('source') == 'album') {
            		$filename = $item->tracknumber.' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype);
            	} else {
            		$filename = $i +1 .' - '.JApplication::stringURLSafe($item->artist).' - '.JApplication::stringURLSafe($item->title).'.'.PlayJoomHelper::getFileExtension($item->mediatype);
            	}
             	$CreateArchivFile->addFile(file_get_contents($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file), $foldername.DIRECTORY_SEPARATOR.$filename);
             }

             // Add cover file into archive, if once existing.
             $covercontent = PlayJoomHelper::getAlbumCover(base64_decode(JRequest::getVar('name')), base64_decode(JRequest::getVar('artist')));
             if ($covercontent) {
             	$CreateArchivFile->addFile($covercontent->data, $foldername.DIRECTORY_SEPARATOR.'cover.'.PlayJoomHelper::getFileExtension($covercontent->mime));
             }

             $CreateArchivFile->setHeader(JApplication::stringURLSafe($foldername).'.zip','application/zip',mb_strlen($CreateArchivFile->file(), '8bit'));
             // Send the archiv
             $CreateArchivFile->send($CreateArchivFile->file());
       }
}