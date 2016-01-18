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
    
    	$input_items = JFactory::getApplication()->input;
    
    	$this->rootURL = rtrim(JURI::base(),'/');
    	$subpathURL = JURI::base(true);
    	if(!empty($subpathURL) && ($subpathURL != '/')) {
    		$this->rootURL = substr($this->rootURL, 0, -1 * strlen($subpathURL));
    	}
    
    	//load javascripts
    	JHtml::_('jquery.framework');
    
    	//load PlayJoom scripts
    	JHtml::addIncludePath(JPATH_LIBRARIES . '/playjoom/cms/html');
    	JHtml::_('AjaxData.library',$input_items->get('view'));
    
    	$document = JFactory::getDocument();
    
    	$js = "
				ajaxdata.init({
					view: '".$input_items->get('view')."',
					root_url: '".$this->rootURL.JRoute::_("index.php")."',
					itemid: ".$input_items->get('Itemid').",
				});
				ajaxdata.getAlbumlist();
		";
    	//load external scripts
    	$document->addScriptDeclaration($js);
    	
    	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/album_view.css');
    	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/artist_view.css');
    
    }
}