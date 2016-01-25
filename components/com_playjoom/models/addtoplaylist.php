<?php
/**
 * Contains the module Methods for the PlayJoom addtoplaylist
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2015 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * Contains the module Methods for the PlayJoom addtoplaylist
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.models
 */
class PlayJoomModelAddtoplaylist extends JModelLegacy
{
    protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		//check for add track action
        if (isset($_POST['track_id'])
         && isset($_POST['list_id']))
		{
			PlayJoomModelAddtoplaylist::AddTrackToList($_POST['track_id'],$_POST['list_id']);
		}
	}


	public static function getPlaylists() {

		$user		= JFactory::getUser();
        $userId	= $user->get('id');

        $db		= JFactory::getDbo();

		$query	= $db->getQuery(true);
        $query->select('a.id As value, a.name As text');
		$query->order('a.create_date', 'DESC');

		$query->from('#__jpplaylists AS a');

		$query->where('user_id="'.$userId.'"');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		 }
		return $options;
	}

	public static function getTrackInfo($track_id) {

               if ($track_id != 0 || $track_id != '')
               {
               	   $db = JFactory::getDBO();
                   $query = $db->getQuery(true);
                   $query->select('id,title,artist');
                   $query->from('#__jpaudiotracks');
                   $query->where('id = "'.$track_id. '"');

                   $db->setQuery($query);
                   $result = $db->loadObject();

                   return $result->artist.' - '.$result->title;
               }

               else
               {
               	   return 0;
               }
        }

        protected function AddTrackToList($track_id, $list_id) {

        	$db = JFactory::getDBO();
            $user = JFactory::getUser();;
        	$user_id = $user->get('id');

        	// Get UTC datetime for now.
		    $dNow = new JDate;
		    $DateTime = clone $dNow;

            //Get database instance for write data
            $row = JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

        	  $obj = new stdClass();
        	  $obj->id = null;
        	  $obj->track_id = $track_id;
        	  $obj->list_id = $list_id;
        	  $obj->user_id = $user_id;
        	  $obj->add_date = $DateTime->format('Y-m-d H:i:s');;

			  $db->insertObject('#__jpplaylist_content', $obj);
        }
}