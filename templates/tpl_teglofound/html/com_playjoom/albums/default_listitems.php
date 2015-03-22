<?php
/**
 * Contains the default body template for the albums output.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Site
 * @subpackage views.artists.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2012-04-08 14:07:01 +0200 (So, 08. Apr 2012) $
 * @revision $Revision: 455 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/helpers/playjoom.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

$counter = null;

foreach($this->items as $i => $item) {

	//Create strings
	$albumsting = base64_encode($item->album);
	$artiststing = base64_encode($item->artist);

	//Check for sampler
	$SamplerCheck = PlayJoomHelper::checkForSampler($item->album,$item->artist);
	if ($SamplerCheck) {
		$artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
    } else {
    	$artistlink = PlayJoomHelperRoute::getPJlink('artists','&artist='.$artiststing);
    	$artistname = $item->artist;
    }

	//Create links
	$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
	$genrelink = PlayJoomHelperRoute::getPJlink('genres','&catid='.$item->catid);

	//Get Album thumbnail
	if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
		$cover = new PlayJoomHelperCover();
		$coverthumb = $cover->getCoverHTMLTag($item, $SamplerCheck);
	}

    $genre = null;
    $counter ++;

    echo '<li><a href="'.$albumlink.'">'.$counter.' - '.$artistname.' - '.$item->album.'</a></li>';
    echo '<li class="divider"></li>';
}
echo '<p class="counter">';
	echo $this->pagination->getPagesCounter();
echo '</p>';
echo $this->pagination->getListFooter();