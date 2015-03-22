<?php
/**
 * @package Joomla 3.0
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/homepage/tmpl/default_newalbum.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

//echo print_r($this->AlbumItems);
echo '<div class="albumsview">';
echo '<h4>'.JText::_('COM_PLAYJOOM_HOMEPAGE_NEWALBUMS').'</h4>';
echo '<ul class="list_of_albums">';
foreach($this->AlbumItems as $i => $item) {

	$albumsting = base64_encode($item->album);
	$artiststing = base64_encode($item->artist);
    $albumlink = JRoute::_('index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid').'&cat='.base64_encode($item->category_title).'&catid='.$item->catid);

    //Get Album thumbnail
    if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {

    	$Cover = new PlayJoomHelperCover();

    	$CoverData = $Cover->getAlbumCover($item);

    	if ($this->params->get('save_cover_tmp', 0) == 1) {
    		$file = JApplication::stringURLSafe($item->artist.'-'.$item->album);
    		$path = $Cover->getFilePath();

    		if (!$Cover->checkFileExists($path, $file, $this->params)) {
    			$Cover->createCoverfile($CoverData, $path, $file, $this->params);
    		}
    	}

    	$coverthumb = $Cover->getCoverThumb($CoverData, $item, $this->params).'<br />';
    } else {
    	$coverthumb = null;
    }
	echo '<li class="genre_item"><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.$item->album.'</a> ('.$item->year.')</li>';
}
echo '<ul>';
echo '</div>';