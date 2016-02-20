<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
//JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= JFactory::getDocument();
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