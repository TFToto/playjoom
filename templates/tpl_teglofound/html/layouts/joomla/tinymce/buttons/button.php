<?php
/**
 * @package     Teglofound.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2010 - 2013 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$button = $displayData;

if ($button->get('name')) {
	
	if($button->get('name') == 'file-add') {
		$icon_name_class = 'fa fa-file-o fa-1x';
	} elseif ($button->get('name') == 'picture'){
		$icon_name_class = 'fa fa-picture-o fa-1x';
	} elseif ($button->get('name') == 'copy'){
		$icon_name_class = 'fa fa-files-o fa-1x';
	} elseif ($button->get('name') == 'arrow-down'){
		$icon_name_class = 'fa fa-chevron-down fa-1x';
	} else {
		$icon_name_class = 'icon-'.$button->get('name');
	}
	
	$class    = ($button->get('class')) ? $button->get('class') : null;
	$class	 .= ($button->get('modal')) ? ' modal-button' : null;
	$href     = ($button->get('link')) ? ' href="' . JUri::base() . $button->get('link') . '"' : null;
	$onclick  = ($button->get('onclick')) ? ' onclick="' . $button->get('onclick') . '"' : ' onclick="IeCursorFix(); return false;"';
	$title    = ($button->get('title')) ? $button->get('title') : $button->get('text');
	
	echo '<div class="button small">';
		echo '<a class="'.$class.'" title="'.$title.'"'.$href . $onclick.' rel="'.$button->get('options').'">';
			echo '<i class="'.$icon_name_class.'"></i> '.$button->get('text');
		echo '</a>';
	echo '</div>';
}