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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';

echo '<tr>';                   
    echo '<th>No</th>';
    echo '<th>'.JHtml::_('grid.sort', 'COM_PLAYJOOM_HEADING_ARTIST', 'a.artist', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('grid.sort', 'COM_PLAYJOOM_HEADING_ALBUM', 'a.album', $listDirn, $listOrder).'</th>';
    
    if ($this->params->get('numbers_of_titles_genre', 8) >= 1)
    {
    	echo '<th>Titles</th>';
    }
    
    echo '<th>'.JHtml::_('grid.sort', 'COM_PLAYJOOM_HEADING_YEAR', 'a.year', $listDirn, $listOrder).'</th>';
echo '</tr>';