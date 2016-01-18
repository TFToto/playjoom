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
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * PlayJoom Model
 */
class PlayJoomModelInfoabout extends JModelItem
{
/**
	 * @var string msg
	 */
	protected $info;
 
	/**
	 * Get the message
	 * @return string The message to be displayed to the user
	 */
	public function getInfo() 
	{
		//Set variablen
	    $type = JRequest::getVar('type');
		 
		if (!isset($this->info)) 
		{  
		    $db = JFactory::getDBO();
    	
    	    $query = $db->getQuery(true);
		    
    	    switch($type) 
    	    {
    	    	case "album" :
    	    		$query->select('title,album_release,label,production,infotxt');
                    $query->from('#__jpalbums');
                    $query->where('title = "'.base64_decode(JRequest::getVar('album')). '"');
    	        break;
    	    
    	        case "artist" :
    	        	$query->select('name,formation,members,infotxt');
                    $query->from('#__jpartists');
                    $query->where('name = "'.base64_decode(JRequest::getVar('artist')). '"');
    	        break;
    	    
    	        case "genre" :
    	        	$query->select('title,description');
                    $query->from('#__categories');
                    $query->where('extension = "com_playjoom"');
                    $query->where('title = "'.base64_decode(JRequest::getVar('genre')). '"');
    	        break;
    		
    	        case "track" :
    			    $query->select('title,description');
                    $query->from('#__jpaudiotracks');
                    $query->where('id = "'.base64_decode(JRequest::getVar('track')). '"');
    	        break;
    	    
    		    default:
    			    return null;    		
    	    }
                            
            $db->setQuery($query);

            if($db->loadObject())
            {
        	     $this->info = $db->loadObject();
            }
            else 
            {
        	     return null;
            }
			
			//$this->info = 'Hello World!';
		}
		return $this->info;
	}
}