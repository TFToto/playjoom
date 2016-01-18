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

	// Overwriting JView display method
	function display($tpl = null) {

		$output_array = array(
				'artist_list' => $this->get('ArtistList'),
				'album_list' => $this->get('AlbumList'),
				'playlist_list' => $this->get('PlaylistList')
			);

		echo json_encode($output_array);
	}
}