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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
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

		echo '<tr>';
		    echo '<td>'.$counter .'</td>';
		    echo '<td><a href="'.$artistlink.'">'.$item->artist.'</a></td>';
		    echo '<td valign="top"><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.$albumname.'</a></td>';

		    //Plugins integration
            $this->events = new stdClass();
            $results = $dispatcher->trigger('onPrepareTrackLink', array(&$item, $this->params));
	        $this->events->PrepareTrackLink = trim(implode("\n", $results));

	        $results = $dispatcher->trigger('onBeforeTrackLink', array(&$item, $this->params));
	        $this->events->BeforeTrackLink = trim(implode("\n", $results));

	        $results = $dispatcher->trigger('onAfterTrackLink', array(&$item, $this->params));
	        $this->events->AfterTrackLink = trim(implode("\n", $results));

		    if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false) {
		    	echo '<td>'.$item->title.'</td>';
		    }
		    else {
		        echo '<td>';
		            echo $this->events->BeforeTrackLink;
		            echo $this->events->PrepareTrackLink;
		            echo $this->events->AfterTrackLink;
		        echo '</td>';
		    }
		    echo '<td><a href="'.$genrelink.'" title="Continue to the genre view">'.$item->category_title.'</td>';
		    echo '<td>'.$item->year.'</td>';
		echo '</tr>';
    }
}