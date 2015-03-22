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
 * @subpackage controller.folder
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
 * Methods for controlling folders
 *
 * @package		PlayJoom.Admin
 * @subpackage	    controller.folder
 * @since		1.6
 */
class PlayJoomControllerFolder extends JControllerAdmin {
	
	/**
	 * Deletes paths from the current path
	 *
	 * @param string $listFolder The image directory to delete a file from
	 * @since 1.5
	 */
	function delete() {
		
		$dispatcher	= JDispatcher::getInstance();
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
	
		$user	= JFactory::getUser();
	
		// Get some data from the request
		$tmpl	= JRequest::getCmd('tmpl');
		$paths	= JRequest::getVar('rm', array(), '', 'array');
		$folder = base64_decode(JRequest::getVar('folder', '', '', 'path'));
	
		if ($tmpl == 'component') {
			// We are inside the iframe
			$this->setRedirect('index.php?option=com_playjoom&view=media&folder='.$folder);
		} else {
			$this->setRedirect('index.php?option=com_playjoom&view=media&folder='.$folder);
		}
	
		if (!$user->authorise('core.delete', 'com_playjoom'))
		{
			// User is not authorised to delete
			JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'User is not authorised to delete.', 'priority' => JLog::WARNING, 'section' => 'admin')));
			return false;
		}
		else
		{
			// Set FTP credentials, if given
			JClientHelper::setCredentialsFromRequest('ftp');
			
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'User is authorised to delete folders.', 'priority' => JLog::INFO, 'section' => 'admin')));
				
			// Initialise variables.
			$ret = true;
	
			if (count($paths)) {
				JPluginHelper::importPlugin('content');
				
				foreach ($paths as $path) {
										
					$fullPath = JPath::clean(PLAYJOOM_BASE_PATH . '/' . $folder . '/' . base64_decode($path));
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Clean to delete folders.'.PLAYJOOM_BASE_PATH . '/' . $folder . '/' . base64_decode($path), 'priority' => JLog::INFO, 'section' => 'admin')));
						
					$object_file = new JObject(array('filepath' => $fullPath));
					if (is_file($fullPath))
					{
						// Trigger the onContentBeforeDelete event.
						$result = $dispatcher->trigger('onContentBeforeDelete', array('com_playjoom.file', &$object_file));
						if (in_array(false, $result, true)) {
							// There are some errors in the plugins
							JError::raiseWarning(100, JText::plural('COM_PLAYJOOM_ERROR_BEFORE_DELETE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));
							$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Error before to delete folder: '.implode('<br />', $errors), 'priority' => JLog::ERROR, 'section' => 'admin')));
							continue;
						}
	
						$ret &= JFile::delete($fullPath);
						$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Delete folder done: '.$fullPath, 'priority' => JLog::INFO, 'section' => 'admin')));
						
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
	
							$ret &= !JFolder::delete($fullPath);
	
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
	
	/**
	 * Create a folder
	 *
	 * @param string $path Path of the folder to create
	 * @since 1.5
	 */
	function create() {
		
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$dispatcher	= JDispatcher::getInstance();
		
		//Get Post datas
		$jinput = JFactory::getApplication()->input;
		$folder = $jinput->post->get('foldername', 'default_value', 'filter');
		$parent = $jinput->post->get('folderbase', 'default_value', 'filter');
		
		//Filter folder name for not allowed characters
		$filterArray = Array("/%/","/'/","/$/","/</","/>/","/\"/","/\*/","/&/","/=/");
		$replaceArray = Array(null,null,null,null,null,null,null,null,null);
		$folder = preg_replace($filterArray , $replaceArray , $folder);
		
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Create a folder.Foldername: '.PLAYJOOM_BASE_PATH.DIRECTORY_SEPARATOR.$parent.DIRECTORY_SEPARATOR.$folder, 'priority' => JLog::INFO, 'section' => 'admin')));
		
		$user = JFactory::getUser();
	
		$this->setRedirect('index.php?option=com_playjoom&view=media&folder='.$parent.'&tmpl='.JRequest::getCmd('tmpl', 'index'));
	
		if (strlen($folder) > 0)
		{
			if (!$user->authorise('core.create', 'com_playjoom'))
			{
				// User is not authorised to delete
				JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_CREATE_NOT_PERMITTED'));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'User is not allowed to create a folder.', 'priority' => JLog::WARNING, 'section' => 'admin')));
				
				return false;
			}
	
			// Set FTP credentials, if given
			JClientHelper::setCredentialsFromRequest('ftp');
	
			JRequest::setVar('folder', $parent);			
			
			$path = JPath::clean(PLAYJOOM_BASE_PATH . '/' . $parent . '/' . $folder);
			if (!is_dir($path) && !is_file($path))
			{
				// Trigger the onContentBeforeSave event.
				$object_file = new JObject(array('filepath' => $path));
				JPluginHelper::importPlugin('content');
				
				$result = $dispatcher->trigger('onContentBeforeSave', array('com_playjoom.folder', &$object_file));
				if (in_array(false, $result, true)) {
					// There are some errors in the plugins
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Error occours before saving. '.$object_file->getErrors(), 'priority' => JLog::ERROR, 'section' => 'admin')));
					JError::raiseWarning(100, JText::plural('COM_PLAYJOOM_ERROR_BEFORE_SAVE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));
					continue;
				}
	
				JFolder::create($path);
				$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
				JFile::write($path . "/index.html", $data);
	
				// Trigger the onContentAfterSave event.
				$dispatcher->trigger('onContentAfterSave', array('com_playjoom.folder', &$object_file, true));
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'create folder complete.', 'priority' => JLog::INFO, 'section' => 'admin')));
				
				$this->setMessage(JText::sprintf('COM_PLAYJOOM_CREATE_COMPLETE', substr($path, strlen(PLAYJOOM_BASE_PATH))));
			}
			JRequest::setVar('folder', ($parent) ? $parent.'/'.$folder : $folder);
		}
	}
}
