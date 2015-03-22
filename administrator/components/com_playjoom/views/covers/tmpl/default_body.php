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

//Get User Objects
$user		= JFactory::getUser();
$canDo = PlayJoomHelper::getActions();

foreach($this->items as $i => $item) {
	
	$coverthumb = PlayJoomHelper::getCoverThumb($item, '../tmp/admin_tmp_img_albumtumb'.$i, 100);

    echo '<tr class="row'.$i % 2 .'">';
        echo '<td>'.$item->id.'</td>';
        echo '<td>'.JHtml::_('grid.id', $i, $item->id).'</td>';
        
        echo '<td>';        
        
        if ($canDo->get('core.edit')
        		|| JAccess::check($user->get('id'), 'core.admin') == 1) {
        	echo '<a href="'.JRoute::_('index.php?option=com_playjoom&task=cover.edit&id='.$item->id).'">';
	            echo $item->album;
	        echo '</a>';
        }
		else {
			echo $item->album;
		}		
		
		echo '</td>';
		echo '<td>'.$item->artist.'</td>';
        echo '<td>'.$coverthumb.'</td>';
    echo '</tr>';
}