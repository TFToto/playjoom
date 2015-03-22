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
 * @date $Date: 2012-12-25 17:41:19 +0100 (Di, 25 Dez 2012) $
 * @revision $Revision: 644 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/sections/view.html.php $
 */

defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );

 
/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewSections extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                //$this->item = $this->get('Item');
                                
                // Get data from the model
                $items       = $this->get('Items');
                $pagination  = $this->get('Pagination');
                
                //Get setting values from xml file
                $app		= JFactory::getApplication();
                $params		= $app->getParams();
                
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;
                
                //For filter and ordering function
                $this->state = $this->get('State');
                $this->authors = $this->get('Authors');
                
                $this->assignRef('params',		$params);
                
                parent::display($tpl);
        }
}
