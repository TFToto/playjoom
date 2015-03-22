<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the submenu style, you would use the following include:
 * <jdoc:include type="module" name="test" style="submenu" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */

/*
 * Module chrome for rendering the module in a submenu
 */
function modChrome_no($module, &$params, &$attribs)
{
	if ($module->content) {
		echo $module->content;
	}
}

function modChrome_dropdown($module, &$params, &$attribs) {

	echo $module->content;
}

function modChrome_footer($module, &$params, &$attribs) {

	if ($module->content) {
		if ($module->showtitle) {
			echo '<h5 class="subheader">'.$module->title.'</h5>';
		}
		echo $module->content;
	}
}

function modChrome_leftsidemodul($module, &$params, &$attribs) {

	echo '<div class="panel leftside">';
	echo '<div class="sidemodul">';
	    if ($module->content) {
	    	echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		    if ($module->showtitle) {
		    	echo '<h3 class="leftsidemodultitle">'.$module->title.'</h3>';
		    }
		    echo $module->content;
		    echo "</div>";
	    }
	echo '</div>';
	echo '</div>';
}

function modChrome_rightsidemodul($module, &$params, &$attribs) {

	$headerTag      = htmlspecialchars($params->get('header_tag', 'h3'));
	$headerClass	= $params->get('header_class');
	if($headerClass) {
		$headerClass	= ' class="'.htmlspecialchars($headerClass).'"';
	} else {
		$headerClass	= ' class="subheader"';
	}

	echo '<div class="panel rightside">';
	echo '<div class="sidemodul">';
	if ($module->content) {
		echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle) {
			if ($params->get('icon_class')) {
				$icon_class = '<i class="'.$params->get('icon_class').'"></i>';
			} else {
				$icon_class = null;
			}
			echo '<'.$headerTag.$headerClass.'>'.$icon_class.$module->title.'</'.$headerTag.'>';
		}
		echo $module->content;
		echo "</div>";
	}
	echo '</div>';
	echo '</div>';
}

function modChrome_downmodul($module, &$params, &$attribs) {

	if ($module->content) {
		echo '<div class="down-panel">';
			echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
				if ($module->showtitle) {
					echo '<h4 class="subheader">'.$module->title.'</h4>';
				}
				echo $module->content;
			echo "</div>";
		echo '</div>';
	}
}

function modChrome_sidenav ($module, &$params, &$attribs) {

	echo '<div class="leftside">';
	echo '<div class="sidemodul">';
	if ($module->content) {
		echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";

		if ($module->showtitle) {
			echo '<h3 class="leftsidemodultitle">'.$module->title.'</h3>';
		}
		echo '<ul class="four side-nav">';
		     echo $module->content;
		echo '</ul>';

		echo "</div>";
	}
	echo '</div>';
	echo '</div>';
}