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
class PlayJoomViewGenres extends JViewLegacy {
	
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

		$app  = JFactory::getApplication();
		$this->params	= $app->getParams();

		//Get parameters for current menu item
		$active       = $app->getMenu()->getActive();
		//Save number of covers
		JFactory::getApplication()->setUserState('com_playjoom.genres.numcover', $active->params->get('number_of_cover',5));

		//For filter and ordering function
		$this->state = $this->get('State');
		$this->authors = $this->get('Authors');

		// Set the document
		$this->setDocument();
		$this->pagination = $this->get('Pagination');

		/**
		 * Build genres array output
		 */
		$complete_output =array();

		//Build output array for json data
		foreach($this->get('Items') as $i => $item){

			// Get an instance of the generic items model for albums
			$model = JModelLegacy::getInstance('Items', 'PlayjoomModel', array('ignore_request' => true));

			// Set the filters based on the module params
			$model->setState('list.start', 0);
			$model->setState('list.limit', JFactory::getApplication()->getUserState('com_playjoom.genres.numcover'));
			
			// Set the order params
			//$model->setState('list.start', 0);
			//$model->setState('list.limit', JFactory::getApplication()->getUserState('com_playjoom.genres.numcover'));

			// Access filter
			$access = !JComponentHelper::getParams('com_playjoom')->get('show_noauth', 1);
			$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
			$model->setState('filter.access', $access);

			$model->setState('itemorder','RAND()');

			// Category filter
			$model->setState('filter.category_id', (int)$item->catid);

			$genre_albums = $model->getItems();

			foreach($genre_albums as $i => $albums_items) {

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
			}

			$genre_output = array(
					$item->category_title => $genre_albums,
					'catid' => $item->catid
			);
			array_push($complete_output, $genre_output);
		}

		$output_array = array(
				'genre_list' => $complete_output
		);

		$this->genres = $output_array;

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