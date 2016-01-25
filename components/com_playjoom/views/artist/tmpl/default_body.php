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
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

$dispatcher	= JDispatcher::getInstance();

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= & JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');
}

$counter = null;

foreach($this->items as $i => $item)
{
	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		//Prepare links
	    $albumsting = base64_encode($item->album);
	    $artiststing = base64_encode($item->artist);
	    $genresting = base64_encode($item->category);

        $albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&cat='.$genresting.'&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid');
        $genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid');

        //Plugins integration
        $this->events = new stdClass();
        $results = $dispatcher->trigger('onPrepareTrackLink', array(&$item, $this->params));
	    $this->events->PrepareTrackLink = trim(implode("\n", $results));

	    $results = $dispatcher->trigger('onBeforeTrackLink', array(&$item, $this->params));
	    $this->events->BeforeTrackLink = trim(implode("\n", $results));

	    $results = $dispatcher->trigger('onAfterTrackLink', array(&$item, $this->params));
        $this->events->AfterTrackLink = trim(implode("\n", $results));

        $counter = $counter + 1;

        echo '<tr >';
            echo '<td>'.$counter .'</td>';
            echo '<td><a href="'.$albumlink.'" title="Continue to the album view">'.$item->album.'</a></td>';

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
            echo '<td><a href="'.$genrelink.'" title="Continue to the album view">'.$item->category.'</a></td>';
            echo '<td>'.$item->year.'</td>';
        echo '</tr>';
	}
}