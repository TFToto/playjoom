<?php
/**
 * Contains the helper methods for the PlayJoom Admin Playlists helper.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * Contains the Helper Methods for the PlayJoom Admin Playlists
 * 
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.helpers
 */
abstract class PlayJoomAdminplaylistsHelper {
	    
        public function getOptions($value)
        {

        //Get database Object
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		//For getting the xml parameters
		$app = JFactory::getApplication();
		$params		= $app->getParams();
		
		//Get User Objects
		$user	= JFactory::getUser();
		
        switch($value) {
		                case 'accesslevel' :
		                               $query->select('al.title As text, al.id As value');
		                               $groups	= implode(',', $user->getAuthorisedViewLevels());
			                           $query->where('al.id IN ('.$groups.')');
		                               
		                               $query->from('#__viewlevels AS al');
		                break;
		                case 'catlist' :       	           
		                	
		                	           $query->select('c.title AS text, c.id As value');		                	           
		                	           $query->where('c.extension = "com_playjoom.playlist"');
		                	           
		                	           $query->from('#__categories AS c');
		                break;
		                case 'artist' :
		                	           $query->select('artist As value, artist As text');
		                	           $query->order('a.artist');
		                	           $query->group('a.artist');
		                	           
		                	           if ($params->get('show_all_users', 1)
		                	           		|| JAccess::check($user->get('id'), 'core.admin') != 1) {
		                	           	   
		                	           	   $query->where('a.add_by = ' . $user->get('id'));
		                	           }
		                	           
		                	           // Implement View Level Access
		                	           if (!$user->authorise('core.admin')
		                	           		&& !$params->get('show_noauth', 1)) {
		                	           
		                	           	   $groups	= implode(',', $user->getAuthorisedViewLevels());
		                	           	   $groups = '0,'.$groups;
		                	           	   $query->where('a.access IN ('.$groups.')');
		                	           }
		                	           
		                	           // Filter by User.
		                	           if (!$params->get('show_all_users', 1)
		                	           		&& JAccess::check($user->get('id'), 'core.admin') != 1) {
		                	           
		                	           	   $users = $user->get('id');
		                	           
		                	           	   if ($params->get('show_nobody', 1)) {
		                	           		
		                	           	       $users = '0,'.$users;
		                	           	   }
		                	           	   
		                	           	   $query->where('a.add_by IN ('.$users.')');
		                	           }
		                	           
		                	           $query->from('#__jpaudiotracks AS a');
		                break;
		                case 'genre' :
		                	$query->select('catid As value');
		                	$query->group('a.catid');
		                
		                	// Join over the categories.
		                	$query->select('c.title AS text');
		                	$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		                	
		                	if ($params->get('show_all_users', 1)
		                			|| JAccess::check($user->get('id'), 'core.admin') != 1) {
		                	
		                		$query->where('a.add_by = ' . $user->get('id'));
		                	}
		                	
		                	// Implement View Level Access
		                	if (!$user->authorise('core.admin')
		                			&& !$params->get('show_noauth', 1)) {
		                	
		                		$groups	= implode(',', $user->getAuthorisedViewLevels());
		                		$groups = '0,'.$groups;
		                		$query->where('a.access IN ('.$groups.')');
		                	}
		                	
		                	// Filter by User.
		                	if (!$params->get('show_all_users', 1)
		                			&& JAccess::check($user->get('id'), 'core.admin') != 1) {
		                	
		                		$users = $user->get('id');
		                	
		                		if ($params->get('show_nobody', 1)) {
		                	
		                			$users = '0,'.$users;
		                		}
		                	
		                		$query->where('a.add_by IN ('.$users.')');
		                	}
		                	
		                	$query->from('#__jpaudiotracks AS a');
		                break;
		                	
		                default :
				                 return JText::_( 'TCE_PLG_ERROR_NO_VALUE_FOR_getOptions_FUNCTION' );
		               }


		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
        }        
}