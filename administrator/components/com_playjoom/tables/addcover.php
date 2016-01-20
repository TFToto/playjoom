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

// No direct access
defined('_JEXEC') or die('Restricted access');

abstract class AddCoverTables
{

        /*
         * Functions for Artist table
         */
        public function check_artist($NameOfAlbum)
        {
          $db = JFactory::getDBO();

    	    $query = $db->getQuery(true);
    	    $query->select('COUNT(*) as counter');
            $query->select('album,artist');
            $query->from('#__jpaudiotracks');
            $query->where('album = "'.$NameOfAlbum. '"');
            $query->group('artist');

          $db->setQuery($query);

          $result = $db->loadObject();

		  if ($result->counter == 0) {
          	return 'unknown';
          }
          else {
          	return $result->artist;
          }
        }


        public function save_new_albumcover($albumname, $artist, $ThisFileInfo, $file_md5, $img_blob) {

        	$db = JFactory::getDBO();

        	   $obj = new stdClass();
        	   $obj->id = null;
        	   $obj->artist = $artist;
        	   $obj->album = $albumname;
        	   $obj->md5 = $file_md5;
        	   $obj->width = $ThisFileInfo['video']['resolution_x'];
        	   $obj->height = $ThisFileInfo['video']['resolution_y'];
        	   $obj->data = $img_blob;
        	   $obj->mime = $ThisFileInfo['mime_type'];

	        $db->insertObject('#__jpcoverblobs', $obj);

	  }
}
