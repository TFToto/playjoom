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
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';

echo '<tr>';
    echo '<th width="5">'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_PLAYJOOM_HEADING_ID', 'a.id', $listDirn, $listOrder).'</th>';
    echo '<th width="20"><input type="checkbox" name="checkall-toggle" value="" title="'.JText::_('JGLOBAL_CHECK_ALL').'" onclick="Joomla.checkAll(this)" /></th>';
    echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_HEADING_ARTIST', 'a.artist', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_HEADING_ALBUM', 'a.album', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_HEADING_GENRE', 'category', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_HEADING_YEAR', 'a.year', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_AUDIOTRACK_FIELD_TRACKNUMBER', 'a.tracknumber', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_HEADING_ACCESS', 'access', $listDirn, $listOrder).'</th>';
    if (JAccess::check($user->get('id'), 'core.admin') == 1) {
    	echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_HEADING_ADD_BY', 'user', $listDirn, $listOrder).'</th>';
    }
    echo '<th>'.JHtml::_('searchtools.sort', 'COM_PLAYJOOM_HEADING_DATE', 'a.add_datetime', $listDirn, $listOrder).'</th>';
echo '</tr>';