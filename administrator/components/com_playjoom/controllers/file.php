<?php
/**
 * Contains the controller method for file media.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Admin
 * @subpackage controller.file
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
 * Media File Controller
 *
 * @package		PlayJoom.Admin
 * @subpackage	    controller.file
 * @since		1.6
 */
class PlayJoomControllerFile extends JControllerLegacy {
	/*
	 * The folder we are uploading into
	*/
	protected $folder = '';
	
	/**
	 * Method for uploading a file
	 *
	 * @since 1.5
	 * @return void
	 */
	function upload() {
		
		$dispatcher	= JDispatcher::getInstance();
		
		$params = JComponentHelper::getParams('com_playjoom');
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		// Get the user
		$user		= JFactory::getUser();

		// Get some data from the request
		$file		= JRequest::getVar('Filedata', '', 'files', 'array');
		$return       = $this->input->post->get('return-url', null, 'base64');
		$this->folder = $this->input->get('folder', '', 'path');

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Start uploading file: '.$this->folder.DIRECTORY_SEPARATOR.$file['name'], 'priority' => JLog::INFO, 'section' => 'admin')));
		
		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// Set the redirect
		if ($return) {
			$this->setRedirect(base64_decode($return).'&view=media&folder='.$this->folder);
		}

		// Make the filename safe
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Make file name safe: '.$file['name'], 'priority' => JLog::INFO, 'section' => 'admin')));
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name']))
		{
			// The request is valid
			$err = null;
			$allowableExtensions = $params->get('upload_audio_extensions', 'mp3,wav,flac');
			if (!PlayJoomMediaHelper::canUpload($file, $err, $allowableExtensions)) {
				
				// The file can't be upload
				JError::raiseNotice(100, JText::_($err));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Can not uploading file: '.$file['name'].'Error: '.$err, 'priority' => JLog::ERROR, 'section' => 'admin')));
				
				return false;
			}

			$filepath = JPath::clean(PLAYJOOM_BASE_PATH . '/' . $this->folder . '/' . strtolower($file['name']));

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

			if (JFile::exists($file['filepath']))
			{
				// File exists
				JError::raiseWarning(100, JText::_('COM_PLAYJOOM_ERROR_FILE_EXISTS'));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'File already exists. '.$file['name'], 'priority' => JLog::ERROR, 'section' => 'admin')));
				
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
			}
			else
			{
				// Trigger the onContentAfterSave event.
				$dispatcher->trigger('onContentAfterSave', array('com_playjoom.file', &$object_file, true));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Upload complete. Temp filename: '.$file['tmp_name'].', filepath: '.$file['filepath'], 'priority' => JLog::INFO, 'section' => 'admin')));
				
				$this->setMessage(JText::sprintf('COM_PLAYJOOM_UPLOAD_COMPLETE', substr($file['filepath'], strlen(PLAYJOOM_BASE_PATH))));
				return true;
			}
		}
		else
		{
			$this->setRedirect('index.php', JText::_('COM_PLAYJOOM_INVALID_REQUEST'), 'error');
			return false;
		}
	}

	/**
	 * Deletes paths from the current path
	 *
	 * @param string $listFolder The image directory to delete a file from
	 * @since 1.5
	 */
	function delete() {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();

		// Get some data from the request
		$tmpl	= JRequest::getCmd('tmpl');
		$paths	= JRequest::getVar('rm', array(), '', 'array');
		$folder = base64_decode(JRequest::getVar('folder', '', '', 'path'));

		$this->setRedirect('index.php?option=com_playjoom&view=media&folder='.$folder);
		
		if (!$user->authorise('core.delete', 'com_playjoom'))
		{
			// User is not authorised to delete
			JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));
			return false;
		}
		else
		{
			// Set FTP credentials, if given
			JClientHelper::setCredentialsFromRequest('ftp');

			// Initialise variables.
			$ret = true;

			if (count($paths))
			{
				JPluginHelper::importPlugin('content');
				$dispatcher	= JDispatcher::getInstance();
				foreach ($paths as $path)
				{
					if ($path !== JFile::makeSafe($path))
					{
						// filename is not safe
						$filename = htmlspecialchars($path, ENT_COMPAT, 'UTF-8');
						JError::raiseWarning(100, JText::sprintf('COM_MEDIA_ERROR_UNABLE_TO_DELETE_FILE_WARNFILENAME', substr($filename, strlen(PLAYJOOM_BASE_PATH))));
						continue;
					}

					$fullPath = JPath::clean(PLAYJOOM_BASE_PATH . '/' . $folder . '/' . $path);
					$object_file = new JObject(array('filepath' => $fullPath));
					if (is_file($fullPath))
					{
						// Trigger the onContentBeforeDelete event.
						$result = $dispatcher->trigger('onContentBeforeDelete', array('com_playjoom.file', &$object_file));
						if (in_array(false, $result, true)) {
							// There are some errors in the plugins
							JError::raiseWarning(100, JText::plural('COM_PLAYJOOM_ERROR_BEFORE_DELETE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));
							continue;
						}

						$ret &= JFile::delete($fullPath);

						// Trigger the onContentAfterDelete event.
						$dispatcher->trigger('onContentAfterDelete', array('com_playjoom.file', &$object_file));
						$this->setMessage(JText::sprintf('COM_PLAYJOOM_DELETE_COMPLETE', substr($fullPath, strlen(PLAYJOOM_BASE_PATH))));
					}
					elseif (is_dir($fullPath))
					{
						if (count(JFolder::files($fullPath, '.', true, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX'), array('index.html', '^\..*', '.*~'))) == 0)
						{
							// Trigger the onContentBeforeDelete event.
							$result = $dispatcher->trigger('onContentBeforeDelete', array('com_playjoom.folder', &$object_file));
							if (in_array(false, $result, true)) {
								// There are some errors in the plugins
								JError::raiseWarning(100, JText::plural('COM_PLAYJOOM_ERROR_BEFORE_DELETE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));
								continue;
							}

							$ret &= JFolder::delete($fullPath);

							// Trigger the onContentAfterDelete event.
							$dispatcher->trigger('onContentAfterDelete', array('com_playjoom.folder', &$object_file));
							$this->setMessage(JText::sprintf('COM_PLAYJOOM_DELETE_COMPLETE', substr($fullPath, strlen(PLAYJOOM_BASE_PATH))));
						}
						else
						{
							//This makes no sense...
							JError::raiseWarning(100, JText::sprintf('COM_PLAYJOOM_ERROR_UNABLE_TO_DELETE_FOLDER_NOT_EMPTY', substr($fullPath, strlen(PLAYJOOM_BASE_PATH))));
						}
					}
				}
			}
			return $ret;
		}
	}
}
