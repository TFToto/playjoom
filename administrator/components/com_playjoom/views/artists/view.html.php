<?php
/**
 * Contains the Viewer method for to collect all necessary data and assign it to the template output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Admin
 * @subpackage views.artists
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Class for artists viewer
 *
 * @package PlayJoom.Admin
 * @subpackage views.artists
 */
class PlayJoomViewArtists extends JViewLegacy {
	
	protected $items;
	
	protected $pagination;
	
	protected $state;
	
	/**
     * Method to display the view
     *
     * @access    public
     * @return void
     */
    function display($tpl = null) {
    	
    	if ($this->getLayout() !== 'modal')
    	{
    		PlayJoomHelper::addSubmenu('artists');
    	}
    	
    	// Get data from the model
        $items       = $this->get('Items');
        $FilterOptionsGenres  = $this->get('FilterOptionsGenres');
        $pagination  = $this->get('Pagination');
                
        //For filter function
        $this->state = $this->get('State');
 
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
        	
        	JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        
        // Assign data to the view
        $this->items = $items;
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
    }
        
    /**
     * Method for to add the page title and toolbar for the audiotracks viewer.
     *
     * @since	1.6
     * @return void
     */
     protected function addToolBar() {
     	
     	$canDo = PlayJoomHelper::getActions();
     	
     	JToolBarHelper::title(JText::_('COM_PLAYJOOM_ARTIST_MANAGER_PLAYJOOM'), 'artists');
                
        if ($canDo->get('core.create')) {
        	JToolBarHelper::addNew('artist.add', 'COM_PLAYJOOM_TOOLBAR_ADD_NEW_ARTIST');
        }

        if ($canDo->get('core.edit')) {
        	JToolBarHelper::editList('artist.edit', 'COM_PLAYJOOM_TOOLBAR_EDIT_ARTIST');
        }
       
        if ($canDo->get('core.delete')) {
        	JToolBarHelper::deleteList(JText::_('COM_PLAYJOOM_REALLY_DELETE'), 'artists.delete', 'COM_PLAYJOOM_TOOLBAR_DELETE_ARTIST');
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_playjoom');
        }
        
        JHtmlSidebar::setAction('index.php?option=com_playjoom&view=albums');
        
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
    protected function setDocument() {
       $document = JFactory::getDocument();
       $document->setTitle(JText::_('COM_PLAYJOOM_ADMINISTRATION'));
    }
}