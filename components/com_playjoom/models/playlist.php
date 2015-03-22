<?php
/**
 * Contains the model methods for to get the content of a playlist in PlayJoom frontend.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Site
 * @subpackage models.playlist
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
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * PlayJoom Model
 */
class PlayJoomModelPlaylist extends JModelList {

   /**
    * Method to build an SQL query to load the list data.
    *
    * @return      string  An SQL query
    */
	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication();

	}

   /**
    * Get the message
    * @return object The message to be displayed to the user
    */
    protected function getListQuery() {

		$dispatcher	= JDispatcher::getInstance();

		//Get User objects
		$user	= JFactory::getUser();

		//For getting the xml parameters
		$app = JFactory::getApplication();
        $params		= $app->getParams();

        $item_source = JRequest::getVar('source');

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get a sql query for selected source: '.$item_source, 'priority' => JLog::INFO, 'section' => 'site')));

		$ordering = JRequest::getVar('orderplaylist','a.tracknumber,a.year');
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Ordering for playlist is: '.$ordering, 'priority' => JLog::INFO, 'section' => 'site')));

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get sql query for item source: '.$item_source, 'priority' => JLog::INFO, 'section' => 'site')));

		switch($item_source) {

			case "album" :

	    		$query->select($this->getState('list.select', 'a.id, a.title, a.artist, a.album, a.length, a.tracknumber, a.hits, a.mediatype, a.pathatlocal, a.file'));
		        $query->from('#__jpaudiotracks AS a');

		        $query->where('a.album = "'.base64_decode(JRequest::getVar('name')).'" AND a.artist = "'.base64_decode(JRequest::getVar('artist')).'"');

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
		        			$query->where('a.add_by >= 1');
		        		}

		        		if ($params->get('show_nobody', 1)) {
		        			$users = '0,'.$users;
		        			$query->where('a.add_by IN ('.$users.')');
		        		}
		        	}
		        	elseif ($userCheck == 0) {
		        		$query->where('a.add_by = '.$users.'');
		        	}
		        }

		        $query->order($ordering);

		        return $query;

	        break;

	        case "artist" :

	    		$query->select($this->getState('list.select','a.id, a.title, a.artist, a.album, a.length, a.tracknumber, a.year, a.pathatlocal, a.file'));
		        $query->from('#__jpaudiotracks AS a');

		        $query->where('a.artist = "'.base64_decode(JRequest::getVar('artist')).'"');

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
		        			$query->where('a.add_by >= 1');
		        		}

		        		if ($params->get('show_nobody', 1)) {
		        			$users = '0,'.$users;
		        			$query->where('a.add_by IN ('.$users.')');
		        		}
		        	}
		        	elseif ($userCheck == 0) {
		        		$query->where('a.add_by = '.$users.'');
		        	}
		        }

		        //Filtering by trackfilter
		        if (JRequest::getVar('trackfilterid')) {
		        	$query->where('a.trackfilterid = "'.(JRequest::getVar('trackfilterid')).'"');
		        }

		        $query->order($ordering);

		        return $query;

	        break;

			case "tag" :

				if (JRequest::getVar('listid')) {
					$query->select($this->getState('list.select','a.id, a.title, a.artist, a.album, a.length, a.tracknumber, a.year, a.pathatlocal, a.file'));
					$query->from('#__contentitem_tag_map AS ctm');
					$query->join('LEFT', '#__jpaudiotracks AS a ON a.id = ctm.content_item_id');
					$query->where('ctm.tag_id = "'.JRequest::getVar('listid').'"');

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
								$query->where('a.add_by >= 1');
							}

							if ($params->get('show_nobody', 1)) {
								$users = '0,'.$users;
								$query->where('a.add_by IN ('.$users.')');
							}
						} elseif ($userCheck == 0) {
							$query->where('a.add_by = '.$users.'');
						}
					}

					$query->order($ordering);

					return $query;
				} else {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No item id value.', 'priority' => JLog::ERROR, 'section' => 'site')));
					return null;
				}

			break;

	        case "playlist" :

	        	$query->select($this->getState('list.select','p.track_id, p.list_id'));
		        $query->from('#__jpplaylist_content AS p');

		        $query->select('a.id, a.title, a.artist, a.album, a.length, a.tracknumber, a.year, a.hits, a.pathatlocal, a.file');
                $query->join('LEFT', '#__jpaudiotracks AS a ON a.id = p.track_id');

		        $query->where('p.list_id = "'.JRequest::getVar('listid').'"');
		        $query->order($ordering);

		        return $query;

	        break;
	        case "genre" :

	    		$query->select($this->getState('list.select','a.id, a.title, a.artist, a.album, a.length, a.tracknumber, a.year, a.catid, a.pathatlocal, a.file'));
		        $query->from('#__jpaudiotracks AS a');

		        // Join over the categories.
		        $query->select('c.title AS category_title');
		        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		        $query->where('c.title = "'.base64_decode(JRequest::getVar('name')).'"');

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
		        			$query->where('a.add_by >= 1');
		        		}

		        		if ($params->get('show_nobody', 1)) {
		        			$users = '0,'.$users;
		        			$query->where('a.add_by IN ('.$users.')');
		        		}
		        	}
		        	elseif ($userCheck == 0) {
		        		$query->where('a.add_by = '.$users.'');
		        	}
		        }
		        $query->order($ordering);

		        $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Create Playlist with query: '.$query, 'priority' => JLog::INFO, 'section' => 'site')));

		        return $query;

	        break;

	        default :
	        	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Missing or wrong playlist item source: '.$item_source, 'priority' => JLog::ERROR, 'section' => 'site')));
	        	return null;
	    }
	}

}