<?php
/**
 * Contains the module Methods for the PlayJoom album
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

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * Contains the module Methods for the PlayJoom album
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.models
 */
class PlayJoomModelAlbum extends JModelList {

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function populateState ($ordering = null, $direction = null) {
		// Initialise variables.
	}
	/**
	* Get the message
	* @return object The message to be displayed to the user
	*/
	protected function getListQuery() {

		//Get User objects
		$user	= JFactory::getUser();

		//For getting the xml parameters
		$app    = JFactory::getApplication();
		$params	= $app->getParams();

		//Get url queries
		$album = base64_decode(JRequest::getVar('album'));
		$artist = base64_decode(JRequest::getVar('artist'));

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			      $this->getState('list.select','a.id, a.title, a.hits, a.artist, a.album, a.title, a.year, a.add_datetime, a.length, a.catid, a.alias,
			      		                         a.file, a.tracknumber, a.mediatype, a.filesize, a.filesize, a.pathatlocal, a.bit_rate, a.sample_rate,
			      		                         a.channelmode, a.channels, a.add_datetime, a.add_by, a.access, a.mod_datetime, a.mod_by, a.access_datetime'
			                     )
		                  );
		$query->from('#__jpaudiotracks AS a');

		// Join over the categories.
		$query->select('c.title AS category');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the covers.
		$query->select('cb.id AS cover_id');
		$query->join('LEFT', '#__jpcoverblobs AS cb ON cb.id = a.coverid');

		/*
		* Join over the users.
		*/
		$query->select('CASE WHEN a.add_by > \'0\' THEN ua.username END AS add_user');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.add_by');

		$query->select('CASE WHEN a.mod_by > \'0\' THEN um.username END AS mod_user');
		$query->join('LEFT', '#__users AS um ON um.id = a.mod_by');

		// Join on voting table
		$query->select('ROUND( v.rating_sum / v.rating_count ) AS rating, v.rating_count as rating_count');
		$query->join('LEFT', '#__jpaudiotracks_rating AS v ON a.id = v.track_id');

		//Check for albumname as sampler
		if (!PlayJoomHelper::checkForSampler($album, $artist)) {
			$query->where('(a.album = "'.$album. '" AND a.artist = "'.$artist. '")');
		}
		else {
			$query->where('a.album = "'.$album. '"');
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin')
			 && !$params->get('show_noauth', 1)) {

			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$groups = '0,'.$groups;
			$query->where('a.access IN ('.$groups.')');
		}

		//Filtering by user
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			//Get user id
			$users = $user->get('id');

			$userCheck = $params->get('show_all_users', 1);
			$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

			if ($userCheck == 1) {

				if ($params->get('show_all_users', 1)) {
					$query->where('add_by >= 1');
				}

				if ($params->get('show_nobody', 1)) {
					$users = '0,'.$users;
					$query->where('add_by IN ('.$users.')');
				}
			} elseif ($userCheck == 0) {
				$query->where('add_by = '.$users.'');
			}
		}

		$query->order('a.tracknumber');

		return $query;
	}


	static function getAlbumNavigator ($Album, $Artist, $Year, $Add, $Direction) {

	     	//Get User objects
		    $user	= JFactory::getUser();

		    //For getting the xml parameters
		    $app    = JFactory::getApplication();
		    $params	= $app->getParams();

		    if ($Direction == "past") {

		    	$db = JFactory::getDBO();
    	        $query = $db->getQuery(true);
                $query->select('at.album,at.year,at.artist');
                $query->from('#__jpaudiotracks as at');
                $query->group('at.year');
                $query->order('at.year desc, at.id');

        	    /*
        	     * query conditions
        	     */
        	    $query->where('(at.year <= "'.$Year. '")');
        	    $query->where('(at.artist = "'.$Artist. '")');
        	    $query->where('(at.album <>  "'.$Album. '")');

        	// Implement View Level Access
        	if (!$user->authorise('core.admin')
        			&& !$params->get('show_noauth', 1)) {

        		$groups	= implode(',', $user->getAuthorisedViewLevels());
        		$groups = '0,'.$groups;
        		$query->where('at.access IN ('.$groups.')');
        	}

        	//Filtering by user
        	if (JAccess::check($user->get('id'), 'core.admin') != 1) {

        		//Get user id
        		$users = $user->get('id');

        		$userCheck = $params->get('show_all_users', 1);
        		$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

        		if ($userCheck == 1) {

        			if ($params->get('show_all_users', 1)) {
        				$query->where('at.add_by >= 1');
        			}

        			if ($params->get('show_nobody', 1)) {
        				$users = '0,'.$users;
        				$query->where('at.add_by IN ('.$users.')');
        			}
        		}
        		elseif ($userCheck == 0) {
        			$query->where('at.add_by = '.$users.'');
        		}
        	}

        	$db->setQuery($query);
        	$row = $db->loadAssoc();

        	$AlbumName  = $row['album'];
        	$ArtistName = $row['artist'];

        	if ($AlbumName != ''
        	 && $ArtistName != '') {

        	 	$albumsting = base64_encode($AlbumName);
		        $artiststing = base64_encode($ArtistName);

		        //Create links
		        $albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');

		        return '<a href="'.$albumlink.'"><img src="'.JURI::base(true).'/components/com_playjoom/images/past.gif" data-src="http://'.$_SERVER['SERVER_NAME'].JURI::base(true).'/components/com_playjoom/images/past.gif" rel="Image" width="24" height="24" alt="Prev Album - '.$row['album'].'" title="'.$row['album'].'" class="album_nav_past"></a>';
        	}
        	else {
        		return null;
        	}
        }
	    elseif ($Direction == "future") {
	    	$db = JFactory::getDBO();
    	    $query = $db->getQuery(true);
            $query->select('at.id, at.album, at.year,at.artist, at.add_datetime');
            $query->from('#__jpaudiotracks as at');
            $query->group('at.year');
            $query->order('at.year asc');

            /*
             * query conditions
             */
        	$query->where('(at.year > "'.$Year. '")');
        	$query->where('(at.artist = "'.$Artist. '")');
        	$query->where('(at.album <>  "'.$Album. '")');

        	// Implement View Level Access
        	if (!$user->authorise('core.admin')
        			&& !$params->get('show_noauth', 1)) {

        		$groups	= implode(',', $user->getAuthorisedViewLevels());
        		$groups = '0,'.$groups;
        		$query->where('at.access IN ('.$groups.')');
        	}

        	//Filtering by user
        	if (JAccess::check($user->get('id'), 'core.admin') != 1) {

        		//Get user id
        		$users = $user->get('id');

        		$userCheck = $params->get('show_all_users', 1);
        		$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

        		if ($userCheck == 1) {

        			if ($params->get('show_all_users', 1)) {
        				$query->where('at.add_by >= 1');
        			}

        			if ($params->get('show_nobody', 1)) {
        				$users = '0,'.$users;
        				$query->where('at.add_by IN ('.$users.')');
        			}
        		}
        		elseif ($userCheck == 0) {
        			$query->where('at.add_by = '.$users.'');
        		}
        	}

        	$db->setQuery($query);
        	$row = $db->loadAssoc();

        	if ($row['album'] != ''
        	 && $row['artist'] != '') {

        	 	$albumsting = base64_encode($row['album']);
		        $artiststing = base64_encode($row['artist']);

		        //Create links
		        $albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');

		       return '<a href="'.$albumlink.'"><img src="'.JURI::base(true).'/components/com_playjoom/images/future.gif" data-src="http://'.$_SERVER['SERVER_NAME'].JURI::base(true).'/components/com_playjoom/images/future.gif" rel="Image" width="24" height="24" alt="Prev Album - '.$row['album'].'" title="'.$row['album'].'" class="album_nav_future"></a>';
        	}
        	else {
        		return null;
        	}
        }
    }

    public function storeVote($pk = 0, $rate = 0)
    {
        if ( $rate >= 1 && $rate <= 5 && $pk > 0 )
        {
            $userIP = $_SERVER['REMOTE_ADDR'];
            $db = $this->getDbo();

            $db->setQuery(
                    'SELECT *' .
                    ' FROM #__jpaudiotracks_rating' .
                    ' WHERE track_id = '.(int) $pk
            );

            $rating = $db->loadObject();

            if (!$rating)
            {
                // There are no ratings yet, so lets insert our rating
                $db->setQuery(
                        'INSERT INTO #__jpaudiotracks_rating ( track_id, lastip, rating_sum, rating_count )' .
                        ' VALUES ( '.(int) $pk.', '.$db->Quote($userIP).', '.(int) $rate.', 1 )'
                );

                if (!$db->query()) {
                        $this->setError($db->getErrorMsg());
                        return false;
                }
            } else {
                if ($userIP != ($rating->lastip))
                {
                    $db->setQuery(
                            'UPDATE #__jpaudiotracks_rating' .
                            ' SET rating_count = rating_count + 1, rating_sum = rating_sum + '.(int) $rate.', lastip = '.$db->Quote($userIP) .
                            ' WHERE track_id = '.(int) $pk
                    );
                    if (!$db->query()) {
                            $this->setError($db->getErrorMsg());
                            return false;
                    }
                } else {
                    return false;
                }
            }
            return true;
        }
        JError::raiseWarning( 'SOME_ERROR_CODE', 'Track Rating:: Invalid Rating:' .$rate, "JModelTrack::storeVote($rate)");
        return false;
    }

    /**
     * Method to get attached playlists data for the current artist
     *
     * @return	object
     * @since	0.9.450
     */
    public function getAttachplaylists() {

    	// Get user objects
    	$user = JFactory::getUser();

    	//For getting the xml parameters
    	$app    = JFactory::getApplication();
    	$params	= $app->getParams();

    	// Create a new query object.
        $db = JFactory::getDBO();
        $query	= $db->getQuery(true);

        $query->select('pl.id, pl.user_id, pl.access, pl.name, pl.attach_artist');

        //Artist filter
        $query->where('pl.attach_artist = "'.base64_decode(JRequest::getVar('artist')).'"');

        // Implement View Level Access
        if (!$user->authorise('core.admin')
        		&& !$params->get('show_noauth', 1)) {

        	$groups	= implode(',', $user->getAuthorisedViewLevels());
        	$groups = '0,'.$groups;
        	$query->where('pl.access IN ('.$groups.')');
        }

		$query->from('#__jpplaylists AS pl');


		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;

    }
}