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
 * @PlayJoom Module
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// no direct access
defined('_JEXEC') or die;

//$app		= JFactory::getApplication();
//$menuparams = $app->getParams();

// Set application parameters in model
$app = JFactory::getApplication();
$appParams = $app->getParams();

// require helper file
JLoader::register('PlayJoomHelper', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'playjoom.php');

if (!$moduleclass_sfx) {
	$moduleclass = 'side-nav';
} else {
	$moduleclass = $moduleclass_sfx;
}

echo '<ul class="'.$moduleclass.'">';
    foreach ($list as $item)
    {
    	if ($params->get('show_hits') == 1)
        {
        	$show_hits = '['.$item->hits.']';
        }
        else 
        {
        	$show_hits = null;
        }
        if ($params->get('show_album') == 1)
        {
        	$show_album = '('.$item->album.')';
        }
        else 
        {
        	$show_album = null;
        }
        if ($params->get('show_length') == 1)
        {
        	$show_length = PlayJoomHelper::Playtime($item->length).' '.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT');
        }
        else 
        {
        	$show_length = null;
        }
    	echo '<li>';
		   echo '<a href="'.$item->link.'">'.$item->artist.' - '.$item->title.' - '.$show_length.' '.$show_album.' '.$show_hits.'</a>';
	    echo '</li>';
	    echo '<li class="divider"></li>';
    }
echo '</ul>';