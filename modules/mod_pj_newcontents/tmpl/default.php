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
$app		= JFactory::getApplication();
$menuparams	= $app->getParams();     

if (!$moduleclass_sfx) {
	$moduleclass = 'side-nav';
} else {
	$moduleclass = $moduleclass_sfx;
}

echo '<ul class="'.$moduleclass.'">';
    foreach ($list as $item)
    {
        //Check for albumname as sampler
		if (PlayJoomHelper::checkForSampler($item->album, $item->artist))
		{
		    $artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
		}
		else 
		{
		    $artistname = $item->artist;
		}
		
    	echo '<li>';
		   switch ($menuparams->get('show_section'))
		   {
		   	case "album":
		   		$albumsting = base64_encode($item->album);
		   		$artiststing = base64_encode($item->artist);  
		        $albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
		   		echo '<a href="'.$albumlink.'">'.$artistname.'&nbsp;-&nbsp;'.$item->album.'</a>';
		   	break;
		   	case "artist":
		   		$artiststing = base64_encode($item->artist); 
		        $artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
		   		echo '<a href="'.$artistlink.'">'.$item->artist.'</a>';
		   	break;
		   	default:
		   		echo '<a href="'.$item->link.'">'.$artistname.' - '.$item->title.'</a>';
		   	break;
		   }
	    echo '</li>';	    
	    echo '<li class="divider"></li>';
    }
echo '</ul>';