<?php
/**
 * @package Joomla 3.2.x
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * PlayJoom List Model
 */
class PlayJoomModelCovers extends JModelList {

	public function __construct($config = array()) {

		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'album', 'a.album',
			    'artist', 'a.artist',
			    'data', 'a.data',
			);
		}

		parent::__construct($config);
	}
	/**
	* Method to build an SQL query to load the list data.
	*
	* @return      string  An SQL query
	*/
	protected function populateState($ordering = null, $direction = null) {

		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		$limit = $app->input->get('limit', 25, 'covers');
		$this->setState('limit', $limit);

		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$artist = $app->getUserStateFromRequest($this->context.'.filter.artist', 'filter_artist');
		$this->setState('filter.artist', $artist);

		$album = $app->getUserStateFromRequest($this->context.'.filter.album', 'filter_album');
		$this->setState('filter.album', $album);

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
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.album');
		$id	.= ':'.$this->getState('filter.artist');
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
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.album, a.artist, a.data, a.width, a.height, a.mime'
				)
			);
		$query->from('#__jpcoverblobs AS a');

		$query->where('a.data <> ""');
		// Filter by Artist / Band.
		if ($artist = $this->getState('filter.artist')) {
			$query->where('a.artist = "' . $artist .'"');
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else if (stripos($search, 'author:') === 0) {
				$search = $db->Quote('%'.$db->escape(substr($search, 7), true).'%');
				$query->where('(ua.album LIKE '.$search.')');
			}
			else {
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.album LIKE '.$search.')');
			}
		}


		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');


		// Add the list ordering clause.
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
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

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('artist As value, artist As text');
		$query->order('a.artist');
		$query->group('a.artist');

		// Filter by Artist / Band.
		if ($artist = $this->getState('filter.artist')) {
			$query->where('a.artist = "' . $artist .'"');
		}

		// Filter by Category.
		if ($category_id = $this->getState('filter.category_id')) {
			$query->where('a.catid = ' . $category_id);
		}

		// Filter by Category.
		if ($user_id = $this->getState('filter.user_id')) {
			$query->where('a.add_by = ' . $user_id);
		}

		// Filter by Album.
		if ($album = $this->getState('filter.album')) {
			$query->where('a.album = "' . $album .'"');
		}

		// Filter by Year.
		if ($year = $this->getState('filter.year')) {
			$query->where('a.year = ' . $year);
		}

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