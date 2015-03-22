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
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modellist');
 
/**
 * PlayJoom Model
 */
class PlayJoomModelStatistics extends JModelList
{
    public function __construct($config = array())
	{
		parent::__construct($config);
	}
               /**
         * Method to build an SQL query to load the list data.
         *
         * @return      string  An SQL query
         */
    protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		//$app = JFactory::getApplication();	
		//$session = JFactory::getSession();
		
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
    
	
    public static function getCounts($CatID=null,$ArtistName=null) 
    {
        //Get database object
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        //Get PlayJoom maccess config
        $app = JFactory::getApplication();
        $params		= $app->getParams();
        
        //Get User Objects
        $user	= JFactory::getUser();
         
        $query->select('COUNT(*) as counter');
        $query->from('#__jpaudiotracks');
        
        if ($CatID != null AND $ArtistName == null)  {
        	$query->where('catid = "'.$CatID. '"');
        }
        elseif ($CatID != null AND $ArtistName != null)  {
        	$query->where('catid = "'.$CatID. '"');
        	$query->where('artist = "'.$ArtistName. '"');
        }
        
        //Filtering by user
        if (JAccess::check($user->get('id'), 'core.admin') != 1) {

        	//Get user id
        	$users = $user->get('id');
        	
        	$userCheck = $params->get('show_all_users', 1);
        	$userCheck = (int)$userCheck + $params->get('show_nobody', 1);
        	
        	if ($userCheck == 1) {
        	
        	    if ($params->get('show_all_users', 1)) {
        		   $query->where('add_by >= 1');
        	    }
        	
        	    if ($params->get('show_nobody', 1)) {
        		$users = '0,'.$users;
        		  $query->where('add_by IN ('.$users.')');
        	    }
        	}
        	elseif ($userCheck == 0) {
        		$query->where('add_by = '.$users.'');
        	}
        }

        // Implement View Level Access
        if (!$user->authorise('core.admin')
        		&& !$params->get('show_noauth', 1)) {
        
        	$groups	= implode(',', $user->getAuthorisedViewLevels());
        	$groups = '0,'.$groups;
        	$query->where('access IN ('.$groups.')');
        }
        
        $db->setQuery($query);
        $result = $db->loadObject();
		        
        return $result->counter;    
    }
    
    protected function getListQuery()
	{
		
		//For getting the xml parameters
		$app = JFactory::getApplication();
        $params		= $app->getParams();
        
        //Get user objects
        $user	= JFactory::getUser();
                
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select( $this->getState('list.select','a.catid AS id') );
		$query->from('#__jpaudiotracks AS a');
        
        // Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
		$query->group('category_title');
		
	    //Filtering by user
        if (JAccess::check($user->get('id'), 'core.admin') != 1) {

        	//Get user id
        	$users = $user->get('id');
        	
        	$userCheck = $params->get('show_all_users', 1);
        	$userCheck = (int)$userCheck + $params->get('show_nobody', 1);
        	
        	if ($userCheck == 1) {
        	
        	    if ($params->get('show_all_users', 1)) {
        		   $query->where('add_by >= 1');
        	    }
        	
        	    if ($params->get('show_nobody', 1)) {
        		$users = '0,'.$users;
        		  $query->where('add_by IN ('.$users.')');
        	    }
        	}
        	elseif ($userCheck == 0) {
        		$query->where('add_by = '.$users.'');
        	}
        }
		
		// Implement View Level Access
		if (!$user->authorise('core.admin')
				&& !$params->get('show_noauth', 1)) {
		
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$groups = '0,'.$groups;
			$query->where('a.access IN ('.$groups.')');
		}
		
        return $query;
	}
	
    public static function getArtistItems($CatID) 
    {
    	
    	//Get PlayJoom maccess config
    	$app = JFactory::getApplication();
    	$params		= $app->getParams();
    	
    	//Get User Objects
    	$user	= JFactory::getUser();
    	
        $db		= JFactory::getDbo();
		
		$query	= $db->getQuery(true);
		
		//$query->setState('list.limit', 5);
        $query->select('a.artist, a.catid');
		
        //$query->order('a.create_date', 'DESC');

		$query->from('#__jpaudiotracks AS a');		
		$query->where('a.catid="'.$CatID.'"');
		
        //Filtering by user
        if (JAccess::check($user->get('id'), 'core.admin') != 1) {

        	//Get user id
        	$users = $user->get('id');
        	
        	$userCheck = $params->get('show_all_users', 1);
        	$userCheck = (int)$userCheck + $params->get('show_nobody', 1);
        	
        	if ($userCheck == 1) {
        	
        	    if ($params->get('show_all_users', 1)) {
        		   $query->where('add_by >= 1');
        	    }
        	
        	    if ($params->get('show_nobody', 1)) {
        		$users = '0,'.$users;
        		  $query->where('add_by IN ('.$users.')');
        	    }
        	}
        	elseif ($userCheck == 0) {
        		$query->where('add_by = '.$users.'');
        	}
        }
		
		// Implement View Level Access
		if (!$user->authorise('core.admin')
				&& !$params->get('show_noauth', 1)) {
		
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$groups = '0,'.$groups;
			$query->where('a.access IN ('.$groups.')');
		}
		
		$query->group('a.artist');

		// Get the genre items.
		$db->setQuery($query);

		$genre_items = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		 }
		return $genre_items;
    }
}