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
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$user		= JFactory::getUser();
$userId		= $user->get('id');

$document	= JFactory::getDocument();
// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tables.css');    
}
$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/slider.css');

// add script parts
$document->addScript(JURI::base(true).'/components/com_playjoom/views/statistics/js/moobargraph.js');

echo '<table class="adminlist">';
    echo '<thead>'.$this->loadTemplate('head').'</thead>';
    echo '<tfoot>'.$this->loadTemplate('foot').'</tfoot>';
    echo '<tbody>'.$this->loadTemplate('body').'</tbody>';
echo '</table>';