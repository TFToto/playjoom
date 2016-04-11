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
 * Class for artists viewer
 * 
 * @package PlayJoom.Site
 * @subpackage views.albums
 */
class PlayJoomViewAlbums extends JViewLegacy {
	
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
	/**
     * Method to display the view
     *
     * @access    public
     */
    function display($tpl = null) {
    	
    	//Get setting values from xml file
		$app  = JFactory::getApplication();
		$this->params	= $app->getParams();
		
		$this->pagination = $this->get('Pagination');
		
		//Get Filter items
		$this->FilterItemsArtists = $this->get('FilterOptionsArtists');
		$this->FilterItemsYears   = $this->get('FilterOptionsYears');
		$this->FilterItemsGenres  = $this->get('FilterOptionsGenres');

		//For filter and ordering function
		$this->state = $this->get('State');
		$this->authors = $this->get('Authors');

		$complete_output =array();

		/**
		 * Build genres array output
		 */
		foreach($this->get('Items') as $i => $albums_items){

			$album_base64    = base64_encode($albums_items->album);
			$artist_base64   = base64_encode($albums_items->artist);
			$category_base64 = base64_encode($albums_items->category_title);
		
			//Create item for Samplercheck
			$sampler_check = PlayJoomHelper::checkForSampler($albums_items->album, $albums_items->artist);
			if($sampler_check) {
				$albums_items->sampler = true;
			}
		
			//Create cover link
			$albums_items->coverlink = JRoute::_(PlayJoomHelperRoute::getCoverRoute($albums_items,$album_base64,$artist_base64,$category_base64, $this->input_items->get('view')));
		
			//Create album link
			$albums_items->albumlink = PlayJoomHelper::createAlbumlink($albums_items,$album_base64,$artist_base64,$category_base64);
		
			//Create Item Title
			$albums_items->itemtitle = PlayJoomHelper::createItemtitle($albums_items, $sampler_check);
			
			array_push($complete_output, $albums_items);
		}
		
		$this->items = $complete_output;
		
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