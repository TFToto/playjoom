<?php
/**
 * @package     Teglofound.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2010 - 2013 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$name = $displayData;

echo '<div class="toggle-editor btn-toolbar pull-right clearfix">';
	echo '<div class="button small">';
		echo '<a class="btn" href="#"onclick="tinyMCE.execCommand(\'mceToggleEditor\', false, \''.$name.'\');return false;" title="'.JText::_('PLG_TINY_BUTTON_TOGGLE_EDITOR').'">';
			echo '<i class="fa fa-code fa-1x"></i> '.JText::_('PLG_TINY_BUTTON_TOGGLE_EDITOR');
		echo '</a>';
	echo '</div>';
echo '</div>';