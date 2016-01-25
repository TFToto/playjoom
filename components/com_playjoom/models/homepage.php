<?php
/**
 * @package Joomla 3.0
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modellist');
 
/**
 * PlayJoom Model for homepage part
 */
class PlayJoomModelHomepage extends JModelList {
	
    public function __construct($config = array()) {
		parent::__construct($config);
	}
    
    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function populateState($ordering = null, $direction = null) {		
	}
    
    protected function getListQuery() {
	
	}
	
	/**
	 * Method for to get a list of new Artists or Bands
	 * 
	 * @param  array   $params
	 * @return string  An SQL query
	 */
	public static function getArtistList() {
		
		$dispatcher	= JDispatcher::getInstance();
		
		//Get User Objects
		$user	= JFactory::getUser();
		$db		= JFactory::getDbo();
		
		//Get setting values from xml file
		$app  = JFactory::getApplication();
		$params	= $app->getParams();
		
		// Get an instance of the generic tracks model
		$model = JModelLegacy::getInstance('Artists', 'PlayjoomModel', array('ignore_request' => true));
		
		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count_artist', 5));
		
		// Set ordering
		
		$order_map = array(
				'm_dsc' => 'a.mod_datetime DESC, a.add_datetime',
				'mc_dsc' => 'CASE WHEN (a.mod_datetime = '.$db->quote($db->getNullDate()).') THEN a.add_datetime ELSE a.mod_datetime END',
				'c_dsc' => 'a.add_datetime',
		);
		
		
		$ordering = JArrayHelper::getValue($order_map,null);
		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering_artist'));
		$dir = 'DESC';
		
		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);
		
		$items = $model->getItems();

		return $items;
	}
	
	/**
	 * Method for to get a list of new Albums
	 *
	 * @param  array   $params
	 * @return string  An SQL query
	 */
	public static function getAlbumList() {
	
		$dispatcher	= JDispatcher::getInstance();
	
		//Get User Objects
		$user	= JFactory::getUser();
		$db		= JFactory::getDbo();
	
		//Get setting values from xml file
		$app  = JFactory::getApplication();
		$params	= $app->getParams();
	
		// Get an instance of the generic tracks model
		$model = JModelLegacy::getInstance('Albums', 'PlayjoomModel', array('ignore_request' => true));
	
		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count_album', 5));
	
		// Set ordering
	
		$order_map = array(
				'm_dsc' => 'a.mod_datetime DESC, a.add_datetime',
				'mc_dsc' => 'CASE WHEN (a.mod_datetime = '.$db->quote($db->getNullDate()).') THEN a.add_datetime ELSE a.mod_datetime END',
				'c_dsc' => 'a.add_datetime',
		);
	
	
		$ordering = JArrayHelper::getValue($order_map,null);
		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering_album'));
		$dir = 'DESC';
	
		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);
	
		$items = $model->getItems();
	
		return $items;
	}
	
	/**
	 * Method for to get a list of playlists
	 *
	 * @param  array   $params
	 * @return string  An SQL query
	 */
	public static function getPlaylistList() {
	
		$dispatcher	= JDispatcher::getInstance();
	
		//Get User Objects
		$user	= JFactory::getUser();
		$db		= JFactory::getDbo();
	
		//Get setting values from xml file
		$app  = JFactory::getApplication();
		$params	= $app->getParams();
	
		// Get an instance of the generic tracks model
		$model = JModelLegacy::getInstance('adminplaylists', 'PlayjoomModel', array('ignore_request' => true));
	
		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count_playlist', 5));
	
		// Set ordering
	
		$order_map = array(
				'm_dsc' => 'l.modifier_date DESC, l.create_date',
				'mc_dsc' => 'CASE WHEN (l.modifier_date = '.$db->quote($db->getNullDate()).') THEN l.create_date ELSE l.modifier_date END',
				'c_dsc' => 'l.create_date',
		);
	
	
		$ordering = JArrayHelper::getValue($order_map,null);
		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering_playlist'),'m_dsc');
		$dir = 'DESC';
	
		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);
	
		$items = $model->getItems();
	
		return $items;
	}
}