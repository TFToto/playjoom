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
 * @date $Date: 2013-05-12 10:44:39 +0200 (So, 12 Mai 2013) $
 * @revision $Revision: 779 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/playlist/view.html.php $
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewPlaylist extends JViewLegacy {

        // Overwriting JView display method
        function display($tpl = null) {

        	$dispatcher	= JDispatcher::getInstance();

        	// Get data from the model
            $items       = $this->get('Items');

            //Get setting values from xml file
            $app		= JFactory::getApplication();
            $params		= $app->getParams();

            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
            	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Problem with datadase request: '.implode("\r\n", $errors), 'priority' => JLog::ERROR, 'section' => 'site')));
            	return false;
            }

            //Load playlist helper
            JLoader::import( 'helpers.playlist', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

            $PlaylistFile = new PlayJoomHelperPlaylist();

             if (JRequest::getVar('attachment_playlist') != '') {
             	$listtype = JRequest::getVar('attachment_playlist');
             } else {
                $listtype = $params->get('playlist_type','m3u');
             }

             $filename = base64_decode(JRequest::getVar('name'));

             if ($filename != '' && JRequest::getVar('disposition') == 'attachment') {
             	$playlistFileName = JApplication::stringURLSafe($filename).'.' .$listtype;
             } else {
                $playlistFileName = 'playlist.'.$listtype;
             }

             switch($listtype) {
             	case 'pls' :
             		$PlaylistFile->CreatePLSList($items, false);
             		$PlaylistFile->setHeader($playlistFileName,'application/pls+xml',mb_strlen($PlaylistFile->file(), '8bit'));
             		break;

                case 'm3u' :
             		$PlaylistFile->CreateM3UList($items, false);
             		$PlaylistFile->setHeader($playlistFileName,'audio/x-mpegurl',mb_strlen($PlaylistFile->file(), '8bit'));
             		break;

             	case 'xspf' :
             		$PlaylistFile->CreateXSPFList($items, false);
             		$PlaylistFile->setHeader($playlistFileName,'application/xspf+xml',mb_strlen($PlaylistFile->file(), '8bit'));
             		break;

             	case 'wpl' :
             		$PlaylistFile->CreateWPLList($items, false);
             		$PlaylistFile->setHeader($playlistFileName,'application/vnd.ms-wpl',mb_strlen($PlaylistFile->file(), '8bit'));
             		break;

             	case 'plist' :
             		// Placeholder for Apples plist
             		break;

             	default:
             		$PlaylistFile->CreateM3UList($items, false);
             		$PlaylistFile->setHeader($playlistFileName,'audio/x-mpegurl',mb_strlen($PlaylistFile->file(), '8bit'));
             		break;
             }

            $PlaylistFile->send($PlaylistFile->file());
       }
}