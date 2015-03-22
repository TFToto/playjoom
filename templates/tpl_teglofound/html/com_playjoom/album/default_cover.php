<?php
/**
 * @package Joomla 1.6.x
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/album/tmpl/default_cover.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$item = (object) array('artist' => base64_decode(JRequest::getVar('artist')), 'album' => base64_decode(JRequest::getVar('album')));
$SamplerCheck = PlayJoomHelper::checkForSampler($item->album, $item->artist);

//Get Album cover
if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
	$cover = new PlayJoomHelperCover();
	$coverthumb = $cover->getCoverHTMLTag($item, $SamplerCheck, $this->params);
}

echo '<div class="cover-row">';
//Output cover image
echo '<div class="coverimg">';
    echo $coverthumb;
echo '</div>';

$length_counter = null;
$size_counter = null;

foreach($this->items as $i => $item) {

	//Couters for the total line
	$length_counter = $length_counter + $item->length;
	$size_counter = $size_counter + $item->filesize;

	//calculate the average values
	if (isset($i) && $i >= 3) {
		$length_average = $length_counter / $i;
		$size_average = $size_counter / $i;

		if ($i == (count($this->items)-1)) {
			echo '<ul class="album-sum">';
			echo '<li>'.JText::_('COM_PLAYJOOM_ALBUM_NO_TRACKS').'&nbsp;'.count($this->items).'</li>';
			echo '<li>'.JText::_('COM_PLAYJOOM_ALBUM_TOTAL').'&nbsp;'.PlayJoomHelper::Playtime($length_counter).' '.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').'</li>';
			echo '<li>'.JText::_('COM_PLAYJOOM_ALBUM_AVERAGE').'&nbsp;'.PlayJoomHelper::Playtime($length_average).' '.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').'</li>';
			echo '</ul>';
		}
	}
}
echo '</div>';