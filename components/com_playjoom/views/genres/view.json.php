<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license https://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );

/**
 * HTML View class for the PlayJoom Home stating page
 */
class PlayJoomViewGenres extends JViewLegacy {

	// Overwriting JView display method
	function display($tpl = null) {

		$this->state = $this->get('State');
		$this->authors = $this->get('Authors');

		$complete_output =array();

		//Build output array for json data
		foreach($this->get('Items') as $i => $item){

			// Get an instance of the generic items model for albums
			$model = JModelLegacy::getInstance('Items', 'PlayjoomModel', array('ignore_request' => true));

			// Set the filters based on the module params
			$model->setState('list.start', 0);
			$model->setState('list.limit', JFactory::getApplication()->getUserState('com_playjoom.genres.numcover'));

			// Access filter
			$access = !JComponentHelper::getParams('com_playjoom')->get('show_noauth', 1);
			$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
			$model->setState('filter.access', $access);

			$model->setState('itemorder','RAND()');

			// Category filter
			$model->setState('filter.category_id', (int)$item->catid);
			
			$genre_output = array(
					$item->category_title => $model->getItems(),
					'catid' => $item->catid
			);
			array_push($complete_output, $genre_output);
			
		}
		
		$output_array = array(
				'genre_list' => $complete_output
		);

		echo json_encode($output_array);
	}
}