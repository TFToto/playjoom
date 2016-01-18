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
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/album/tmpl/default_body.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$app	    = JFactory::getApplication();

JPluginHelper::importPlugin('playjoom');
$dispatcher	= JDispatcher::getInstance();

echo '<div class="row">';
	echo '<ul class="side-nav-4small">';

		echo '<li class="divider"></li>';

		foreach($this->items as $i => $item) {

			if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

				//create artist string
        		$TitleLenght = strlen($item->title);

        		if ($TitleLenght > 40) {
           			$TitleName = substr($item->title,0, 37).'...';
        		} else {
           			$TitleName = $item->title;
        		}

        		//Plugins integration
	    		$this->events = new stdClass();

	    		$results = $dispatcher->trigger('onInTrackbox', array($item, $this->params));
	    		$this->events->InTrackbox = trim(implode("\n", $results));

        		$results = $dispatcher->trigger('onPrepareTrackLink', array(&$item, $this->params, $item->title));
	    		$this->events->PrepareTrackLink = trim(implode("\n", $results));

	    		$results = $dispatcher->trigger('onBeforeTrackLink', array(&$item, $this->params));
	    		$this->events->BeforeTrackLink = trim(implode("\n", $results));

	    		$results = $dispatcher->trigger('onAfterTrackLink', array(&$item, $this->params));
	    		$this->events->AfterTrackLink = trim(implode("\n", $results));

				//Check for Trackcontrol
				if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false) {
	    			$NoLink = sprintf ("%02d", $item->tracknumber);
	    			$TitleLink = $item->title;
	    		} else {
	    			$NoLink = '<a href="index.php?option=com_playjoom&amp;view=broadcast&amp;id='.$item->id.'" target="_blank" class="direct_link">'.sprintf ("%02d", $item->tracknumber).'</a>';
	    			$TitleLink = $this->events->PrepareTrackLink;
	    		}

	    		echo '<li><span class="trackno">'.$NoLink.'</span> - '.$this->events->BeforeTrackLink.'<span class="tracktitle">'.$TitleLink.'</span><span class="trackminutes">['.PlayJoomHelper::Playtime($item->length).' '.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').']</span>'.$this->events->AfterTrackLink;
	    		echo '<li class="divider"></li>';
			}
		}

	echo '</ul>';
echo '</div>';