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

//Get plugin contents
$item_onBeforePJContent = $this->events->onBeforePJContent;
$item_onAfterPJContent  = $this->events->onAfterPJContent;

// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');

//load javascript and css script
$document	= JFactory::getDocument();

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document->addStyleSheet(JURI::root(true).'/components/com_playjoom/assets/css/playlist_view.css');
}

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&&view=adminplaylist&listid='.JRequest::getVar('listid').'&Itemid='.JRequest::getVar('Itemid')).'" method="post" name="adminForm">';
	echo $this->loadTemplate('toolbar');

	//check if one or more track in the playlist
	if (PlayJoomHelper::getPlaylistEntries(JRequest::getVar('listid')) >= 1) {

		echo $item_onBeforePJContent;

		echo '<table class="adminlist">';
			echo '<thead>'.$this->loadTemplate('head').'</thead>';
			echo '<tfoot>'.$this->loadTemplate('foot').'</tfoot>';
			echo '<tbody>'.$this->loadTemplate('body').'</tbody>';
		echo '</table>';

		echo JHtml::_('form.token');

		echo '</form>';

	echo $item_onAfterPJContent;

}
else {
	echo JText::_('COM_PLAYJOOM_PLAYLIST_EMPTY');
}