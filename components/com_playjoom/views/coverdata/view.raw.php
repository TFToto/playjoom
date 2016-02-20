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
class PlayJoomViewCoverdata extends JViewLegacy {

	// Overwriting JView display method
	function display($tpl = null) {

		$dispatcher	= JDispatcher::getInstance();
echo 'hello';
		// Get data from the model
		$this->item = $this->get('Item');
			
			
			//header('Content-Type', $this->item->mime);
	
			
            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
            	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Problem with datadase request: '.implode("\r\n", $errors), 'priority' => JLog::ERROR, 'section' => 'site')));
            	return false;
            }

            if ($this->item != null) {

            	switch ($this->item->mime) {
            		case "image/jpeg" :
            			$cover_base64 = 'data:image/jpg;base64,'.base64_encode($this->item->data);
            			//$cover_base64 = base64_encode($this->item->data);
            			break;
            		case "image/jpg" :
            			$cover_base64 = 'data:image/jpg;base64,'.base64_encode($this->item->data);
            			break;
            		case "image/gif" :
            			$cover_base64 = 'data:image/gif;base64,'.base64_encode($this->item->data);
            			break;
            		case "image/png" :
            			$cover_base64 = 'data:image/png;base64,'.base64_encode($this->item->data);
            			break;
            		default:
            			'MISSING COVER IMAGE TYPE';
            			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Missing or unknow mime type for cover img. mime: '.$this->item->mime, 'priority' => JLog::ERROR, 'section' => 'site')));
            			$cover_base64 = null;
            	}

            	
            	//print $cover_base64;
            	echo $this->item->data;
            	
            	/*
            	echo 'jsonCallback('.json_encode(array(
            						"image_data" => $cover_base64,
            						"image_code" => 'jpg',
            						"image_width" => $this->item->width,
            						"image_height" => $this->item->height,
            						"image_mime" => $this->item->mime
            					)).')';
            	*/
            } else {
            	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Cover datas, go to subsequently maintain.', 'priority' => JLog::WARNING, 'section' => 'site')));
            
            }

       }
}