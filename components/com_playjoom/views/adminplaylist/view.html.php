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
class PlayJoomViewAdminPlaylist extends JViewLegacy {

	// Overwriting JView display method
	function display($tpl = null) {

	// Get data from the model
	$items        = $this->get('Items');
	$playlistinfo = $this->get('PlaylistInfo');
	$pagination   = $this->get('Pagination');

	//Get setting values from xml file
	$app    = JFactory::getApplication();
	$params	= $app->getParams();

	// Assign data to the view
	$this->items        = $items;
	$this->playlistinfo = $playlistinfo;
	$this->pagination   = $pagination;
	$this->params       = $params;
	$this->state        = $this->get('State');

	JPluginHelper::importPlugin('playjoom');
	$dispatcher	= JDispatcher::getInstance();

	$this->events = new stdClass;

	$results = $dispatcher->trigger('onBeforePJContent', array(&$items, &$this->params, 'playlist', base64_encode($this->playlistinfo->name)));
	$this->events->onBeforePJContent = trim(implode("\n", $results));

	$results = $dispatcher->trigger('onAfterPJContent', array(&$items, &$this->params, 'playlist', base64_encode($this->playlistinfo->name)));
	$this->events->onAfterPJContent = trim(implode("\n", $results));

	$results = $dispatcher->trigger('OnAfterTrackbox', array(&$items, &$this->params));
	$this->events->OnAfterTrackbox = trim(implode("\n", $results));

	$this->_prepareDocument();

	parent::display($tpl);
}

	/**
	* Prepares the document
	*/
	protected function _prepareDocument() {

	//Get url query
	$cat = base64_decode(JRequest::getVar('cat'));
	$album = base64_decode(JRequest::getVar('album'));

	$app	= JFactory::getApplication();
	$pathway = $app->getPathway();

	$pathway->addItem($this->playlistinfo->name, null);

	//Set Page title
	$this->document->setTitle($app->getCfg('sitename').' - '.JText::_('COM_PLAYJOOM_PLAYLIST').': '.$this->playlistinfo->name);

	}
}