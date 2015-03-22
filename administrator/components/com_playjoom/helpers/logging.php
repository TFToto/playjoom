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
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

/**
 * PlayJoom component helper for site section.
 */
abstract class PlayJoomLogging
{
	function getLogTime(){
		
		$date = JFactory::getDate();
        $date->setOffset(JFactory::getApplication()->getCfg('offset'));
        return JFactory::getDate('now', JFactory::getApplication()->getCfg('offset'))->toFormat();
		
	}
	
	function getFactor ($size_value) {
		switch ($size_value) 
		{
		 case 'K' :
		 case 'k' :
		           return 1024;
		 break;
		 case 'M' :
		 case 'm' :
		           return 1024 * 1024;
		 break;
		 case 'G' :
		 case 'g' :
		           return 1024 * 1024 * 1024;
		 break;
		 default  :
		 	       return null;		        
		}
	}
	
	function writeLogRow ($content) {
		
		$params =  JComponentHelper::getParams('com_playjoom');
		
		if ($params->get('logging_active') == 1) {
			
			//set path for log file
			$log_path = '..'.DS.$params->get('logging_path').DS;
		    $log_file = $log_path.'playjoom0.log';
		    
		    if (!is_writable($log_path)) {
		    	return null;
		    }		    
		    //set ip setting
		    if ($params->get('logging_ip', 0) == 1) {
		    	$client   = 'Client: '.$_SERVER['REMOTE_ADDR'].', ';   	
		    }
		    else {
		    	$client = null;
		    }
		    
		    $sessionid = PlayJoomHelper::getSessionID();
		
		    //get time
		    $log_time = PlayJoomLogging::getLogTime(); 
		    
		    /*
		     * check log file site
		     * arrSize 0 -> all
		     * arrSize 1 -> numbers
		     * arrSize 2 -> letters
		     */		    
            preg_match("/([0-9]+)([a-z]+)/i", $params->get('logging_filesize'),$arrSize);  
           
		    if (file_exists($log_file)){
            	if (filesize($log_file) >= $arrSize[1] * PlayJoomLogging::getFactor($arrSize[2])){
            		rename($log_file, $log_path.'playjoom1.log');
            	}
            }
		    
            //write log row
		    file_put_contents($log_file, $log_time.' - '.$client.$sessionid.' - '.$content."\r\n", FILE_APPEND);
		}
		
		return null;
	}
}