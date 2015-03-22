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
 * @date $Date: 2011-05-23 22:12:00 +0200 (Mo, 23 Mai 2011) $
 * @revision $Revision: 188 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/alphabetical/tmpl/default_foot.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
switch($this->params->get('show_alphabetical')) 
{
	case 'artist' : 
		//Placeholder for artist
    break;
	case 'album' :
	    //Placeholder for album
    break;
	default :
		echo '<tr>';
        echo '<td class="sectiontablefooter" colspan="6">'.$this->pagination->getListFooter().'</td>';
        echo '</tr>';
}