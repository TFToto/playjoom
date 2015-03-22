<?php
/**
 * Contains the module Methods for the PlayJoom adminplaylist
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
 * Contains the module Methods for the PlayJoom adminplaylist
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.models
 */
class PlayJoomModelAdminPlaylist extends JModelList {

	/**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function populateState($ordering = 'ordering', $direction = 'ASC') {

		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		if (isset($_POST['track_id'])
	     && JRequest::getVar('action') == 'del')
	     {
		  PlayJoomModelAdminPlaylist::DeleteTrackFromPlaylist($_POST['track_id']);
		 }

		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$artist = $app->getUserStateFromRequest($this->context.'.filter.artist', 'filter_artist');
		$this->setState('filter.artist', $artist);

		$album = $app->getUserStateFromRequest($this->context.'.filter.album', 'filter_album', '');
		$this->setState('filter.album', $album);

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$year = $app->getUserStateFromRequest($this->context.'.filter.year', 'filter_year', '');
		$this->setState('filter.year', $year);

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
		$id	.= ':'.$this->getState('filter.artist');
		$id	.= ':'.$this->getState('filter.album');
		$id	.= ':'.$this->getState('filter.year');
		$id	.= ':'.$this->getState('filter.category_id');
		$id	.= ':'.$this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		//For getting the xml parameters
		$app = JFactory::getApplication();
        $params		= $app->getParams();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'p.user_id, p.track_id, p.lft, p.rgt, p.list_id, p.add_date')
		              );
		$query->from('#__jpplaylist_content AS p');

		// Join over the audiotracklist.
		$query->select('t.file, t.pathatlocal, t.id AS id, t.title AS title, t.artist AS artist, t.album AS album, t.length AS length');
		$query->join('LEFT', '#__jpaudiotracks AS t ON t.id = p.track_id');

		// Join over the categories.
		$query->select('c.title AS genre');
		$query->join('LEFT', '#__categories AS c ON c.id = t.catid');

		$query->where('p.list_id = "' . JRequest::getVar('listid') .'"');

		$query->order('p.id');
		return $query;
	}

    public function getTable($type = 'Category', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

    protected function DeleteTrackFromPlaylist($track_id)
	{

		$db = JFactory::getDBO();

    	// Delete playlist
		$query = $db->getQuery(true);
		$query->delete();
		$query->from('#__jpplaylist_content');
		$query->where('track_id = "'.$track_id. '"');
		$db->setQuery($query);

		// Check for a database error.
		if (!$this->_db->query()) {
			$e = new JException(JText::_('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);
			return false;
		}

		return true;

	}

	public function getPlaylistInfo() {

		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('id,catid,access,name,create_date,modifier_date,attach_artist,attach_genre');
		$query->from('#__jpplaylists');
		$query->where('id = "'.JRequest::getVar('listid'). '"');

		$db->setQuery($query);

		return $db->loadObject();

	}

}