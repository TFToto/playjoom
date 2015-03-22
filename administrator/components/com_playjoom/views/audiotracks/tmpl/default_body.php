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
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

//Get User Objects
$user		= JFactory::getUser();
$canDo      = PlayJoomHelper::getActions();
$dispatcher	= JDispatcher::getInstance();

foreach($this->items as $i => $item) {
	
	//Plugins integration
	$this->events = new stdClass();
	$results = $dispatcher->trigger('onPrepareTrackLink', array(&$item, $this->params, null, 'admin'));
	$this->events->PrepareTrackLink = trim(implode("\n", $results));
	 
	$results = $dispatcher->trigger('onBeforeTrackLink', array(&$item, $this->params));
	$this->events->BeforeTrackLink = trim(implode("\n", $results));
	 
	$results = $dispatcher->trigger('onAfterTrackLink', array(&$item, $this->params));
	$this->events->AfterTrackLink = trim(implode("\n", $results));
	
	echo '<tr class="row'.$i % 2 .'">';
	    echo '<td>'.$item->id.'</td>';
        echo '<td>'.JHtml::_('grid.id', $i, $item->id).'</td>';
        echo '<td>'.$item->artist.'</td>';
        
        echo '<td>';
                          
           if ($canDo->get('core.edit')
           	|| JAccess::check($user->get('id'), 'core.admin') == 1) {
           	   /*
           	  echo '<a href="'.JRoute::_('index.php?option=com_playjoom&task=audiotrack.edit&id='.$item->id).'">';
			      echo $item->title;
			  echo '</a>';
			  */
           	
           	echo $this->events->BeforeTrackLink;
           	echo $this->events->PrepareTrackLink;
           	echo $this->events->AfterTrackLink;
           }
		   else {
			  echo $item->title;
		   }
        echo '</td>';
        
        echo '<td>'.$item->album.'</td>';
        echo '<td>'.$item->category.'</td>';
        echo '<td>'.$item->year.'</td>';
        echo '<td>'.$item->tracknumber.'</td>';
        echo '<td>'.$item->access_level.'</td>';
        if (JAccess::check($user->get('id'), 'core.admin') == 1) {
        	echo '<td>'.$item->user.'</td>';
        }
        echo '<td>'.$item->add_datetime.'</td>';
   echo '</tr>';
}