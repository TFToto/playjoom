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
 * @copyright Copyright (C) 2010-2014 by www.teglo.info
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

class PlayJoomViewCovers extends JViewLegacy {

	protected $items;

	protected $pagination;

	protected $state;

	/**
	* PlayJoom view display method
	* @return void
	*/
	function display($tpl = null) {

		if ($this->getLayout() !== 'modal') {
			PlayJoomHelper::addSubmenu('covers');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		$this->items         = $this->get('Items');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->pagination    = $this->get('Pagination');

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
	* Setting the toolbar
	*/
	protected function addToolBar() {

		$canDo = PlayJoomHelper::getActions();
		JToolBarHelper::title(JText::_('COM_PLAYJOOM_COVER_MANAGER_PLAYJOOM'), 'covers');

		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('cover.add', 'COM_PLAYJOOM_TOOLBAR_ADD_NEW_COVER');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('cover.edit', 'COM_PLAYJOOM_TOOLBAR_EDIT_COVER');
		}
		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList(JText::_('COM_PLAYJOOM_REALLY_DELETE'), 'covers.delete', 'COM_PLAYJOOM_TOOLBAR_DELETE_COVER');
		}
		if ($canDo->get('core.admin')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_playjoom');
		}
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