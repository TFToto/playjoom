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
 * HTML View class for the PlayJoom Home stating page
 */
class PlayJoomViewHomepage extends JViewLegacy {

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

		//Get setting values from xml file
		$app  = JFactory::getApplication();
		$this->params	= $app->getParams();
		
		//Get parameters for current menu item
		$active       = $app->getMenu()->getActive();
		
		//For filter and ordering function
		$this->state = $this->get('State');
		$this->authors = $this->get('Authors');
		
		// Get an instance of the generic items model for albums
		$db = JFactory::getDbo();
		$model = JModelLegacy::getInstance('Items', 'PlayjoomModel', array('ignore_request' => true));

		// Set the order params
		$model->setState('list.start', 0);
		$model->setState('list.limit', $active->params->get('count_album',5));
		
		// Access filter
		$access = !JComponentHelper::getParams('com_playjoom')->get('show_noauth', 1);
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Set ordering
		$order_map = array(
				'm_dsc' => 'a.mod_datetime DESC, a.add_datetime',
				'mc_dsc' => 'CASE WHEN (a.mod_datetime = '.$db->quote($db->getNullDate()).') THEN a.add_datetime ELSE a.mod_datetime END',
				'c_dsc' => 'a.add_datetime',
		);

		$ordering = JArrayHelper::getValue($order_map,null);
		$ordering = JArrayHelper::getValue($order_map, $active->params->get('ordering_album'));
		$dir = 'DESC';

		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);

		$homepage_albums = $model->getItems();

		$complete_output =array();

		/**
		 * Build genres array output
		 */
		foreach($homepage_albums as $i => $albums_items){
		
			$album_base64    = base64_encode($albums_items->album);
			$artist_base64   = base64_encode($albums_items->artist);
			$category_base64 = base64_encode($albums_items->category_title);
		
			//Create item for Samplercheck
			$sampler_check = PlayJoomHelper::checkForSampler($albums_items->album, $albums_items->artist);
			if($sampler_check) {
				$albums_items->sampler = true;
			}
		
			//Create cover link
			$albums_items->coverlink = PlayJoomHelper::createCoverlink($albums_items,$album_base64,$artist_base64,$category_base64, $this->input_items->get('view'));
		
			//Create album link
			$albums_items->albumlink = PlayJoomHelper::createAlbumlink($albums_items,$album_base64,$artist_base64,$category_base64);
		
			//Create Item Title
			$albums_items->itemtitle = PlayJoomHelper::createItemtitle($albums_items, $sampler_check);
				
			array_push($complete_output, $albums_items);
		}
		
		$this->albumitems = $complete_output;
		
		// Get data from the model
		$this->ArtistItems   = $this->get('ArtistList');
		$this->PlaylistItems = $this->get('PlaylistList');
		
		// Set the document
		$this->setDocument();
		
		parent::display($tpl);
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