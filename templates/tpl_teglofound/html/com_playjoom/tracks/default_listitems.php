<?php
/**
 * Contains the default template for the artist output.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Site
 * @subpackage views.tracks.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2013-09-08 14:20:12 +0200 (So, 08 Sep 2013) $
 * @revision $Revision: 829 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/tracks/tmpl/default_body.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

$counter    = null;
$coverwidth =  $this->params->get('maxsize_cover');
$coverstate =  $this->params->get('show_cover');
$dispatcher	= JDispatcher::getInstance();

foreach($this->items as $i => $item) {

	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		if ($coverstate == 1) {
			$coverthumb = PlayJoomHelper::getCoverThumb($item->album, $item->artist, JPATH_BASE.DS.'tmp'.DS.'tmp_img_albumtumb'.$i, $coverwidth, $i).'<br />';
		}
		else {
		    $coverthumb = null;
		}

		//Check for albumname as sampler
		if (PlayJoomHelper::checkForSampler($item->album, $item->artist)) {
			$albumname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
		} else {
			$albumname = $item->album;
		}

		//Create strings
		$albumsting = base64_encode($item->album);
		$genresting = base64_encode($item->category_title);
		$artiststing = base64_encode($item->artist);

		//Create links
		$albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
		$genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid');
		$artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');

		$counter ++;

		echo '<li>'.sprintf ("%02d", $counter).' - <a href="'.$artistlink.'">'.$item->artist.'</a> ';

		//Plugins integration
        $this->events = new stdClass();
        $results = $dispatcher->trigger('onPrepareTrackLink', array(&$item, $this->params));
	    $this->events->PrepareTrackLink = trim(implode("\n", $results));

	    $results = $dispatcher->trigger('onBeforeTrackLink', array(&$item, $this->params));
	    $this->events->BeforeTrackLink = trim(implode("\n", $results));

	    $results = $dispatcher->trigger('onAfterTrackLink', array(&$item, $this->params));
	    $this->events->AfterTrackLink = trim(implode("\n", $results));

		if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false) {
			echo $item->title;
		} else {
		    echo $this->events->BeforeTrackLink;
		    echo $this->events->PrepareTrackLink;
		    echo $this->events->AfterTrackLink;
		}
		echo '<br /><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.$albumname.'</a> (<a href="'.$genrelink.'" title="Continue to the genre view">'.$item->category_title.'</a>)';
		echo '</li>';
		echo '<li class="divider"></li>';
    }
}
echo '<p class="counter">';
	echo $this->pagination->getPagesCounter();
echo '</p>';
echo $this->pagination->getListFooter();