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
 * @copyright Copyright (C) 2010 by www.teglo.info
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
$saveOrder	= $listOrder == 'l.ordering';
?>
<tr>
        <th width="6">
                <?php echo JHtml::_('grid.sort', 'COM_PLAYJOOM_PLAYJOOM_HEADING_ID', 'l.id', $listDirn, $listOrder); ?>
        </th>                     
        <th>
                <?php echo JHtml::_('grid.sort', 'COM_PLAYJOOM_PLAYJOOM_HEADING_NAME', 'l.name', $listDirn, $listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('grid.sort', 'COM_PLAYJOOM_PLAYJOOM_HEADING_CATEGORY', 'l.catid', $listDirn, $listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('grid.sort', 'COM_PLAYJOOM_PLAYJOOM_HEADING_ACCESS', 'l.access', $listDirn, $listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('grid.sort', 'COM_PLAYJOOM_PLAYJOOM_HEADING_CREATE', 'l.create_date', $listDirn, $listOrder); ?>
        </th>
        <th>
                <?php echo JText::_('COM_PLAYJOOM_PLAYJOOM_HEADING_NOOFTRACKS'); ?>
        </th>
</tr>