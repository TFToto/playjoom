<?php
/**
 * Contains the module Methods for the PlayJoom adminplaylists
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
 * Contains the module Methods for the PlayJoom adminplaylists
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.models
 */
class PlayJoomModelAdminPlaylists extends JModelList {

	protected function populateState($ordering = null, $direction = null) {

	// Initialise variables.
	$app = JFactory::getApplication();
	$session = JFactory::getSession();

	//check for add track action
	if (isset($_POST['new_playlist_name'])
	 && isset($_POST['user_id'])
	 && JRequest::getVar('action') == 'add')
		{
		 PlayJoomModelAdminPlaylists::AddPlaylist($_POST['new_playlist_name'],
		 		                                  $_POST['user_id'],
		 		                                  $_POST['category_id'],
		 		                                  $_POST['access'],
		 		                                  $_POST['attach_artist'],
		 		                                  $_POST['attach_genre']);
		}

	else if (isset($_POST['new_playlist_name'])
	 && isset($_POST['list_id'])
	 && JRequest::getVar('action') == 'edit')
		{
		 PlayJoomModelAdminPlaylists::EditPlaylist($_POST['new_playlist_name'],
		 		                                   $_POST['list_id'],
		 		                                   $_POST['category_id'],
		 		                                   $_POST['access'],
		 		                                   $_POST['attach_artist'],
		 		                                   $_POST['attach_genre']);
		}

	else if (isset($_POST['list_id'])
	 && JRequest::getVar('action') == 'del')
		{
		 PlayJoomModelAdminPlaylists::DeletePlaylist($_POST['list_id']);
		}

	// List state information.
	parent::populateState('l.name', 'asc');
	}

    protected function getListQuery()
    {
        $user		= JFactory::getUser();
        $userId	= $user->get('id');

    	// Create a new query object.
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        $query->select(
			$this->getState(
				            'list.select',
				            'l.id, l.catid, l.access, l.name, l.create_date, l.modifier_date')
		              );
		$query->from('#__jpplaylists AS l');
		$query->where('user_id ="'.$userId.'"');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = l.catid');

		// Join over the accesslevels.
		$query->select('al.title AS access_title');
		$query->join('LEFT', '#__viewlevels AS al ON al.id = l.access');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		// Add the list ordering clause.
		//$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		//$query->order($db->getEscaped($this->getState('list.ordering', 'l.id')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
		$query->order($this->getState('list.ordering', 'a.ordering').' '.$this->getState('list.direction', 'ASC'));

        return $query;
     }

     public function getPlaylistInfo() {

     	$db = JFactory::getDBO();

     	$query = $db->getQuery(true);
     	$query->select('id,catid,access,name,create_date,modifier_date,attach_artist,attach_genre');
     	$query->from('#__jpplaylists');
     	$query->where('id = "'.JRequest::getVar('id'). '"');

     	$db->setQuery($query);

     	return $db->loadObject();

     }

	protected function AddPlaylist($playlist_name, $user_id, $CatID=0, $Access=0, $AttachArtist=null, $AttachGenre=null)
	{

		$dispatcher	= JDispatcher::getInstance();

		// Get UTC datetime for now.
		$dNow = new JDate;
		$DateTime = clone $dNow;

		$db = JFactory::getDBO();

        $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Create datetime with $DateTime='.$DateTime, 'priority' => JLog::INFO, 'section' => 'site')));

        //Get database instance for write data
        $row =& JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

        $obj = new stdClass();
        $obj->id = null;
        $obj->user_id = $user_id;
        $obj->access = $Access;
        $obj->catid = $CatID;
        $obj->name = $playlist_name;
        $obj->create_date = $DateTime->format('Y-m-d H:i:s');
        $obj->attach_artist = $AttachArtist;
        $obj->attach_genre = $AttachGenre;

		$db->insertObject('#__jpplaylists', $obj);

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Finish with creating a new Playlist at '.$DateTime.', with name: '.$playlist_name, 'priority' => JLog::INFO, 'section' => 'site')));

	}

    protected function EditPlaylist($playlist_name, $list_id, $CatID=0, $Access=0, $AttachArtist=null, $AttachGenre=null)
	{

		// Get UTC datetime for now.
		$dNow = new JDate;
		$DateTime = clone $dNow;

		$db = JFactory::getDBO();

        //Get database instance for write data
        $row =& JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

        $obj = new stdClass();
        $obj->id = $list_id;
        $obj->name = $playlist_name;
        $obj->catid = $CatID;
        $obj->access = $Access;
        $obj->modifier_date = $DateTime->format('Y-m-d H:i:s');
        $obj->attach_artist = $AttachArtist;
        $obj->attach_genre = $AttachGenre;

		$db->updateObject('#__jpplaylists', $obj, 'id', true);

	}

    protected function DeletePlaylist($playlist_id)
	{

		$db = JFactory::getDBO();

    	// Delete playlist
		$query = $db->getQuery(true);
		$query->delete();
		$query->from('#__jpplaylists');
		$query->where('id = "'.$playlist_id. '"');
		$db->setQuery($query);

		// Check for a database error.
		if (!$this->_db->query()) {
			$e = new JException(JText::_('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);
			return false;
		}
        if (PlayJoomHelper::getPlaylistEntries($playlist_id) >= 1 ) {

        	//Delete playlist content as well, if something exists
        	$query = $db->getQuery(true);
		    $query->delete();
		    $query->from('#__jpplaylist_content');
		    $query->where('list_id = "'.$playlist_id. '"');
		    $db->setQuery($query);

            // Check for a database error.
		    if (!$this->_db->query()) {
			    $e = new JException(JText::_('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), $this->_db->getErrorMsg()));
			    $this->setError($e);
			    return false;
		    }

        }
		return true;

	}
	/**
	 * Method to check out a user for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */

    public function checkin($userId = null)
	{
		// Get the user id.
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');

		if ($userId) {
			// Initialise the table with JUser.
			$table = JUser::getTable('User', 'JTable');

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row in.
			if (!$table->checkin($userId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	public function checkout($userId = null)
	{
		// Get the user id.
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');

		if ($userId) {
			// Initialise the table with JUser.
			$table = JUser::getTable('User', 'JTable');

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (!$table->checkout($user->get('id'), $userId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}
}