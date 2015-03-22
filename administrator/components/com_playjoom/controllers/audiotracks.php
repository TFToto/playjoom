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
 
/**
 * PlayJooms Controller
 */
class PlayJoomControllerAudioTracks extends JControllerAdmin {
	
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
     * Proxy for getModel.
     * @since       1.6
     */
    public function getModel($name = 'AudioTrack', $prefix = 'PlayJoomModel') {
    	
    	$model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }
    
    public function getTable($type = 'Category', $prefix = 'JTable', $config = array()) {
    	
    	return JTable::getInstance($type, $prefix, $config);
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
    	
    	// Check for request forgeries.
    	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));    
    	
    	$task            = JRequest::getVar('task_save');
    	    
    	// Set the redirect based on the task.
    	switch ($this->getTask()) {
    		
    		case 'apply':
    			$message = JText::_('COM_CONFIG_SAVE_SUCCESS');
    			$this->setRedirect('index.php?option=com_config&view=component&component='.$option.'&tmpl=component&refresh=1', $message);
    			break;
    
    		case 'save':
    		default:
    			$this->setRedirect('index.php?option=com_playjoom&view=audiotracks');
    			break;
    	}
    
    	return true;
    }
}