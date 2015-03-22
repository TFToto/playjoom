<?php
/**
 * Contains the Viewer method for to collect all necessary data and assign it to the template output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Site
 * @subpackage views.tracks
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );

 
/**
 * Class for artists viewer
 * 
 * @package PlayJoom.Site
 * @subpackage views.tracks
 */
class PlayJoomViewTracks extends JViewLegacy {
	
	/**
     * Method to display the view
     *
     * @access    public
     */
    function display($tpl = null) {
    	
    	// Get data from the model
        $items       = $this->get('Items');
        
        $FilterOptionsArtists = $this->get('FilterOptionsArtists');
        $FilterOptionsAlbums  = $this->get('FilterOptionsAlbums');
        $FilterOptionsYears   = $this->get('FilterOptionsYears');
        $FilterOptionsGenres  = $this->get('FilterOptionsGenres');
        
        $pagination  = $this->get('Pagination');
                
        //Get setting values from xml file
        $app		= JFactory::getApplication();
        $params		= $app->getParams();
                
        // Assign data to the view
        $this->items = $items;
        //For filter options
        $this->FilterItemsArtists = $FilterOptionsArtists;
        $this->FilterItemsAlbums  = $FilterOptionsAlbums;
        $this->FilterItemsYears   = $FilterOptionsYears;
        $this->FilterItemsGenres  = $FilterOptionsGenres;
        
        $this->pagination = $pagination;
                
        //For filter and ordering function
        $this->state = $this->get('State');
        $this->authors = $this->get('Authors');
                
        $this->assignRef('params',		$params);
                
        // add style sheet
        if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
        	$document	= JFactory::getDocument();
        	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');
        	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/filter.css');
        }
        
        parent::display($tpl);
    }
}
