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
 * @subpackage views.audiotracks
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2014 by www.teglo.info. All rights reserved.
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
 * Class for audiotracks viewer
 *
 * @package PlayJoom.Admin
 * @subpackage views.audiotracks
 */
 class PlayJoomViewAudioTracks extends JViewLegacy {

 	/**
     * Method to display the view
     *
     * @access public
     * @return	void
     */
    function display($tpl = null) {

		// Get data from the model
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		// For filter function
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		//Get setting values from xml file
		$this->params = JComponentHelper::getParams('com_playjoom');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
		return false;
		}

		PlayJoomHelper::addSubmenu('audiotracks');

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

    	// set some global property
        $canDo = PlayJoomHelper::getActions();
        JToolBarHelper::title(JText::_('COM_PLAYJOOM_AUDIOTRACK_MANAGER_PLAYJOOM'), 'audiotracks');

        if ($canDo->get('core.create')) {

        	$bar = JToolBar::getInstance('toolbar');

        	//New tracks button
        	$title = JText::_('COM_PLAYJOOM_TOOLBAR_ADD_TRACKS');
		    $dhtml = "<button onclick=\"window.location = 'index.php?option=com_playjoom&view=media';\" class=\"btn btn-small btn-success\">
		    <i class=\"icon-music icon-white\" title=\"$title\"></i>
		    $title</button>";

        	$bar->appendButton('Custom', $dhtml, 'Add_folder-tracks');
        	$bar->appendButton('Standard', 'music', 'COM_PLAYJOOM_TOOLBAR_ADD_TRACK', 'audiotrack.add', false);
        }

        if ($canDo->get('core.edit')) {
            JToolBarHelper::editList('audiotrack.edit', 'COM_PLAYJOOM_TOOLBAR_EDIT_TRACK');
        }

        if ($canDo->get('core.delete')) {
            JToolBarHelper::deleteList(JText::_('COM_PLAYJOOM_REALLY_DELETE'), 'audiotracks.delete', 'COM_PLAYJOOM_TOOLBAR_DELETE_TRACK');
        }

        // Add a batch button
        if ($canDo->get('core.edit')) {

        	JHtml::_('bootstrap.modal', 'collapseModal');
        	$title = JText::_('JTOOLBAR_BATCH');

        	// Instantiate a new JLayoutFile instance and render the batch button
        	$layout = new JLayoutFile('joomla.toolbar.batch');

        	$dhtml = $layout->render(array('title' => $title));
        	$bar->appendButton('Custom', $dhtml, 'batch');

        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_playjoom');
        }

        //Set sidebar content
        JHtmlSidebar::setAction('index.php?option=com_playjoom&view=audiotracks');
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
    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields() {
    	return array(
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.hits' => JText::_('JAUTHOR'),
			'a.add_datetime' => JText::_('JDATE'),
		);
    }
}