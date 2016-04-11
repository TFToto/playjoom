<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license https://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modellist');
 
/**
 * PlayJoom Model
 */
class PlayJoomModelItems extends JModelList
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
    protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		//$app = JFactory::getApplication();	
		//$session = JFactory::getSession();
		
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
		$query->select($this->getState('list.select','a.id, a.artist, a.album, a.year, a.catid, a.access, a.add_by, a.pathatlocal, a.coverid'));
		$query->from('#__jpaudiotracks AS a');
        
        // Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the categories.
		$query->select('b.mime');
		$query->join('LEFT', '#__jpcoverblobs AS b ON b.id = a.coverid');

		$query->group('album');
		
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
		
		/*
		 * Order settings
		 */
		if ($itemorder = $this->getState('itemorder')) {
			$query->order($itemorder);
		}

		/*
		 * Filter settings
		*/
		// Filter by Category.
		if ($category_id = $this->getState('filter.category_id')) {
			$query->where('a.catid = "' . (int)$category_id.'"');
		}

		return $query;
	}
}