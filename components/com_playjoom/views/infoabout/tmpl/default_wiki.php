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
 * @date $Date: 2011-05-19 21:40:50 +0200 (Do, 19 Mai 2011) $
 * @revision $Revision: 187 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://localhost/repos/playjoom/components/com_playjoom/views/infoabout/tmpl/default.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= & JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/infoabout_view.css');
}

//Set variablen
$type = JRequest::getVar('type');
$about = base64_decode(JRequest::getVar('about'));
$source = JRequest::getVar('source');

echo '<fieldset class="infoabout">';

	$WikiText = PlayJoomWikiHelper::getcontent(JRequest::getVar($type),'de');
	$content = $WikiText['parse']['text']['*'];
	
echo  preg_replace( '/href="\/wiki/', 'href="http://de.wikipedia.org/wiki', $content );

//Out the footrow
echo '<br />';
echo '<br />';
echo '<center><button type="button" onclick="window.parent.SqueezeBox.close();">'.JText::_('COM_PLAYJOOM_ADDTRACK_LABEL_CLOSE').'</button></center>';
echo '</fieldset>';