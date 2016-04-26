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

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

/*
echo '<div class="albumsview">';
	echo '<h4>'.JText::_('COM_PLAYJOOM_HOMEPAGE_NEWALBUMS').'</h4>';
	echo '<ul class="list_of_albums"></ul>';
echo '</div>';
*/

//Build List of genre titles
echo '<div class="albumsview">';
foreach($this->albumitems as $i => $item){

	echo '<ul class="list_of_albums">';
	echo '<li class="album_item"><a title="Continue to the album view" href="'.$item->albumlink.'"><img class="cover" data-src="'.$item->coverlink.'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" />'.$item->itemtitle.'</a></li>';
	echo '</ul>';
}
echo '</div>';
