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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class PlayJoomViewPlayJooms extends JViewLegacy
{
        /**
         * PlayJoom view display method
         * @return void
         */
        function display($tpl = null) 
        {
                // Get data from the model
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;
                
                // Set the toolbar
                $this->addToolBar();
                
                // Display the template
                parent::display($tpl);
 
                // Set the document
                $this->setDocument();
        }
/**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $canDo = PlayJoomHelper::getActions();
                JToolBarHelper::title(JText::_('COM_PLAYJOOM_MANAGER_PLAYJOOMS'), 'playjoom');
                if ($canDo->get('core.create')) 
                {
                        JToolBarHelper::addNew('playjoom.add', 'JTOOLBAR_NEW');
                }
                if ($canDo->get('core.edit')) 
                {
                        JToolBarHelper::editList('playjoom.edit', 'JTOOLBAR_EDIT');
                }
                if ($canDo->get('core.delete')) 
                {
                        JToolBarHelper::deleteList('', 'playjoom.delete', 'JTOOLBAR_DELETE');
                }
                if ($canDo->get('core.admin')) 
                {
                        JToolBarHelper::divider();
                        JToolBarHelper::preferences('com_playjoom');
                }
        }
        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('COM_PLAYJOOM_ADMINISTRATION'));
        }
}
