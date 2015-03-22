<?php
/**
 * @package Joomla 3.0
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/homepage/tmpl/default.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= & JFactory::getDocument();
	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/album_view.css');
	$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/artist_view.css');
}

//Add Header row
if ($this->params->get('show_page_heading') ==1) {
	echo '<h1 class="'.$this->params->get('pageclass_sfx').'">';
	    echo $this->params->get('page_heading');
	echo '</h1>';
}

//Add Welcome text content
echo '<div class"welcometxt">';
    echo $this->params->get('welcometxt');
echo '</div>';

echo $this->loadTemplate('newartist');
echo $this->loadTemplate('newalbum');
echo $this->loadTemplate('newplaylist');