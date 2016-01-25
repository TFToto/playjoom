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

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );

 
/**
 * HTML View class for the PlayJoom Home stating page
 */
class PlayJoomViewHomepage extends JViewLegacy {
	
	// Overwriting JView display method
    function display($tpl = null) {
    	
    	//Get setting values from xml file
        $app  = JFactory::getApplication();
        $this->params	= $app->getParams();
        
        // Get data from the model
        $this->ArtistItems       = $this->get('ArtistList');
        $this->AlbumItems       = $this->get('AlbumList');
        $this->PlaylistItems       = $this->get('PlaylistList');
         
        //Get setting values from xml file
        $app		= JFactory::getApplication();
        $params		= $app->getParams();
                
        parent::display($tpl);
    }
}
