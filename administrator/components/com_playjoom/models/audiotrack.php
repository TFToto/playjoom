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
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * PlayJoom Model
 */
class PlayJoomModelAudioTrack extends JModelAdmin {

    /**
	 * Method to perform batch operations on a track.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   0.9.5xx
	 */
	public function batch($commands, $pks, $contexts) {

		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks)) {
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		if (strlen($commands['user_id']) > 0) {
			if (!$this->batchUser($commands['user_id'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['category_id']) > 0) {
			if (!$this->batchGenre($commands['category_id'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['trackfilter_id']) > 0) {
			if (!$this->batchTrackfilter($commands['trackfilter_id'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['assetgroup_id']) > 0) {
			if (!$this->batchAccesslevel($commands['assetgroup_id'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['artist']) > 0) {
			if (!$this->batchArtist($commands['artist'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['album']) > 0) {
			if (!$this->batchAlbum($commands['album'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['year']) > 0) {
			if (!$this->batchYear($commands['year'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}

		if (strlen($commands['tag']) > 0) {
			if (!$this->batchTag($commands['tag'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}
//TODO Batch function for Tags
/*
		if (strlen($commands['id3tags']) == 1) {
			if (!$this->batchID3Tags($commands['id3tags'], $pks, $contexts)) {
				return false;
			}

			$done = true;
		}
*/
		if (!$done) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch to change the year of a track which was released.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchYear($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();
		$userId	= $user->get('id');
		$table = $this->getTable();

		// Get UTC datetime for now.
		$dNow = new JDate;
		$DateTime = clone $dNow;

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
					|| JAccess::check($user->get('id'), 'core.admin') == 1) {

				$table->reset();
				$table->load($pk);
				$table->year = $value;
				$table->mod_datetime = $DateTime->format('Y-m-d H:i:s');
				$table->mod_by = $userId;

				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				}
			}
			else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch to change an name of the album of a track.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchAlbum($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();
		$userId	= $user->get('id');
		$table = $this->getTable();

		// Get UTC datetime for now.
		$dNow = new JDate;
		$DateTime = clone $dNow;

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
					|| JAccess::check($user->get('id'), 'core.admin') == 1) {

				$table->reset();
				$table->load($pk);
				$table->album = $value;
				$table->mod_datetime = $DateTime->format('Y-m-d H:i:s');
				$table->mod_by = $userId;

				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				}
			}
			else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch to change an name of the artist of a track.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchArtist($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();
		$userId	= $user->get('id');
		$table = $this->getTable();
		$datetime = JFactory::getDate('now', null);

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
					|| JAccess::check($user->get('id'), 'core.admin') == 1) {

				$table->reset();
				$table->load($pk);
				$table->artist = $value;
				$table->mod_datetime = $datetime;
				$table->mod_by = $userId;

				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				}
			}
			else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch to change an owner of a track.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchUser($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();
		$userId	= $user->get('id');
		$table = $this->getTable();

		// Get UTC datetime for now.
		$dNow = new JDate;
		$DateTime = clone $dNow;

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
           	 || JAccess::check($user->get('id'), 'core.admin') == 1) {

				$table->reset();
				$table->load($pk);
				$table->add_by = (int) $value;
				$table->mod_datetime = $DateTime->format('Y-m-d H:i:s');
				$table->mod_by = $userId;

				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				}
			}
			else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}
	/**
	 * Batch to write ID3Tags into audio file.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchID3Tags($value, $pks, $contexts) {

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
			|| JAccess::check($user->get('id'), 'core.admin') == 1) {

			}
			else {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch to change the access level of a track.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchAccesslevel($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();
		$userId	= $user->get('id');
		$table = $this->getTable();
		$datetime = JFactory::getDate('now', null);

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
           	 || JAccess::check($user->get('id'), 'core.admin') == 1) {

				$table->reset();
				$table->load($pk);
				$table->access = (int) $value;
				$table->mod_datetime = $datetime;
				$table->mod_by = $userId;

				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch to change the genre of a track.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchGenre($value, $pks, $contexts) {
		// Set the variables
		$user = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();
		$userId	= $user->get('id');
		$table = $this->getTable();
		$datetime = JFactory::getDate('now', null);

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
           	 || JAccess::check($user->get('id'), 'core.admin') == 1) {

				$table->reset();
				$table->load($pk);
				$table->catid = (int) $value;
				$table->mod_datetime = $datetime;
				$table->mod_by = $userId;

				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch to change the filter of a track.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchTrackfilter($value, $pks, $contexts) {
		// Set the variables
		$user = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();
		$userId	= $user->get('id');
		$table = $this->getTable();
		$datetime = JFactory::getDate('now', null);

		foreach ($pks as $pk) {

			if ($canDo->get('core.edit')
					|| JAccess::check($user->get('id'), 'core.admin') == 1) {

				$table->reset();
				$table->load($pk);
				$table->trackfilterid = (int) $value;
				$table->mod_datetime = $datetime;
				$table->mod_by = $userId;

				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}
	/**
	 * Batch to change the tags of a track.
	 *
	 * @param   integer  $value     The new value matching a User ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   0.9.5xx
	 */
	protected function batchTag($value, $pks, $contexts) {
		// Set the variables
		$user = JFactory::getUser();
		$table = $this->getTable();
		$dispatcher = JEventDispatcher::getInstance();

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Vaule content:.'.print_r($contexts, true), 'priority' => JLog::INFO, 'section' => 'admin')));

		foreach ($pks as $pk)
		{
			if ($user->authorise('core.edit', $contexts[$pk]))
			{
				$table->reset();
				$table->load($pk);
				$tags = array($value);

				//$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Vaule content:.'.print_r($table, true), 'priority' => JLog::INFO, 'section' => 'admin')));

				/**
				 * @var  JTableObserverTags  $tagsObserver
				*/
				$tagsObserver = $table->getObserverOfClass('JTableObserverTags');
				$result = $tagsObserver->setNewTags($tags, false);

				if (!$result)
				{
					$this->setError($table->getError());

					return false;
				}

				//self::setPublishStateForTags($table);
				//self::setTagTrackTitle($tags);
				//self::setTagCoreParams($tags);
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	* Method override to check if you can edit an existing record.
	*
	* @param       array   $data   An array of input data.
	* @param       string  $key    The name of the key for the primary key.
	*
	* @return      boolean
	* @since       1.6
	*/
	protected function allowEdit($data = array(), $key = 'id') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_playjoom.audiotrack.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	protected function canDelete($data = array(), $key = 'id') {
		$user = JFactory::getUser();
		return $user->authorise('core.delete', 'com_playjoom.audiotrack.'.(int) $data->id);
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
	public function getTable($type = 'AudioTrack', $prefix = 'PlayJoomTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	* Method to get a single record.
	*
	* @param   integer    The id of the primary key.
	*
	* @return  mixed  Object on success, false on failure.
	*/
	public function getItem($pk = null) {

		if ($item = parent::getItem($pk)) {
			if (!empty($item->id)) {
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_playjoom.audiotrack');
			}
		}

		return $item;
	}
	/**
	 * Method to store a row
	 *
	 * @param boolean $updateNulls True to update fields even if they are null.
	 */
	public function store($updateNulls = false) {

		$this->tagsHelper->preStoreProcess($this);
		$result = parent::store($updateNulls);

		return $result && $this->tagsHelper->postStoreProcess($this);
	}
	/**
	* Method to save the form data.
	*
	* @param   array    $data  The form data.
	*
	* @return  boolean  True on success.
	*
	* @since   1.6
	*/
	public function save($data) {

		if (parent::save($data)) {

			$table = $this->getTable();

			if ((!empty($data['tags']) && $data['tags'][0] != '')) {
				$table->newTags = $data['tags'];
				self::setPublishStateForTags($data);
				self::setTagTrackTitle($data);
				self::setTagCoreParams($data);
			}

			$asset = JTable::getInstance('Asset');
			$asset->title = 'tee';

			self::writeID3Tags($data);

			return true;
		}

		return false;
	}
	/**
	 * Method for to write ID3Tags into file
	 *
	 * @param array $data ID3 tag items
	 * @param string Kind of text encoding, for example UTF-8
	 * @return boolean
	 */
	public function writeID3Tags($data, $TextEncoding = 'UTF-8') {

		$dispatcher	= JDispatcher::getInstance();

		require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'id3'.DIRECTORY_SEPARATOR.'getid3.php');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'id3'.DIRECTORY_SEPARATOR.'write.php');

		// Initialize getID3 engine
		$getID3 = new getID3;
		$getID3->setOption(array('encoding'=>$TextEncoding));

		$tagwriter = new getid3_writetags;
		$tagwriter->tagformats = array('id3v2.3');

		// Set various options
		$tagwriter->overwrite_tags = true;
		$tagwriter->tag_encoding = $TextEncoding;
		$tagwriter->remove_other_tags = true;

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Writing tags for: '.$data['pathatlocal'].'/'.$data['file'], 'priority' => JLog::INFO, 'section' => 'admin')));

		// Data for ID3 Tag
		$tagwriter->filename = $data['pathatlocal'].'/'.$data['file'];

		// Get name for Genre ID
		if ($data['catid']) {
			$genre_name =  self::getGenrename($data['catid'])->title;
		} else {
			$genre_name = null;
		}
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Name for Genre id: '.$data['catid'].' is '.$genre_name, 'priority' => JLog::INFO, 'section' => 'admin')));

		// Create data array for ID3 tag
		$TagData = array(
			'title' => array($data['title']),
			'artist' => array($data['artist']),
			'album' => array($data['album']),
			'year' => array($data['year']),
			'genre' => array($genre_name),
			'comment' => array('Edited by PlayJoom streaming server'),
			'track' => array($data['tracknumber'])
		);
		$tagwriter->tag_data = $TagData;

		// write ID3 tag
		if ($tagwriter->WriteTags()) {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Writting of ID3 Tag successfully.', 'priority' => JLog::INFO, 'section' => 'admin')));
			if (!empty($tagwriter->warnings)) {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Attention! '.implode('<br>', $tagwriter->warnings), 'priority' => JLog::WARNING, 'section' => 'admin')));
				return false;
			}
		} else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Failed to write tags! '.implode('<br>', $tagwriter->errors), 'priority' => JLog::WARNING, 'section' => 'admin')));
			return true;
		}
	}
	/**
	 * Method to get the name of a category id
	 *
	 * @param	int	$cat_id ID of category
	 *
	 * @return	object
	 */
	public function getGenrename($cat_id) {

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('c.id, c.title');
		$query->from('#__categories AS c');
		$query->where('c.id = '.$cat_id);

		// Setup the query
		$db->setQuery($query->__toString());

		// Return the result
		return $db->loadObject();
	}
	/**
	 * Method for to set title extendend title value
	 *
	 *
	 */
	public function setTagTrackTitle($data) {

		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get ucm_content column for track IDs: '.$data[id], 'priority' => JLog::INFO, 'section' => 'admin')));

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
		->select($db->quoteName('core_content_id'))
		->from($db->quoteName('#__ucm_content'))
		->where($db->quoteName('core_type_alias') . ' = ' . $db->quote('com_playjoom.audiotrack'))
		->where($db->quoteName('core_content_item_id') . ' IN (' . $data[id] .')');
		$db->setQuery($query);
		$ccIds = $db->loadColumn();

		self::change($ccIds, $data);

		return true;
	}
	/**
	 * Method for to set the core params values
	 *
	 *
	 */
	public function setTagCoreParams($data) {

		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get ucm_content column for track IDs: '.$data[id], 'priority' => JLog::INFO, 'section' => 'admin')));

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
		->select($db->quoteName('core_content_id'))
		->from($db->quoteName('#__ucm_content'))
		->where($db->quoteName('core_type_alias') . ' = ' . $db->quote('com_playjoom.audiotrack'))
		->where($db->quoteName('core_content_item_id') . ' IN (' . $data[id] .')');
		$db->setQuery($query);
		$ccIds = $db->loadColumn();

		self::changeParams($ccIds, $data);

		return true;
	}
	/**
	 * Method to update the title and alias items for a row or list of rows in the database table.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 *
	 * @return  boolean  True on success.
	 *
	 */
	public function change($pks = null, $data) {

		$dispatcher = JEventDispatcher::getInstance();

		JArrayHelper::toInteger($pks);

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			} else {
				// Nothing to change, return false.
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		$pksImploded = implode(',', $pks);

		// Get the JDatabaseQuery object
		$query = $this->_db->getQuery(true);

		// Update the title and alias items for rows with the given primary keys.
		$query->update($this->_db->quoteName('#__ucm_content'))
		->set($this->_db->quoteName('core_title') . ' = \'' . $data['artist'].' - '.$data['title'].' ('.$data['album'].')\'')
		->set($this->_db->quoteName('core_alias') . ' = \'' . JApplication::stringURLSafe($data['artist']).'-'.JApplication::stringURLSafe($data['title']).'-'.JApplication::stringURLSafe($data['album']).'\'')
		->where($this->_db->quoteName('core_content_id') . ' = ' . $pksImploded );

		$this->_db->setQuery($query);

		try {
			$this->_db->execute();
		} catch (RuntimeException $e) {
			$this->setError($e->getMessage());
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => $e->getMessage(), 'priority' => JLog::ERROR, 'section' => 'admin')));

			return false;
		}

		$this->setError('');

		return true;
	}
	/**
	 * Method to update the title and alias items for a row or list of rows in the database table.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 *
	 * @return  boolean  True on success.
	 *
	 */
	public function changeParams($pks = null, $data) {

		$dispatcher = JEventDispatcher::getInstance();

		JArrayHelper::toInteger($pks);

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			} else {
				// Nothing to change, return false.
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		$pksImploded = implode(',', $pks);

		//Create params array
		$CoreParams = array(
						'artist' => $data['artist'],
						'title' => $data['title'],
						'album' => $data['album'],
						'length' => $data['length'],
						'pathatlocal' => $data['pathatlocal'],
						'file' => $data['file'],
						'year' => $data['year'],
						'mediatype' => $data['mediatype'],
						'catid' => $data['catid']
							);
		$ParamsString = json_encode($CoreParams, JSON_FORCE_OBJECT);

		// Get the JDatabaseQuery object
		$query = $this->_db->getQuery(true);

		// Update the title and alias items for rows with the given primary keys.
		$query->update($this->_db->quoteName('#__ucm_content'))
		->set($this->_db->quoteName('core_params') . ' = \''.$this->_db->escape($ParamsString).'\'')
		->where($this->_db->quoteName('core_content_id') . ' = ' . $pksImploded );

		$this->_db->setQuery($query);

		try {
			$this->_db->execute();
		} catch (RuntimeException $e) {
			$this->setError($e->getMessage());
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => $e->getMessage(), 'priority' => JLog::ERROR, 'section' => 'admin')));

			return false;
		}

		$this->setError('');

		return true;
	}
	/**
	* Method to change the published state of one or more records.
	*
	* @param   array    &$pks   A list of the primary keys to change.
	* @param   integer  $value  The value of the published state. [optional]
	*
	* @return  boolean  True on success.
	*
	* @since   2.5
	*/
	public function setPublishStateForTags($pks, $value = 1) {

		$dispatcher = JEventDispatcher::getInstance();
		$user = JFactory::getUser();
		$table = $this->getTable();
		//$pks = (array) $pks;
		$pks[id] = (array) $pks[id];

		// Include the content plugins for the change of state event.
		JPluginHelper::importPlugin('content');

		// Access checks.
		foreach ($pks['tags'] as $i => $pk) {

			$table->reset();

			if ($table->load($pk)) {
				if (!$this->canEditState($table)) {
					// Prune items that you can't change.
					unset($pks[$i]);
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
					return false;
				}
			}
		}

		// Attempt to change the state of the records.
		if (!$table->publish($pks['tags'], $value, $user->get('id'))) {
			$this->setError($table->getError());
			return false;
		}

		// Trigger the onContentChangeState event.
		$result = $dispatcher->trigger('onContentChangeState', array('com_playjoom.audiotrack', $pks[id], $value));

		if (in_array(false, $result, true)) {
			$this->setError($table->getError());
			return false;
		}

		// Clear the component's cache
		$this->cleanCache();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Set of publish state done.', 'priority' => JLog::INFO, 'section' => 'admin')));

		return true;
	}
	/**
	* Method to get the record form.
	*
	* @param       array   $data           Data for the form.
	* @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
	* @return      mixed   A JForm object on success, false on failure
	* @since       1.6
	*/
	public function getForm($data = array(), $loadData = true) {

		// Get the form.
		$form = $this->loadForm('com_playjoom.audiotrack', 'audiotrack', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
 			return false;
		}
		return $form;
	}
	/**
	* Method to get the script that have to be included on the form
	*
	* @return string       Script files
	*/
	public function getScript() {
		return 'administrator/components/com_playjoom/models/forms/playjoom.js';
	}
	/**
	* Method to get the data that should be injected in the form.
	*
	* @return      mixed   The data for the form.
	* @since       1.6
	*/
	protected function loadFormData() {

        	//Get user id
        	$user	= JFactory::getUser();
            $userId	= $user->get('id');

        	// Get UTC datetime for now.
		    $dNow = new JDate;
		    $DateTime = clone $dNow;

            // Check the session for previously entered form data.
		    $app  = JFactory::getApplication();
		    $data = $app->getUserState('com_playjoom.edit.audiotrack.data', array());

            $item	= $this->getItem();

            if (empty($data)) {
            	//Get data from form
                $data = $this->getItem();
            }

            if ($item->id != ''
            	&& $userId != '') {
              	//Add modified datas
            	$data->set('mod_by', $userId);
            	$data->set('mod_datetime', $DateTime->format('Y-m-d H:i:s'));
            } else {
            	$data->set('add_by', $userId);
            	$data->set('add_datetime', $DateTime->format('Y-m-d H:i:s'));
            }

		return $data;

	}
	/**
	 * Method to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import.
	 *
	 * @return  void
	 *
	 * @see     JFormField
	 * @since   1.6
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'playjoom')
	{
		jimport('joomla.filesystem.path');

		// Set the access control rules field component value.
		$form->setFieldAttribute('rules', 'component', 'com_playjoom');
		$form->setFieldAttribute('rules', 'section', 'audiotrack');

		// Trigger the default form events.
		parent::preprocessForm($form, $data, $group);
	}
}
