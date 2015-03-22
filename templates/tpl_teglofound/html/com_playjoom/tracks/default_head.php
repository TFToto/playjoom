<?php
/**
 * Contains the default head template for the artist output.
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
 * @date $Date: 2013-09-08 14:20:12 +0200 (So, 08 Sep 2013) $
 * @revision $Revision: 829 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/tracks/tmpl/default_head.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';

echo '<tr>';
	echo '<th>No</th>';
    echo '<th>'.JHtml::_('grid.sort', 'COM_PLAYJOOM_HEADING_ARTIST', 'a.artist', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('grid.sort', 'COM_PLAYJOOM_HEADING_ALBUM', 'a.album', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('grid.sort', 'COM_PLAYJOOM_HEADING_GENRE', 'category_title', $listDirn, $listOrder).'</th>';
    echo '<th>'.JHtml::_('grid.sort', 'COM_PLAYJOOM_HEADING_YEAR', 'a.year', $listDirn, $listOrder).'</th>';
echo '</tr>';