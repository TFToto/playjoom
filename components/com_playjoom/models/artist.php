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
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
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
class PlayJoomModelArtist extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'artist', 'a.artist',
				'album', 'a.album',
				'year', 'a.year',
				'catid', 'a.catid', 'category_title',
			    'access', 'a.access', 'access_level',
				'add_datetime', 'a.add_datetime',
				'length', 'a.length',
				'alias', 'a.alias',
				'file', 'a.file',
				'tracknumber', 'a.tracknumber',
				'mediatype', 'a.mediatype',
				'filesize', 'a.filesize',
			);
		}

		parent::__construct($config);
	}
               /**
         * Method to build an SQL query to load the list data.
         *
         * @return      string  An SQL query
         */
    protected function populateState($ordering = null, $direction = null)	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();


		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);

		$artist = $app->getUserStateFromRequest($this->context.'.filter.artist', 'filter_artist');
		$this->setState('filter.artist', $artist);

		$album = $app->getUserStateFromRequest($this->context.'.filter.album', 'filter_album', '');
		$this->setState('filter.album', $album);

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$year = $app->getUserStateFromRequest($this->context.'.filter.year', 'filter_year', '');
		$this->setState('filter.year', $year);

		// Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        // process show_noauth parameter
        if (!$params->get('show_noauth', 1)) {
        	$this->setState('filter.access', true);
        }
        else {
        	$this->setState('filter.access', false);
        }

        // List state information.
        parent::populateState('a.year', 'asc','a.tracknumber', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.album');
		$id	.= ':'.$this->getState('filter.year');
		$id	.= ':'.$this->getState('filter.category_id');

		return parent::getStoreId($id);
	}

        /**
         * Returns a reference to the a Table object, always creating it.
         *
         * @param       type    The table type to instantiate
         * @param       string  A prefix for the table class name. Optional.
         * @param       array   Configuration array for model. Optional.
         * @return      JTable  A database object
         * @since       1.6
         */
        public function getTable($type = 'PlayJoom', $prefix = 'PlayJoomTable', $config = array())
        {
                return JTable::getInstance($type, $prefix, $config);
        }

        /**
         * Get the message
         * @return object The message to be displayed to the user
         */
    protected function getListQuery()
	{

		//For getting the xml parameters
		$app = JFactory::getApplication();
        $params		= $app->getParams();

        //Get User objects
        $user	= JFactory::getUser();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.artist, a.album, a.year, a.add_datetime, a.length, a.catid' .
			    ', a.alias, a.pathatlocal, a.file, a.tracknumber, a.mediatype, a.filesize, a.access'
			                )
		              );
		$query->from('#__jpaudiotracks AS a');

		// Join over the access level.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('c.title AS category');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		$query->where('a.artist = "' . base64_decode(JRequest::getVar('artist')) .'"');


		// Filter by Category.
		if ($category_id = $this->getState('filter.category_id')) {
			$query->where('a.catid = ' . $category_id);
		}

		// Filter by Album.
		if ($album = $this->getState('filter.album')) {
			$query->where('a.album = "' . $album .'"');
		}

		// Filter by Year.
		if ($year = $this->getState('filter.year')) {
			$query->where('a.year = ' . $year);
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


		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else if (stripos($search, 'author:') === 0) {
				$search = $db->Quote('%'.$db->getEscaped(substr($search, 7), true).'%');
				$query->where('(ua.name LIKE '.$search.' OR ua.username LIKE '.$search.')');
			}
			else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.title LIKE '.$search.' OR a.album LIKE '.$search.')');
			}
		}


		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		//Special ordering for the colum category
		if ($orderCol == 'a.ordering' || $orderCol == 'category') {
			$orderCol = 'category '.$orderDirn.', c.title';
		}

		// Add the list ordering clause.
		//$query->order($db->getEscaped($this->getState('list.ordering', 'a.year')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
		//$query->order($this->getState('list.ordering', 'a.ordering').' '.$this->getState('list.direction', 'ASC'));


		return $query;
	}

    public static function getArtistInfos($artist) {

    	$db = JFactory::getDBO();

    	$query = $db->getQuery(true);
        $query->select('id,name,formation,members,infotxt');
        $query->from('#__jpartists');
        $query->where('name = "'.$artist. '"');

        $db->setQuery($query);

        return $db->loadObject();

    }

    public static function getArtistCount($artist) {

    	$db = JFactory::getDBO();

    	$query = $db->getQuery(true);
        $query->select('COUNT(*) as counter');
        $query->from('#__jpartists');
        $query->where('name = "'.$artist. '"');

        $db->setQuery($query);

        return $db->loadObject();

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

    public static function getAlbumitems() {

    	//Get User objects
    	$user	= JFactory::getUser();

    	//For getting the xml parameters
    	$app = JFactory::getApplication();
    	$params		= $app->getParams();

    	$db		= JFactory::getDbo();
    	$query	= $db->getQuery(true);

    	$query->select('a.album, a.year, a.artist, a.catid');
    	$query->where('a.artist = "'.base64_decode(JRequest::getVar('artist')).'"');
    	$query->order('a.year');
    	$query->group('a.album');

    	// Join over the categories.
    	$query->select('c.title AS category_title');
    	$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

    	// Join over the covers.
    	$query->select('cb.id AS cover_id');
    	$query->join('LEFT', '#__jpcoverblobs AS cb ON cb.id = a.coverid');

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
    	$query->from('#__jpaudiotracks AS a');

    	// Get the options.
    	$db->setQuery($query);

    	$album_list = $db->loadObjectList();

    	// Check for a database error.
    	if ($db->getErrorNum()) {
    		JError::raiseWarning(500, $db->getErrorMsg());
    	}

    	return $album_list;
    }

    public function getTrackFilter() {

    	//Get User objects
    	$user	= JFactory::getUser();

    	//For getting the xml parameters
    	$app = JFactory::getApplication();
    	$params		= $app->getParams();

    	$db		= JFactory::getDbo();
    	$query	= $db->getQuery(true);

    	$query->select('tf.title, tf.id');
    	$query->where('tf.extension = "com_playjoom.trackfilter"');
    	$query->order('tf.lft');

    	$query->join('LEFT', '#__jpaudiotracks AS ti ON tf.id = ti.trackfilterid');
    	$query->group('ti.trackfilterid');

    	// Implement View Level Access
    	if (!$user->authorise('core.admin')
    			&& !$params->get('show_noauth', 1)) {

    		$groups	= implode(',', $user->getAuthorisedViewLevels());
    		$groups = '0,'.$groups;
    		$query->where('ti.access IN ('.$groups.')');
    	}

    	//Filtering by user
    	if (JAccess::check($user->get('id'), 'core.admin') != 1) {

    		//Get user id
    		$users = $user->get('id');

    		$userCheck = $params->get('show_all_users', 1);
    		$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

    		if ($userCheck == 1) {

    			if ($params->get('show_all_users', 1)) {
    				$query->where('ti.add_by >= 1');
    			}

    			if ($params->get('show_nobody', 1)) {
    				$users = '0,'.$users;
    				$query->where('ti.add_by IN ('.$users.')');
    			}
    		}
    		elseif ($userCheck == 0) {
    			$query->where('ti.add_by = '.$users.'');
    		}
    	}
    	$query->from('#__categories AS tf');

    	// Get the options.
    	$db->setQuery($query);

    	$album_list = $db->loadObjectList();

    	// Check for a database error.
    	if ($db->getErrorNum()) {
    		JError::raiseWarning(500, $db->getErrorMsg());
    	}

    	return $album_list;
    }

    public static function getFilteritems($trackfilterid) {

    	//Get User objects
    	$user	= JFactory::getUser();

    	//For getting the xml parameters
    	$app = JFactory::getApplication();
    	$params		= $app->getParams();

    	$db		= JFactory::getDbo();
    	$query	= $db->getQuery(true);

    	$query->select('a.album, a.year, a.artist, a.catid');
    	$query->where('a.artist = "'.base64_decode(JRequest::getVar('artist')).'" AND a.trackfilterid ='.$trackfilterid);
    	$query->order('a.year');
    	$query->group('a.album');

    	// Join over the categories.
    	$query->select('c.title AS category_title');
    	$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

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
    	$query->from('#__jpaudiotracks AS a');

    	// Get the options.
    	$db->setQuery($query);

    	$album_list = $db->loadObjectList();

    	// Check for a database error.
    	if ($db->getErrorNum()) {
    		JError::raiseWarning(500, $db->getErrorMsg());
    	}

    	return $album_list;
    }

}