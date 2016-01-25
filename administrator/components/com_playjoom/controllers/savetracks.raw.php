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
 * Raw Controller for savetracks
 *
 * @package		PlayJoom.Admin
 * @subpackage	    controller.savetracks.raw
 */
class PlayJoomControllerSaveTracks extends JControllerAdmin {

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
     * Method for to append new track into database
     *
     */
    function append() {

    	$dispatcher	= JDispatcher::getInstance();

    	// Send the appropriate error code response.
    	JResponse::clearHeaders();
    	JResponse::setHeader('Content-Type', 'application/json; charset=utf-8');
    	JResponse::sendHeaders();

    	$total = $_GET['total'];
    	$one_procent = 1/($total/100);
    	$curr_index = number_format($_GET['total']*$_GET['status']/100,0,'','');

    	$path_from_cache = explode('*|*', JFactory::getApplication()->getUserState('com_playjoom.path.array'));

    	$php_array['status'] = $_GET['status']+$one_procent;

    	//$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Add track no '.$curr_index.' in path: '.base64_decode(($path_from_cache[$curr_index] - 1)), 'priority' => JLog::INFO, 'section' => 'admin')));

    	if (isset($path_from_cache[$curr_index])) {
    		$model = $this->getModel('savetracks');
    		$model->AddTrackItem(base64_decode($path_from_cache[$curr_index]), 'audio');
    	}

    	// Bei 100% ist Schluss ;)
    	if($php_array['status']>100) {
    		$php_array['status'] = 100;
    	}

    	if($php_array['status'] != 100
    	 && isset($path_from_cache[$curr_index])) {
    		$php_array['message'] = JText::_('COM_PLAYJOOM_SAVETRACKS_CURRENT_STATUS').' '.($curr_index + 1).' / '.$total.' - '.round($php_array['status'],1).'%';
    		$php_array['message_path'] = JText::_('COM_PLAYJOOM_SAVETRACKS_PATH_STATUS').' '.base64_decode($path_from_cache[$curr_index]);
    	} else {
    		//Clear UserStates in session 
    		JFactory::getApplication()->setUserState('com_playjoom.savetracks', null);
    		JFactory::getApplication()->setUserState('com_playjoom.path.array', null);
    		JFactory::getApplication()->setUserState('com_playjoom.path.data', null);
    		
    		$php_array['message'] = 'done';
    	}

    	// Output as PHP arrays as JSON Objekt
    	echo json_encode($php_array);
    }
}