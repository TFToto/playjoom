<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );


/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewAlphabetical extends JViewLegacy {

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
	
		$this->input_items = JFactory::getApplication()->input;
	
		parent::__construct($config);
	}

	// Overwriting JView display method
	function display($tpl = null) {
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

                // add style sheet
                if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
                	$document	= JFactory::getDocument();
                	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/filter.css');
                }
		
		// Set the document
		$this->setDocument();
                //$this->_prepareDocument($params);

                parent::display($tpl);
        }

        /**
         * Prepares the document
         */
        protected function _prepareDocument($params) {

        	$app	  = JFactory::getApplication();
        	$document = JFactory::getDocument();

        	// add style sheet
            if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
                $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/filter.css');
                $document->addStyleSheet(JURI::root(true).'/components/com_playjoom/assets/css/playlist_view.css');
            }

        	//Pathway settings
        	if (JRequest::getVar('LetterForAlphabetical')) {
        		$pathway = $app->getPathway();
        	    $pathway->addItem(JRequest::getVar('LetterForAlphabetical'), PlayJoomHelperRoute::getPJlink('alphabetical', '&LetterForAlphabetical='.JRequest::getVar('LetterForAlphabetical')));
        	}

        	//Set Page title
        	$this->document->setTitle($app->getCfg('sitename').' - '.JRequest::getVar('LetterForAlphabetical'));
        }
	
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() {

    	//load javascripts
		JHtml::_('jquery.framework');

		//load PlayJoom scripts
		JHtml::addIncludePath(JPATH_LIBRARIES . '/playjoom/cms/html');
		JHtml::_('Cover.library',$this->input_items->get('view'));

		$document = JFactory::getDocument();

		$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/album_view.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/artist_view.css');

	}
}
