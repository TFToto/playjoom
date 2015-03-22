<?php
/**
 * @copyright	Copyright (C) 2010 - 2012 by teglo, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * PlayJoom System Logging Plugin
 *
 * @package	PlayJoom.Plugin
 * @subpackage	System.log
 */
class  plgSystemPJLogger extends JPlugin {
	
	/**
	 * Method for to get the size factor from the log file
	 * 
	 * @param string prefix
	 * @return integre
	 */ 
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
	
	/**
	 * Method for to create log files for PlayJoom components
	 * 
	 * @param array $logContent
	 */
	function onEventLogging($logContent) {		
		
		//get PlayJoom configuration
		$params  =  JComponentHelper::getParams('com_playjoom');
		$log_path = JFactory::getConfig()->get('log_path');
		$log_file = $log_path.DIRECTORY_SEPARATOR.'com_playjoom0.php';
		
		$options['text_file'] = 'com_playjoom0.php';
		
		//Check log folder weather it is writeable
		if (!is_writable($log_path)) {
			return null;
		}
		
		//set ip setting
		if ($params->get('logging_ip', 0) == 1) {
			$options['text_entry_format'] = '{DATETIME} {PRIORITY} {CLIENTIP} {METHOD} {SECTION} {MESSAGE}';
		} else {
			$options['text_entry_format'] = '{DATETIME} {PRIORITY} {METHOD} {SECTION} {MESSAGE}';
		}
		
		$options['text_file'] = 'com_playjoom0.php';
		
		//Set Priotiry level
		switch ($params->get('priority', 30719)) {
			case 8 :
				$prioritylevel = JLog::ERROR;
			    break;
			case 16 :
				$prioritylevel = JLog::WARNING;
			    break;
			case 16 :
			    $prioritylevel = JLog::INFO;
			    break;
			default :
				$prioritylevel = JLog::ALL;
		}
		/*
		 * check log file site
		* arrSize 0 -> all
		* arrSize 1 -> numbers
		* arrSize 2 -> letters
		*/
		preg_match("/([0-9]+)([a-z]+)/i", $params->get('logging_filesize', '500k'),$arrSize);

		if (file_exists($log_file)){
			if (filesize($log_file) >= $arrSize[1] * plgSystemPJLogger::getFactor($arrSize[2])){
				rename($log_file, $log_path.DIRECTORY_SEPARATOR.'com_playjoom1.php');
			}
		} 
		
		JLog::addLogger($options, $prioritylevel);
		JLog::add($logContent['method'].' '.$logContent['section']." - ".$logContent['message'], $logContent['priority'], $logContent['section']);
	}
}
