<?php
/**
 * Contains the model methods for to get the data for audiotracks in PlayJoom backend.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage models.audiotracks
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * This models supports retrieving lists of PlayJoom artists.
 *
 * @package PlayJoom.Admin
 * @subpackage models.audiotracks
 * @since		0.9.460
 */
class PlayJoomModelAudioTracks extends JModelList {

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {

		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'album', 'a.album',
			    'artist', 'a.artist',
			    'title', 'a.title',
			    'year', 'a.year',
			    'tracknumber', 'a.tracknumber',
			    'catid', 'a.catid', 'category',
				'access', 'a.access', 'access_level',
			    'addby', 'a.add_by', 'user',
			    'add_datetime', 'a.add_datetime',
			);
		}

		parent::__construct($config);
	}

    /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
    protected function populateState($ordering = null, $direction = null) {

		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context.'.filter.access_id', 'filter_access_id', 0, 'int');
		$this->setState('filter.access_id', $access);

		$artist = $app->getUserStateFromRequest($this->context.'.filter.artist', 'filter_artist');
		$this->setState('filter.artist', $artist);

		$album = $app->getUserStateFromRequest($this->context.'.filter.album', 'filter_album', '');
		$this->setState('.filter.album', $album);

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$userId = $app->getUserStateFromRequest($this->context.'.filter.user_id', 'filter_user_id');
		$this->setState('filter.user_id', $userId);

		$year = $app->getUserStateFromRequest($this->context.'.filter.year', 'filter_year', '');
		$this->setState('filter.year', $year);

		// List state information.
		parent::populateState('a.id', 'desc');
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
	protected function getStoreId($id = '') {
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.artist');
		$id	.= ':'.$this->getState('filter.album');
		$id	.= ':'.$this->getState('filter.year');
		$id	.= ':'.$this->getState('filter.category_id');
		$id	.= ':'.$this->getState('filter.user_id');

		return parent::getStoreId($id);
	}

	/**
	 * Get the master query for retrieving a list of audiotracks subject to the model state.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery() {

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user	= JFactory::getUser();

		// Select the required fields from the table.
		$query->select($this->getState('list.select','a.id, a.title, a.artist, a.album, a.year, a.tracknumber, a.catid, a.access, a.add_by, a.add_datetime, a.file, a.pathatlocal'));
		$query->from('#__jpaudiotracks AS a');

		// Join over the categories.
		$query->select('c.title AS category');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the users.
		$query->select('u.username AS user');
		$query->join('LEFT', '#__users AS u ON u.id = a.add_by');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		self::setFilters($query, $db);

		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$users = $user->get('id');
			$query->where('add_by = '.$users);
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
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.title LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		//Special ordering for the colum category
		if ($orderCol == 'a.ordering' || $orderCol == 'category') {
			$orderCol = 'category '.$orderDirn.', c.title';
		}

		//Special ordering for the colum user
		elseif ($orderCol == 'a.ordering' || $orderCol == 'user') {
			$orderCol = 'user '.$orderDirn.', u.username';
		}

		//Special ordering for the colum access
		elseif ($orderCol == 'a.ordering' || $orderCol == 'access') {
			$orderCol = 'access '.$orderDirn.', ag.title';
		}

		// Add the list ordering clause.
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	/**
	 * Method for the create where commands for filter function
	 *
	 * @param unknown $query
	 * @param unknown $db
	 * @return string where command for audiotrack list filtering
	 */
	public function setFilters($query, $db) {

		$app = JFactory::getApplication();
		$user	= JFactory::getUser();

		// Filter by Artist / Band.
		if ($artist = $app->getUserStateFromRequest('filter.artist', 'filter_artist')) {
			$query->where('a.artist = "' . $db->escape($artist) .'"');
		}

		// Filter by Category.
		if ($category_id = $app->getUserStateFromRequest('filter.category_id', 'filter_category_id')) {
			$query->where('a.catid = ' . (int)$category_id);
		}

		// Filter by Category.
		if ($access_id = $app->getUserStateFromRequest('filter.access_id', 'filter_access_id')) {
			$query->where('a.access = ' . (int)$access_id);
		}

		// Filter by User.
		if ($user_id = $app->getUserStateFromRequest('filter.user_id', 'filter_user_id')) {
			$query->where('a.add_by = ' . (int)$user_id);
		}

		// Filter by Album.
		if ($album = $app->getUserStateFromRequest('filter.album', 'filter_album')) {
			$query->where('a.album = "' . $db->escape($album) .'"');
		}

		// Filter by Year.
		if ($year = $app->getUserStateFromRequest('filter.year', 'filter_year')) {
			$query->where('a.year = ' . (int)$year);
		}

		// Filter by User.
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$query->where('a.add_by = '.$user->get('id'));
		}

		return $query;
	}
	/**
	 * Returns a JTable object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate. [optional]
	 * @param   string  $prefix  A prefix for the table class name. [optional]
	 * @param   array   $config  Configuration array for model. [optional]
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
    public function getTable($type = 'Category', $prefix = 'JTable', $config = array())	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method for to get a list of valid artists for the filter menu
	 *
	 * @return  JTable  A database object
	 * @since   0.9.460
	 */
	public function getFilterOptionsArtists() {

		//Get User Objects
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('artist As value, artist As text');
		$query->order('a.artist');
		$query->group('a.artist');

		self::setFilters($query, $db);

		// Filter by User.
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$query->where('a.add_by = '.$user->get('id'));
		}

		$query->from('#__jpaudiotracks AS a');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	/**
	 * Method for to get a list of valid albums for the filter menu
	 *
	 * @return  JTable  A database object
	 * @since   0.9.460
	 */
	public function getFilterOptionsAlbums() {

		//Get User Objects
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('album As value, album As text');
		$query->order('a.album');
		$query->group('a.album');

		self::setFilters($query, $db);

		// Filter by User.
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$query->where('a.add_by = '.$user->get('id'));
		}

		$query->from('#__jpaudiotracks AS a');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	/**
	 * Method for to get a list of valid years for the filter menu
	 *
	 * @return  JTable  A database object
	 * @since   0.9.460
	 */
	public function getFilterOptionsYears() {

		//Get User Objects
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('year As value, year As text');
		$query->order('a.year DESC');
		$query->group('a.year DESC');

		self::setFilters($query, $db);

		// Filter by User.
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$query->where('a.add_by = '.$user->get('id'));
		}

		$query->from('#__jpaudiotracks AS a');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	/**
	 * Method for to get a list of valid genres for the filter menu
	 *
	 * @return  JTable  A database object
	 * @since   0.9.460
	 */
	public function getFilterOptionsGenres() {

		//Get User Objects
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('catid As value');
		$query->group('a.catid');

		// Join over the categories.
		$query->select('c.title AS text');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		self::setFilters($query, $db);

		// Filter by User.
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$query->where('a.add_by = '.$user->get('id'));
		}

		$query->from('#__jpaudiotracks AS a');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	/**
	 * Method for to get a list of valid access items for the filter menu
	 *
	 * @return  JTable  A database object
	 * @since   0.9.916
	 */
	public function getFilterOptionsAccess() {

		//Get User Objects
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('access As value');
		$query->group('a.access');

		// Join over the categories.
		$query->select('vl.title AS text');
		$query->join('LEFT', '#__viewlevels AS vl ON vl.id = a.access');

		self::setFilters($query, $db);

		// Filter by User.
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$query->where('a.add_by = '.$user->get('id'));
		}

		$query->from('#__jpaudiotracks AS a');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	/**
	 * Method for to get a list of valid owners for the filter menu
	 *
	 * @return  JTable  A database object
	 * @since   0.9.916
	 */
	public function getFilterOptionsUser() {

		//Get User Objects
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('a.add_by As value');
		$query->group('a.add_by');

		// Join over the categories.
		$query->select('us.username AS text');
		$query->join('LEFT', '#__users AS us ON us.id = a.add_by');

		self::setFilters($query, $db);

		// Filter by User.
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			$query->where('a.add_by = '.$user->get('id'));
		}

		$query->from('#__jpaudiotracks AS a');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
}