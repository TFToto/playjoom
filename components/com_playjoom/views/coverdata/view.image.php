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

/**
 * HTML View class for the PlayJoom Component
 */
class PlayJoomViewCoverdata extends JViewLegacy {

	// Overwriting JView display method
	function display($tpl = null) {

		// Get data from the model
		$this->item = $this->get('Item');
		echo $this->item;
	}
}