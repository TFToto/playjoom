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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

class PlayJoomViewAlbums extends JViewLegacy
{
        /**
         * PlayJoom view display method
         * @return void
         */
        function display($tpl = null) {
        	
        	if ($this->getLayout() !== 'modal')
        	{
        		PlayJoomHelper::addSubmenu('albums');
        	}
        	
                // Get data from the model
                $items       = $this->get('Items');
                $FilterOptionsArtists = $this->get('FilterOptionsArtists');
                $FilterOptionsGenres  = $this->get('FilterOptionsGenres');
                
                $pagination  = $this->get('Pagination');
                //For filter function
                $this->state = $this->get('State');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                  //      JError::raiseError(500, implode('<br />', $errors));
                    //    return false;
                }
                // Assign data to the view
                $this->items = $items;
                //For filter options
                $this->FilterItemsArtists = $FilterOptionsArtists;
                $this->FilterItemsGenres  = $FilterOptionsGenres;
                
                $this->pagination = $pagination;
                
        // We don't need toolbar in the modal window.
        if ($this->getLayout() !== 'modal') {
        	$this->addToolbar();
        	$this->sidebar = JHtmlSidebar::render();
        }
                
                // Display the template
                parent::display($tpl);
 
                // Set the document
                $this->setDocument();
                
                //echo "test aus view Datei: ";
                
                
        }
/**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $canDo = PlayJoomHelper::getActions();
                JToolBarHelper::title(JText::_('COM_PLAYJOOM_ALBUM_MANAGER_PLAYJOOM'), 'albums');
                if ($canDo->get('core.create')) 
                {
                        JToolBarHelper::addNew('album.add', 'COM_PLAYJOOM_TOOLBAR_ADD_NEW_ALBUM');
                }
                if ($canDo->get('core.edit')) 
                {
                        JToolBarHelper::editList('album.edit', 'COM_PLAYJOOM_TOOLBAR_EDIT_ALBUM');
                }
       
                if ($canDo->get('core.delete')) 
                {
                       //JToolBarHelper::deleteList('audiotrack.delete', 'JTOOLBAR_DELETE');
                       JToolBarHelper::deleteList(JText::_('COM_PLAYJOOM_REALLY_DELETE'), 'albums.delete', 'COM_PLAYJOOM_TOOLBAR_DELETE_ALBUM');
                       
                }
                if ($canDo->get('core.admin')) 
                {
                        JToolBarHelper::divider();
                        JToolBarHelper::preferences('com_playjoom');
                }
                
                //Set sidebar content
        JHtmlSidebar::setAction('index.php?option=com_playjoom&view=audiotracks');
        
        JHtmlSidebar::addFilter(
        JText::_('COM_PLAYJOOM_FILTER_ARTIST'),
        'filter_artist',
        JHtml::_('select.options', $this->FilterItemsArtists, 'value', 'text', $this->state->get('filter.artist'))
        );
        
        JHtmlSidebar::addFilter(
        JText::_('COM_PLAYJOOM_FILTER_GENRE'),
        'filter_category_id',
        JHtml::_('select.options', $this->FilterItemsGenres, 'value', 'text', $this->state->get('filter.category_id'))
        );
        
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
