<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=albums').'" method="post" name="adminForm" id="adminForm">';

	//Page Title configuration
    if ($this->params->get('show_page_heading') == 1) {
    	
    	echo '<div class="item-page'.$this->params->get('pageclass_sfx').'">';
    	
    	    if (!$this->escape($this->params->get('page_heading'))) {
    	    	echo '<h3 class="subheader">'.ucfirst(JRequest::getVar('view')).' | '.JText::_('COM_PLAYJOOM_HEADER_TITEL_TOTAL').' '.$this->pagination->total.'</h3>';
    	    }
    	    else { 
    		    echo '<h3 class="subheader">'.$this->escape($this->params->get('page_heading')).' | '.JText::_('COM_PLAYJOOM_HEADER_TITEL_TOTAL').' '.$this->pagination->total.'</h3>';
			}

		echo '</div>';
	}

	echo $this->loadTemplate('filter');
	
	echo '<div class="btn-group pull-right">';
		echo '<label for="limit" class="element-invisible">';
			echo JText::_('JGLOBAL_DISPLAY_NUM');
		echo '</label>';
		echo $this->pagination->getLimitBox();
	echo '</div>';
	
	echo $this->pagination->getListFooter();

	//Build List of genre titles
	echo '<div class="albumsview">';
	foreach($this->items as $i => $item){

		echo '<ul class="list_of_albums">';
			echo '<li class="album_item"><a title="Continue to the album view" href="'.$item->albumlink.'"><img class="cover" data-src="'.$item->coverlink.'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" />'.$item->itemtitle.'</a></li>';
		echo '</ul>';
	}
	echo '</div>';
	echo $this->pagination->getListFooter();

echo '</form>';
