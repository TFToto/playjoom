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
 * @copyright Copyright (C) 2010-2015 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
//JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');
JHtml::_('formbehavior.chosen', 'select');

//Get plugin contents
$item_onBeforePJContent = $this->events->onBeforePJContent;
$item_onAfterPJContent = $this->events->onAfterPJContent;

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';
$dir_case   = JRequest::getVar( 'dir' );
$dirName   = $this->state->get('list.dirName');

echo $this->params->get('show_section');
echo $this->loadTemplate('artist');
echo $this->loadTemplate('albumsview');
echo $this->loadTemplate('tracks');

echo $item_onAfterPJContent;