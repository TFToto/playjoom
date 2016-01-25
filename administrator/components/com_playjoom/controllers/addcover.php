<?php
/**
 * Contains the controller method for add covers.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Admin
 * @subpackage controller.addcover
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * AddCover File Controller
 *
 * @package		PlayJoom.Admin
 * @subpackage	    controller.addcover
 * @since		1.6
 */
class PlayJoomControllerAddCover extends JControllerLegacy {
	/*
	 * The folder we are uploading into
	*/
	protected $folder = '';
	
	/**
	 * Class Constructor
	 *
	 * @param	array	$config		An optional associative array of configuration settings.
	 * @return	void
	 * @since	1.5
	 */
	function __construct($config = array()) {
		parent::__construct($config);
	
		// Map the apply task to the save method.
		$this->registerTask('apply', 'save');
	}
	
	/**
	 * Method for uploading a file
	 *
	 * @since 1.5
	 * @return void
	 */
	function save() {
		
		$dispatcher	= JDispatcher::getInstance();
		$params = JComponentHelper::getParams('com_playjoom');
		
		$allowableExtensions = $params->get('upload_cover_extensions', 'jpg,jpeg,png,gif');
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		// Get the user
		$user		= JFactory::getUser();

		// Get some data from the request
		$file		= JRequest::getVar('Filedata', '', 'files', 'array');
		$ArtistAlbum = JRequest::getVar('artistalbum');;
		$this->folder = $this->input->get('folder', '', 'path');
		$return = null;
		
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Start uploading and save cover for: '.$ArtistAlbum.', file '.$file['name'], 'priority' => JLog::INFO, 'section' => 'admin')));
		
		// Set the redirect
		//$this->setRedirect(JRoute::_('index.php?option=com_playjoom&view=covers'));
		
		$file['name']	= JFile::makeSafe($file['name']);
		
		if (isset($file['name'])) {
			
			// The request is valid
			$err = null;
			if (!PlayJoomMediaHelper::canUpload($file, $err, $allowableExtensions)) {
				
				// The file can't be upload
				JError::raiseNotice(100, JText::_($err));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'The file '.$file['name'].' can\'t be upload. Error: '.$err, 'priority' => JLog::ERROR, 'section' => 'admin')));
		
				return false;
			}
			
			//Get global tmp path
			$tmp_path = JFactory::getConfig()->get('tmp_path');
			
			$filepath = JPath::clean($tmp_path . '/image/' . strtolower($file['name']));
		
			// Trigger the onContentBeforeSave event.
			JPluginHelper::importPlugin('content');
				
			$object_file = new JObject($file);
			$object_file->filepath = $filepath;
			$result = $dispatcher->trigger('onContentBeforeSave', array('com_playjoom.file', &$object_file));
			if (in_array(false, $result, true)) {
				// There are some errors in the plugins
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Error occours before saving. '.$object_file->getErrors(), 'priority' => JLog::ERROR, 'section' => 'admin')));
				JError::raiseWarning(100, JText::plural('COM_PLAYJOOM_ERROR_BEFORE_SAVE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));
				return false;
			}
			$file = (array) $object_file;
		
			if (JFile::exists($filepath))
			{
				// File exists
				JError::raiseWarning(100, JText::_('COM_PLAYJOOM_ERROR_FILE_EXISTS'));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'File already exists. '.$filepath, 'priority' => JLog::ERROR, 'section' => 'admin')));
		
				return false;
			}
			elseif (!$user->authorise('core.create', 'com_playjoom'))
			{
				// File does not exist and user is not authorised to create
				JError::raiseWarning(403, JText::_('COM_PLAYJOOM_ERROR_CREATE_NOT_PERMITTED'));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'The User '.$user->get('username').' has not permitted to upload the file: '.$file, 'priority' => JLog::ERROR, 'section' => 'admin')));
		
				return false;
			}
		
			if (!JFile::upload($file['tmp_name'], $file['filepath']))
			{
				// Error in upload
				JError::raiseWarning(100, JText::_('COM_PLAYJOOM_ERROR_UNABLE_TO_UPLOAD_FILE'));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Unable to upload file: '.$file['tmp_name'].' in path: '.$file['filepath'], 'priority' => JLog::ERROR, 'section' => 'admin')));
		
				return false;
			} else {				
				
				if (PlayJoomControllerAddCover::AddCover($file['filepath'], $ArtistAlbum)) {
					
					// Trigger the onContentAfterSave event.
					$dispatcher->trigger('onContentAfterSave', array('com_playjoom.file', &$object_file, true));
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Saving cover complete. File: '.$file['filepath'], 'priority' => JLog::INFO, 'section' => 'admin')));
					
					$link = JRoute::_('index.php?option=com_playjoom&view=covers', false);
					$msg = JText::sprintf('COM_PLAYJOOM_UPLOAD_COMPLETE', substr($file['filepath'], strlen(PLAYJOOM_BASE_PATH)));
					$this->setRedirect($link, $msg);
					
					//Delete temp cover file, after adding in database
					unlink($file['filepath']);
					
					return true;
					
				} else {
					$this->setMessage(JText::sprintf('COM_PLAYJOOM_FAULTY_TOADD_DATABASE', substr($file['filepath'], strlen(PLAYJOOM_BASE_PATH))));
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Not Possible to add the cover into the database', 'priority' => JLog::ERROR, 'section' => 'admin')));
					
					//Delete temp cover file, after adding in database
					unlink($file['filepath']);
				}
				
				return true;
			}
		} else	{
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => JText::_('COM_PLAYJOOM_INVALID_REQUEST'), 'priority' => JLog::ERROR, 'section' => 'admin')));
				
			return false;
		}
		
	}
	
	/**
	 * Method for to add an album cover.
	 *
	 * @param string $coverfile path and file name
	 * @param string $NameValues Information about the artist- and album name 
	 *
	 */
	protected function AddCover($coverfile, $NameValues) {
	
		$dispatcher	= JDispatcher::getInstance();
		
		/*
		 * Initialize getID3 engine
		*/
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'id3'.DIRECTORY_SEPARATOR.'getid3.php');
		$getID3 = new getID3;
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Initialize getID3 engine is done', 'priority' => JLog::INFO, 'section' => 'admin')));
		
		/*
		 * Split $NameValues into Values [0] => Artist, [1]=> album name
		*/
		$Values = explode('+',$NameValues);;
	
		$ThisFileInfo = $getID3->analyze($coverfile);
		getid3_lib::CopyTagsToComments($ThisFileInfo);
	
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'meta data for cover file: resolution-x: '.$ThisFileInfo['video']['resolution_x'].', resolution-y: '.$ThisFileInfo['video']['resolution_y'].', mime type: '.$ThisFileInfo['mime_type'], 'priority' => JLog::INFO, 'section' => 'admin')));
		
		JLoader::import( 'tables.addcover', JPATH_SITE .DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');
	
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Load artist: '.$Values[0].' for albumname: '.$Values[1], 'priority' => JLog::INFO, 'section' => 'admin')));
		
		$img_blob = file_get_contents($coverfile);
		
		AddCoverTables::save_new_albumcover($Values[1], $Values[0], $ThisFileInfo, md5_file($coverfile), $img_blob);
		return true;
	}
}
