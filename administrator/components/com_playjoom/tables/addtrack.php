<?php
/**
 * Contains the addtrack database methods for add tracks.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package Joomla 3.0 and PlayJoom 0.9
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

abstract class AddTrackTables
{

        public function check_md5($md5)
        {
        	$db = JFactory::getDBO();
            $query = $db->getQuery(true);
    	    $query->select('COUNT(*) as counter');
            $query->from('#__jpaudiotracks');
            $query->where('md5 = "'.$md5.'"');

            $db->setQuery($query);
            $result = $db->loadObject();

            return $result->counter;
        }


        /*
         * Functions for Artist table
         */
        public function check_artist($NameOfArtist)
        {
        	$db = JFactory::getDBO();
            $query = $db->getQuery(true);
    	    $query->select('COUNT(*) as counter');
            $query->from('#__jpartists');
            $query->where('name = "'.$NameOfArtist.'"');

            $db->setQuery($query);
            $result = $db->loadObject();

            return $result->counter;
        }

        public function save_new_artist($NameOfArtist, $catid)
        {
        	$db = JFactory::getDBO();
            $row =& JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

        	   $obj = new stdClass();
        	   $obj->id = null;
        	   $obj->catid = $catid;
        	   $obj->name = $NameOfArtist;
        	   $obj->alias = JApplication::stringURLSafe($NameOfArtist);
	        $db->insertObject('#__jpartists', $obj);
        }

        /*
         * Functions for Album table
         */
        public function check_album($NameOfAlbum)
        {
        	$db = JFactory::getDBO();
            $query = $db->getQuery(true);
    	    $query->select('COUNT(*) as counter');
            $query->from('#__jpalbums');
            $query->where('title = "'.$NameOfAlbum.'"');

            $db->setQuery($query);
            $result = $db->loadObject();

            return $result->counter;
        }

        public function save_new_album($id3tags, $catid)
        {
        	$db = JFactory::getDBO();
            $row =& JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

        	   $obj = new stdClass();
        	   $obj->id = null;
        	   $obj->catid = $catid;
        	   $obj->title = $id3tags['album'];
        	   $obj->artist = $id3tags['artist'];
        	   $obj->alias = JApplication::stringURLSafe($id3tags['album']);
	        $db->insertObject('#__jpalbums', $obj);
        }
        /*
         * Functions for album cover
         */
        public function check_cover($cover_md5)
        {
        	$db = JFactory::getDBO();
            $query = $db->getQuery(true);
    	    $query->select('COUNT(*) as counter');
            $query->from('#__jpcoverblobs');
            $query->where('md5 = "'.$cover_md5.'"');

            $db->setQuery($query);
            $result = $db->loadObject();

            return $result->counter;
        }

        public function save_new_albumcover($id3tags, $ThisFileInfo)
        {
        	$db = JFactory::getDBO();
            $row =& JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

        	   $obj = new stdClass();
        	   $obj->id = null;
        	   $obj->artist = $id3tags['artist'];
        	   $obj->album = $id3tags['album'];
        	   $obj->md5 = md5($ThisFileInfo['id3v2']['APIC'][0]['data']);
        	   $obj->width = $ThisFileInfo['id3v2']['APIC'][0]['image_width'];
        	   $obj->height = $ThisFileInfo['id3v2']['APIC'][0]['image_height'];
        	   $obj->data = $ThisFileInfo['id3v2']['APIC'][0]['data'];
        	   $obj->mime = $ThisFileInfo['id3v2']['APIC'][0]['mime'];

	        $db->insertObject('#__jpcoverblobs', $obj);
        }

        public function save_new_track($id3tags, $catid, $DirectoryToScan, $ThisFileInfo, $md5hash_value) {

        	$dispatcher	= JDispatcher::getInstance();

        	if (strlen($DirectoryToScan) <= 255) {

        		//Get user id
        		$user	= JFactory::getUser();
                $userId	= $user->get('id');

                //For getting the xml parameters
                $params =  JComponentHelper::getParams('com_playjoom');

        		// Get UTC datetime for now.
		        $dNow = new JDate;
		        $DateTime = clone $dNow;

        	    $db = JFactory::getDBO();

        	          $obj = new stdClass();
        	          $obj->id = null;
        	          $obj->md5 = $md5hash_value;
        	          $obj->access = $params->get('pj_pre_accesslevel');
        	          $obj->pathatlocal = $DirectoryToScan;
        	          $obj->file = $ThisFileInfo['filename'];
				      $obj->title = $id3tags['title'];
				      $obj->alias = $id3tags['alias'];
				      $obj->tracknumber = $id3tags['number'];
				      $obj->mediatype = $id3tags['mime'];
				      $obj->bit_rate = $id3tags['bitrate'];
			          $obj->sample_rate = $id3tags['samplerate'];
				      $obj->channels = $id3tags['channels'];;
				      $obj->channelmode = $id3tags['channelmode'];;
				      $obj->filesize = $id3tags['filesize'];
				      $obj->length = $id3tags['length'];
				      $obj->catid = $catid;
				      $obj->add_datetime = $DateTime->format('Y-m-d H:i:s');
				      $obj->add_by = $userId;
				      $obj->artist = $id3tags['artist'];
				      $obj->album = $id3tags['album'];
				      $obj->year = $id3tags['year'];

			    $db->insertObject('#__jpaudiotracks', $obj);

			    $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Insert track complete for file: '.$DirectoryToScan.'/'.$ThisFileInfo['filename'], 'priority' => JLog::INFO, 'section' => 'site')));

        	}
        	else {
        		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Path to file with '.strlen($DirectoryToScan).' characters to long. Allows are only 255 characters.', 'priority' => JLog::ERROR, 'section' => 'site')));
        	}
        }
}
