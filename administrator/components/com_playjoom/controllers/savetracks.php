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
 * @copyright Copyright (C) 2010 - 2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
// Register dependent classes.
JLoader::register('PlayJoomSavetracks', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/savetracks.php');
 
/**
 * Controller for savetracks
 *
 * @package		PlayJoom.Admin
 * @subpackage	    controller.savetracks
 */
class PlayJoomControllerSaveTracks extends JControllerAdmin {
	
	/**
	 * Class Constructor
	 *
	 * @param	array	$config		An optional associative array of configuration settings.
	 * @return	void
	 * @since	1.5
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

		// Map the apply task to the save method.
		$this->registerTask('apply', 'save');
	}
	
	/**
     * Method to start the addtracks method for the save the files of the selected folder.
     *
     * The seleced folder value will comes for the media viewer as submit form with the text name selectedfolder
     *
     * @param	void
     * @return	void
     */
    function save() {
    	
    	$dispatcher	= JDispatcher::getInstance();
    	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get start to save tracks', 'priority' => JLog::INFO, 'section' => 'site')));
    	
    	// We don't want this form to be cached.
    	header('Pragma: no-cache');
    	header('Cache-Control: no-cache');
    	header('Expires: -1');
    	
    	$path = JRequest::getVar('selectedfolder');
    	
    	if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    		$path = substr($path, 1);
    	}
    	
    	$message = null;
    	$messageType = null;
    	
    	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Check path: '.$path, 'priority' => JLog::INFO, 'section' => 'site')));
    	 
    	//Check if path for audio files exists
    	if($path) {
    				
    		JFactory::getApplication()->setUserState('com_playjoom.path.data', $path);
    		 
    		//Get file array
    		$model = $this->getModel('savetracks');
    		$file_array = $model->getFilesArray();    		
    		//Save path array into user state
    		$cache_array = implode('*|*', $file_array);    		
    		JFactory::getApplication()->setUserState('com_playjoom.path.array', $cache_array);
    		//Set url for redirection
    		$url = 'index.php?option=com_playjoom&view=savetracks';
    	} else	{
    		JFactory::getApplication()->setUserState('com_playjoom.array', null);
    		$url = 'index.php?option=com_playjoom&view=audiotracks';
    		$message = JText::_('COM_PLAYJOOM_VIEW_TOAPPEND_FAILED');
    		$messageType = 'error';
    	}
    	
    	$this->setRedirect($url, $message, $messageType);
    	
    }
}