<?php
/**
 * Contains the Genre viewer.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 * This artist viewer collects all necessary data and assign the data to the template output.
 *
 * @package Joomla 1.6.x and PlayJoom 0.9.x
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
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewGenre extends JViewLegacy {

	protected $items;
	protected $attachplaylists;
	protected $pagination;
	protected $state;

	// Overwriting JView display method
	function display($tpl = null) {

		// Assign data to the view

		// Get data from the model
		$items       = $this->get('Items');
		$PLitems     = $this->get('Attachplaylists');
		$pagination  = $this->get('Pagination');
		$genre	     = $this->get('Genre');

		//Get setting values from xml file
		$app		= JFactory::getApplication();
		$params		= $app->getParams();

		JPluginHelper::importPlugin('playjoom');
		$dispatcher	= JDispatcher::getInstance();

		// Assign data to the view
		$this->items = $items;
		$this->plitems = $PLitems;
		$this->pagination = $pagination;
		$this->genre = $genre;

		//For filter and ordering function
		$this->state = $this->get('State');
		$this->authors = $this->get('Authors');

		$this->assignRef('params',		$params);

		$this->_prepareDocument();

		$this->events = new stdClass;

		$results = $dispatcher->trigger('onBeforePJContent', array(&$items, &$this->params, 'genre'));
		$this->events->onBeforePJContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterPJContent', array(&$items, &$this->params, 'genre'));
		$this->events->onAfterPJContent = trim(implode("\n", $results));

		// add style sheet
		if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
			$document	= JFactory::getDocument();
			$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');
			$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/filter.css');
		}

		JHtml::_('formbehavior.chosen', 'select');

		parent::display($tpl);
	}

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {

    	//Get url query
    	$album = base64_decode(JRequest::getVar('album'));


		$app	= JFactory::getApplication();
		$pathway = $app->getPathway();

		//$pathway->addItem($cat, PlayJoomHelperRoute::getPJlink('artists'));
		if (isset($this->genre->id)) {
			$pathway->addItem($this->genre->title, PlayJoomHelperRoute::getPJlink('genres','&catid='.$this->genre->id));
		}
		if (isset($this->genre->title)) {
			//Set Page title
			$this->document->setTitle($app->getCfg('sitename').' - Genre: '.$this->genre->title);
		}
	}
}
