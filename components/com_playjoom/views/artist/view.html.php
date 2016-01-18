<?php
/**
 * Contains the artist viewer.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 * This artist viewer collects all necessary data and assign the data to the template output.
 *
 * @package Joomla 3.0.x and PlayJoom 0.9.x
 * @copyright Copyright (C) 2010-2014 by www.teglo.info. All rights reserved.
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

JLoader::import( 'helpers.artist', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewArtist extends JViewLegacy {

	protected $items;
	protected $albumitems;
	protected $attachplaylists;
	protected $pagination;
	protected $state;


    /**
     * Overwriting JView display method
     */
    function display($tpl = null) {

    	// Get data from the model
        $this->items       = $this->get('Items');
        $this->albumitems  = $this->get('Albumitems');
        $this->TrackFilter = $this->get('TrackFilter');
        $this->plitems     = $this->get('Attachplaylists');
        $this->pagination  = $this->get('Pagination');

        //Get setting values from xml file
        $app    = JFactory::getApplication();
        $params	= $app->getParams();

        //For filter and ordering function
        $this->state = $this->get('State');

        $this->assignRef('params',		$params);

        JPluginHelper::importPlugin('playjoom');
        $dispatcher	= JDispatcher::getInstance();

        $this->events = new stdClass;

        $results = $dispatcher->trigger('onBeforePJContent', array(&$this->items, &$this->params, 'artist'));
        $this->events->onBeforePJContent = trim(implode("\n", $results));

        $results = $dispatcher->trigger('onAfterPJContent', array(&$this->items, &$this->params, 'artist'));
        $this->events->onAfterPJContent = trim(implode("\n", $results));

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Method for to prepares the document artist
     */
    protected function _prepareDocument() {

    	//Get url query
    	$artist = base64_decode(JRequest::getVar('artist'));
    	$genre = base64_decode(JRequest::getVar('cat'));

    	$app	= JFactory::getApplication();
    	$pathway = $app->getPathway();

    	//Add item to pathway
    	if ($genre) {
    		$pathway->addItem($genre, PlayJoomHelperRoute::getPJlink('genres','&cat='.JRequest::getVar('cat').'&catid='.JRequest::getVar('catid')));
    		$pathway->addItem($artist, PlayJoomHelperRoute::getPJlink('artists','&artist='.JRequest::getVar('artist').'&cat='.JRequest::getVar('cat').'&catid='.JRequest::getVar('catid')));
    	} else {
    		$pathway->addItem($artist, PlayJoomHelperRoute::getPJlink('artists'));
    	}

    	//load javascript and css script
    	$document	= JFactory::getDocument();

    	// add style sheet
    	if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
    		$document	= JFactory::getDocument();
    		$document->addStyleSheet(JURI::root(true).'/components/com_playjoom/assets/css/playlist_view.css');
    		$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/album_view.css');
    		$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/artist_view.css');
    		$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/filter.css');
    	}

    	//Set Page title
    	$this->document->setTitle($app->getCfg('sitename').' - Alben von '.$artist);
    }
}
