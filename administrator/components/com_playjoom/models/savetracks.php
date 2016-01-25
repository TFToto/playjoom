<?php
/**
 * Contains the model methods for to save audiotracks in PlayJoom database.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage models.savetracks
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2015 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Model class for savetracks
 *
 * @package		PlayJoom.Admin
 * @subpackage	    models.savetracks
 */
class PlayJoomModelSavetracks extends JModelLegacy {

	/**
	 * Method for to create a array of all files recursively
	 *
	 * @param string $dir Directory of the audio files which should be saved in the array
	 * @return array $file_info Array with the collected paths of audio files
	 */
	public function getFilesArray() {

		$dispatcher	= JDispatcher::getInstance();

		//Check OS for folder seperator
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$dir = str_replace('/', '\\', JFactory::getApplication()->getUserState('com_playjoom.path.data'));
		} else {
			$dir = JFactory::getApplication()->getUserState('com_playjoom.path.data');
		}

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get start to create file array with path '.$dir, 'priority' => JLog::INFO, 'section' => 'site')));

		//Get PlayJoom configuration
		$app	= JFactory::getApplication();
		$config = JComponentHelper::getParams('com_playjoom');
		//Get allowed file types
		$file_types = $config->get('upload_audio_extensions', 'mp3,wav,flac');
		$file_types = preg_replace('/,/','|',$file_types);

		set_time_limit(240);

		$file_info = array();

		$iterator = new RecursiveDirectoryIterator($dir);

		foreach(new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) {

			if (false == $file->isDir()) {

				//check the folder for only files with mp3, wav or flac as file ending
				$full_file_name = realpath($file->getPathname());

				if (preg_match("/." . $file_types . "$/i",$full_file_name)) {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Put file '.$full_file_name.' into array.', 'priority' => JLog::INFO, 'section' => 'site')));
					array_push($file_info, base64_encode($full_file_name));
				}
			}
		}

		return $file_info;

	}

	/**
	 * Method for to check md5 checksum for track in database
	 *
	 * @param string $md5 checksum of track
	 * @return integer
	 *
	 */
	private function checkMD5($md5) {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*) as counter');
		$query->from('#__jpaudiotracks');
		$query->where('md5 = "'.$md5.'"');

		$db->setQuery($query);
		$result = $db->loadObject();

		return $result->counter;
	}


	/*
	 * Functions for Artist table
	*/
	private function checkArtist($name_artist) {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*) as counter');
		$query->from('#__jpartists');
		$query->where('name = "'.$name_artist.'"');

		$db->setQuery($query);
		$result = $db->loadObject();

		if($result->counter >= 1) {
			JFactory::getApplication()->setUserState('com_playjoom.savetracks.artistname.'.$name_artist.'.existing', true);
			return true;
		} else {
			return false;
		}
	}

	private function saveNewArtist($name_artist, $catid) {

		$db = JFactory::getDBO();
		$row = JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

		$obj = new stdClass();
		$obj->id = null;
		$obj->catid = $catid;
		$obj->name = $name_artist;
		$obj->alias = JApplication::stringURLSafe($name_artist);
		$db->insertObject('#__jpartists', $obj);
	}

	/*
	 * Functions for Album table
	*/
	private function checkAlbum($name_album)	{

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*) as counter');
		$query->from('#__jpalbums');
		$query->where('title = "'.$name_album.'"');

		$db->setQuery($query);
		$result = $db->loadObject();

		if($result->counter >= 1) {
			JFactory::getApplication()->setUserState('com_playjoom.savetracks.albumname.'.$name_album.'.existing', true);
			return true;
		} else {
			return false;
		}
	}

	private function saveNewAlbum($id3tags, $catid) {

		$db = JFactory::getDBO();
		$row = JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

		$obj = new stdClass();
		$obj->id = null;
		$obj->catid = $catid;
		$obj->title = $id3tags['album'];
		$obj->artist = $id3tags['artist'];
		$obj->alias = JApplication::stringURLSafe($id3tags['album']);
		$db->insertObject('#__jpalbums', $obj);
	}
	/*
	 * Functions for album cover
	*/
	private function checkCover($cover_md5) {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(*) as counter');
		$query->from('#__jpcoverblobs');
		$query->where('md5 = "'.$cover_md5.'"');

		$db->setQuery($query);
		$result = $db->loadObject();

		if($result->counter >= 1) {
			JFactory::getApplication()->setUserState('com_playjoom.savetracks.cover.'.$cover_md5.'.existing', true);
			return true;
		} else {
			return false;
		}
	}

	private function saveNewAlbumcover($id3tags, $ThisFileInfo) {

		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Insert cover data for: '.$id3tags['artist'].' - '.$id3tags['album'], 'priority' => JLog::INFO, 'section' => 'admin')));
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Mime type: '.$ThisFileInfo['id3v2']['APIC'][0]['mime'], 'priority' => JLog::INFO, 'section' => 'admin')));
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Image dimension: '.$ThisFileInfo['id3v2']['APIC'][0]['image_height'].'x'.$ThisFileInfo['id3v2']['APIC'][0]['image_width'], 'priority' => JLog::INFO, 'section' => 'admin')));
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'MD5 checksum: '.md5($ThisFileInfo['id3v2']['APIC'][0]['data']), 'priority' => JLog::INFO, 'section' => 'admin')));

		$db = JFactory::getDBO();
		$row = JTable::getInstance('AudioTrack','PlayJoomTable',$config = array());

		$obj = new stdClass();
		$obj->id = null;
		$obj->artist = $id3tags['artist'];
		$obj->album = $id3tags['album'];
		$obj->md5 = md5($ThisFileInfo['id3v2']['APIC'][0]['data']);
		$obj->width = $ThisFileInfo['id3v2']['APIC'][0]['image_width'];
		$obj->height = $ThisFileInfo['id3v2']['APIC'][0]['image_height'];
		$obj->data = $ThisFileInfo['id3v2']['APIC'][0]['data'];
		$obj->mime = $ThisFileInfo['id3v2']['APIC'][0]['mime'];

		$db->insertObject('#__jpcoverblobs', $obj);
	}

	private function saveNewTrack($id3tags, $catid, $dir, $ThisFileInfo, $md5hash_value) {

		$dispatcher	= JDispatcher::getInstance();

		if (strlen($dir) <= 255) {

			//Get user id
			$user	= JFactory::getUser();
			$userId	= $user->get('id');

			//For getting the xml parameters
			$params =  JComponentHelper::getParams('com_playjoom');

			// Get UTC datetime for now.
			$dNow = new JDate;
			$DateTime = clone $dNow;

			$db = JFactory::getDBO();

			$obj = new stdClass();
			$obj->id = null;
			$obj->md5 = $md5hash_value;
			$obj->access = $params->get('pj_pre_accesslevel');
			$obj->pathatlocal = $dir;
			$obj->file = $ThisFileInfo['filename'];
			$obj->title = $id3tags['title'];
			$obj->alias = $id3tags['alias'];
			$obj->tracknumber = $id3tags['number'];
			$obj->mediatype = $id3tags['mime'];
			$obj->bit_rate = $id3tags['bitrate'];
			$obj->sample_rate = $id3tags['samplerate'];
			$obj->channels = $id3tags['channels'];;
			$obj->channelmode = $id3tags['channelmode'];;
			$obj->filesize = $id3tags['filesize'];
			$obj->length = $id3tags['length'];
			$obj->catid = $catid;
			$obj->add_datetime = $DateTime->format('Y-m-d H:i:s');
			$obj->add_by = $userId;
			$obj->artist = $id3tags['artist'];
			$obj->album = $id3tags['album'];
			$obj->year = $id3tags['year'];

			$db->insertObject('#__jpaudiotracks', $obj);

			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Insert track complete for file: '.$dir.'/'.$ThisFileInfo['filename'], 'priority' => JLog::INFO, 'section' => 'site')));
	        return true;
		}
		else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Path to file with '.strlen($dir).' characters to long. Allows are only 255 characters.', 'priority' => JLog::ERROR, 'section' => 'site')));
		}
	}

	/**
	 * Returns the id for the first level ID for an new entrie in the categorie table.
	 *
	 * @param string $mediatype possible value: 'audio', 'video', etc
	 * @return integer | null
	 */
	private function getFirstlevelID ($mediatype) {

		$dispatcher	= JDispatcher::getInstance();

		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('id,title');
		$query->from('#__categories');
		$query->where('title = "'.$mediatype. '" AND published = 1 AND extension = "com_playjoom"');

		$db->setQuery($query);
		$result = $db->loadObject();

		if (isset($result->id)) {
			if($result->id != 0 || $result->id != '') {
				return $result->id;
			}
		}
		else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No item exists for mediatype: '.$mediatype.'!', 'priority' => JLog::WARNING, 'section' => 'admin')));
			return null;
		}
	}

	/**
	 * Returns the id number of a category
	 *
	 * @param string $genre Genre title
	 * @param string $mediatype Possible values 'audio', 'video', etc
	 * @return integer
	 */
	private function getCategoryID($genre, $mediatype) {

		//Check whether category exists
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('id,title');
		$query->select('COUNT(*) as counter');
		$query->from('#__categories');
		$query->where('title = "'.$genre. '" AND extension = "com_playjoom" AND published = 1 OR alias = "'.$genre. '" AND extension = "com_playjoom" AND published = 1');

		$db->setQuery($query);
		$result = $db->loadObject();

		if ($result->counter >= 1) {
			JFactory::getApplication()->setUserState('com_playjoom.savetracks.genre.'.$genre, $result->id);
			return $result->id;
		}
		else {
			//In the case if there is no right category in database
			$mediatype_id = self::getFirstlevelID ($mediatype);

			self::saveCategory($genre, $mediatype, $mediatype_id);

			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('id,title');
			$query->from('#__categories');
			$query->where('title = "'.$genre. '" AND extension = "com_playjoom"');

			$db->setQuery($query);
			$result = $db->loadObject();
			return $result->id;
		}
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Category', $prefix = 'CategoriesTable', $config = array())	{

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to save new category.
	 *
	 * @param array  $value     Title of the new category
	 * @param string $mediatype Kind of data type, like audio, video etc
	 * @param intger $parent_id Id of parent entries if some exists
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function saveCategory($value, $mediatype, $parent_id) {

		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get starting to save a new cat: '.$value, 'priority' => JLog::INFO, 'section' => 'admin')));

		//Build data array for Playjoom category / Genre
		$data = array(
				"parent_id" => $parent_id,
				"level" => 2,
				"lft" => null,
				"rgt" => null,
				"alias" => null,
				"id" => null,
				"asset_id" => null,
				"path" => $mediatype."/".$value,
				"extension" => "com_playjoom",
				"title" => $value,
				"note" => null,
				"description" => null,
				"published" => 1,
				"checked_out" => null,
				"checked_out_time" => null,
				"access" => 1,
				"metadesc" => null,
				"metakey" => null,
				"created_user_id" => null,
				"created_time" => null,
				"modified_user_id" => null,
				"modified_time" => null,
				"language" => "*"
		);

		// Initialise variables;

		$table		= $this->getTable();
		//$pk			= (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;
		$pk = $data['id'];
		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Load the row if saving an existing category', 'priority' => JLog::INFO, 'section' => 'admin')));
		// Load the row if saving an existing category.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
			$table->setLocation($data['parent_id'], 'last-child');
		}

		// Bind the data.
		if (!$table->bind($data)) {
			print_r ($data);
			$this->setError($table->getError());
			return false;
		}

		// Bind the rules.
		if (isset($data['rules'])) {
			$rules = new JRules($data['rules']);
			$table->setRules($rules);
		}
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Check Categorie data', 'priority' => JLog::WARNING, 'section' => 'admin')));

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the tree path.
		if (!$table->rebuildPath($table->id)) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}
	/**
	 * Method for prepare the data for a track to save it into database
	 *
	 * @param string $FullFileName absolute path incl. filemame
	 * @param string $mediatype possible value: 'audio', 'video', etc
	 * @param integer $catid ID number for the genre
	 *
	 * @return void
	 *
	 */
	public function AddTrackItem($FullFileName, $mediatype, $catid = null) {

		$dispatcher	= JDispatcher::getInstance();

		require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'id3'.DIRECTORY_SEPARATOR.'getid3.php');

		// Initialize getID3 engine
		$getID3 = new getID3;

		$ThisFileInfo = $getID3->analyze($FullFileName);
		getid3_lib::CopyTagsToComments($ThisFileInfo);

		JLoader::import( 'helpers.prepare_id3tags', JPATH_SITE .DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

		//for check of duplicate entries
		$md5hash_value = md5_file($ThisFileInfo['filenamepath']);

		if (self::checkMD5($md5hash_value) == 0 ) {

			//Create the file array
			$id3tags = array();
			$id3tags = ID3TagsHelper::getID3Tags($ThisFileInfo);

			//To prepare catid
			$cattag = $id3tags['genre'];

			if ($cattag == 'no'){
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No genre tag existing!', 'priority' => JLog::WARNING, 'section' => 'admin')));
				$catid = self::getFirstlevelID ($mediatype);
			}
			else {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'getCategoryIDg. cattag: '.$cattag.', mediatype: '.$mediatype, 'priority' => JLog::INFO, 'section' => 'admin')));

				//check for allready existing cover id in user state
				if (JFactory::getApplication()->getUserState('com_playjoom.savetracks.genre.'.$cattag) != 0) {
					$catid = JFactory::getApplication()->getUserState('com_playjoom.savetracks.genre.'.$cattag);
				} else {
					$catid = self::getCategoryID($cattag, $mediatype);
				}
			}

			//Arist check
			if (!JFactory::getApplication()->getUserState('com_playjoom.savetracks.artistname.'.$id3tags['artist'].'.existing')) {
				if (self::checkArtist($id3tags['artist'])) {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'The artist '.$id3tags['artist'].' already exists. Nr.: '.self::checkAlbum($id3tags['artist']).', mediatype: '.$mediatype, 'priority' => JLog::WARNING, 'section' => 'admin')));
				} else {
					self::saveNewArtist($id3tags['artist'], $catid);
				}
			}

			//Album check
			if (!JFactory::getApplication()->getUserState('com_playjoom.savetracks.albumname.'.$id3tags['album'].'.existing')) {
				if (self::checkAlbum($id3tags['album'])) {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'The album '.$id3tags['album'].' already exists.', 'priority' => JLog::INFO, 'section' => 'admin')));
				}
				else {
					self::saveNewAlbum($id3tags, $catid);
				}
			}

			/*
			 * conditions for saving the cover
			* if cover data in the media file exists
			* if checksum of the cover data doesn´t exists in the database
			* if a complete album name exists
			* if a complete artist name exists
			*/
			if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'] )) {

				if (!JFactory::getApplication()->getUserState('com_playjoom.savetracks.cover.'.$ThisFileInfo['id3v2']['APIC'][0]['data'].'.existing')) {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No user state for cover data exist.', 'priority' => JLog::INFO, 'section' => 'admin')));

					if ($ThisFileInfo['id3v2']['APIC'][0]['data'] != ''
					 && self::checkCover(md5($ThisFileInfo['id3v2']['APIC'][0]['data'])) == 0
					 && $id3tags['album'] != "'.JText::_('COM_PLAYJOOM_ADD_TRACK_UNKNOWN').'"
					 && $id3tags['artist'] != "'.JText::_('COM_PLAYJOOM_ADD_TRACK_UNKNOWN').'") {
						self::saveNewAlbumcover($id3tags, $ThisFileInfo);
					} else {
						$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Missing some data for to add new cover!', 'priority' => JLog::WARNING, 'section' => 'admin')));
					}
				}
			}
			self::saveNewTrack($id3tags, $catid, dirname($FullFileName), $ThisFileInfo, $md5hash_value);
		} else {
		    $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Track already exist: '.$FullFileName, 'priority' => JLog::INFO, 'section' => 'admin')));
	    }
	}
}
