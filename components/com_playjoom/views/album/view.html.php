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
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewAlbum extends JViewLegacy {

	protected $items;
	protected $attachplaylists;

	// Overwriting JView display method
	function display($tpl = null) {

		// Get data from the model
		$items       = $this->get('Items');
		$PLitems     = $this->get('Attachplaylists');

		//Get setting values from xml file
		$app		= JFactory::getApplication();
		$params		= $app->getParams();
		$lang	= JFactory::getLanguage();

		JPluginHelper::importPlugin('playjoom');
		$dispatcher	= JDispatcher::getInstance();
		// Assign data to the view
		$this->items = $items;
		$this->plitems = $PLitems;

		$this->events = new stdClass;

		$results = $dispatcher->trigger('onBeforePJContent', array(&$items, &$this->params, 'album'));
		$this->events->onBeforePJContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterPJContent', array(&$items, &$this->params, 'album'));
		$this->events->onAfterPJContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('OnAfterTrackbox', array(&$items, &$this->params));
		$this->events->OnAfterTrackbox = trim(implode("\n", $results));


		//load params data
		$this->assignRef('params',		$params);

		$this->_prepareDocument($params);

		parent::display($tpl);
	}

	/**
	* Prepares the document
	*/
	protected function _prepareDocument($params) {

		//Get url query
		$artist = base64_decode(JRequest::getVar('artist'));
		$album = base64_decode(JRequest::getVar('album'));
		$genre = base64_decode(JRequest::getVar('cat'));


		$app	= JFactory::getApplication();

		//load javascript and css script
		$document	= JFactory::getDocument();

		// add style sheet
		if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
			$document->addStyleSheet(JURI::root(true).'/components/com_playjoom/assets/css/playlist_view.css');
			$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/album_view.css');
		}

		//Pathway settings
		$pathway = $app->getPathway();

		if ($genre) {
			$pathway->addItem($genre, PlayJoomHelperRoute::getPJlink('genres','&cat='.JRequest::getVar('cat').'&catid='.JRequest::getVar('catid')));
			$pathway->addItem($artist, PlayJoomHelperRoute::getPJlink('artists','&artist='.JRequest::getVar('artist').'&cat='.JRequest::getVar('cat').'&catid='.JRequest::getVar('catid')));
		} else {
			$pathway->addItem($artist, PlayJoomHelperRoute::getPJlink('artists'));
		}
		$pathway->addItem($album, PlayJoomHelperRoute::getPJlink('albums'));

		//Set Page title
		$this->document->setTitle($app->getCfg('sitename').' - '.$album.' von '.$artist);
	}
}