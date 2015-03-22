<?php
/**
 * @package Joomla 3.0.x
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewSuggestionsearch extends JViewLegacy {
	
        // Overwriting JView display method
        function display($tpl = null) {
        	
        	$dispatcher	= JDispatcher::getInstance();
        	
        	// Get data from the model
            $artist_results = $this->get('ArtistResults');
            $album_results  = $this->get('AlbumResults');
            $track_results  = $this->get('TrackResults');
                
            //Get setting values from xml file
            $app		= JFactory::getApplication();
            $params		= $app->getParams();
 
            // Check for errors.
            if (count($errors = $this->get('Errors'))) {            	
            	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Problem with datadase request: '.implode("\r\n", $errors), 'priority' => JLog::ERROR, 'section' => 'site')));              
            	return false;
            }

            $results = array_merge($artist_results, $album_results, $track_results);
            echo json_encode($results);
       }
}