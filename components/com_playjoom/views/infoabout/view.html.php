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

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );

if (JRequest::getVar('source') == 'wiki') {
	require_once JPATH_COMPONENT.'/apis/wiki.php';
}

/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewInfoabout extends JViewLegacy
{
// Overwriting JView display method
	function display($tpl = null) 
	{
		// Assign data to the view
		$this->info = $this->get('Info');
		
		//Get setting values from xml file
        $app		= JFactory::getApplication();
        $params		= $app->getParams();
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->assignRef('params',		$params);
		
		//load external data
		if (JRequest::getVar('source') == 'lastfm') {
			$About =  array('artist' => JRequest::getVar('artist'),'album' => JRequest::getVar('album'),'genre' => JRequest::getVar('genre'),'type' => JRequest::getVar('type'));			
			require_once JPATH_COMPONENT.'/apis/lastfm.php';
			$getLastFMContent = PlayJoomLastfmHelper::GetLastfmContent(JRequest::getVar('type'), $About);
			$this->assignRef('lastFMRequest',	$getLastFMContent);			
		}
		
        // Display the view
		parent::display($tpl);
	}
}