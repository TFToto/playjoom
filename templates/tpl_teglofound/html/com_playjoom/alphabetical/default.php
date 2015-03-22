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
 * @date $Date: 2011-05-27 22:14:03 +0200 (Fr, 27 Mai 2011) $
 * @revision $Revision: 201 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/alphabetical/tmpl/default.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';
$dir_case   = JRequest::getVar( 'dir' );
$dirName   = $this->state->get('list.dirName');

// add style sheet
if ($this->params->get('css_type') == 'pj_css') {
	$document	= JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/alphabetical_view.css');
}

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=alphabetical').'&LetterForAlphabetical='.JRequest::getVar('LetterForAlphabetical').'" method="post" name="adminForm" id="adminForm">';
echo '<div class="hide-for-small">';
    echo $this->loadTemplate('filter');
echo '</div>';
echo $this->loadTemplate('body');
echo $this->loadTemplate('foot');
echo '<div>';
echo '<input type="hidden" name="task" value="" />';
echo '<input type="hidden" name="boxchecked" value="0" />';
echo '<input type="hidden" name="filter_order" value="'.$listOrder.'" />';
echo '<input type="hidden" name="filter_order_Dir" value="'.$listDirn.'" />';
echo JHtml::_('form.token');
echo '</div>';
echo '</form>';