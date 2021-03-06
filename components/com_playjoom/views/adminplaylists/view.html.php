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

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );

 
/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewAdminPlaylists extends JViewLegacy
{
	protected $data;
	protected $form;
    protected $params;
	protected $state;
	
        // Overwriting JView display method
        function display($tpl = null) 
        {
                                
                // Get data from the model
                $items       = $this->get('Items');
                $playlistinfo = $this->get('PlaylistInfo');
                $pagination  = $this->get('Pagination');
               
                //Get setting values from xml file
                $app		= JFactory::getApplication();
                $params		= $app->getParams();
                
                // Assign data to the view
                $this->items = $items;
                $this->playlistinfo = $playlistinfo;
                $this->pagination = $pagination;
                
                // Get the view data.
		        $this->data	= $this->get('Data');
		        $this->form	 = $this->get('Form');
		        $this->state = $this->get('State');
		        $this->params = $this->state->get('params'); 
		        
                $this->assignRef('params',		$params);
                
                parent::display($tpl);
        }
}
