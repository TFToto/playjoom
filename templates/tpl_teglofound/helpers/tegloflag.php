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
 * @date $Date: 2013-01-25 20:39:12 +0100 (Fr, 25 Jan 2013) $
 * @revision $Revision: 678 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/administrator/components/com_playjoom/helpers/playjoom.php $
 */

// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * PlayJoom component helper.
 */
abstract class TegloFlagHelper {
	
	/**
	 * Method to sort a column in a grid
	 *
	 * @param   string  $title          The link title
	 * @param   string  $order          The order field for the column
	 * @param   string  $direction      The current direction
	 * @param   string  $selected       The selected ordering
	 * @param   string  $task           An optional task override
	 * @param   string  $new_direction  An optional direction for the new column
	 * @param   string  $tip            An optional text shown as tooltip title instead of $title
	 *
	 * @return  string
	 *
	 * @since   11.1
	 */
	function sort($title, $order, $direction = 'asc', $selected = 0, $task = null, $new_direction = 'asc', $tip = '')	{
		
		JHtml::_('behavior.tooltip');
	
		$direction = strtolower($direction);
		$icon = array('arrow-down', 'arrow-up');
		$index = (int) ($direction == 'desc');
	
		if ($order != $selected)
		{
			$direction = $new_direction;
		}
		else
		{
			$direction = ($direction == 'desc') ? 'asc' : 'desc';
		}
	
		$html = '<a href="#" onclick="Joomla.tableOrdering(\'' . $order . '\',\'' . $direction . '\',\'' . $task . '\');"'
				. ' class="hasTipppppppppp" title="' . JText::_($tip ? $tip : $title) . '::' . JText::_('JGLOBAL_CLICK_TO_SORT_THIS_COLUMN') . '">';
		$html .= JText::_($title);
	
		if ($order == $selected)
		{
			$html .= ' <i class="icon-' . $icon[$index] . '"></i>';
		}
	
		$html .= '</a>';
	
		return $html;
	}
	
	/**
	 * Method for to create a hex color code with other brightness
	 * 
	 * @param string  $hex
	 * @param integre $percent
	 * @return string new hex value
	 */
	static function colorBrightness($hex, $percent) {
		// Work out if hash given
		$hash = '';
		if (stristr($hex,'#')) {
			$hex = str_replace('#','',$hex);
			$hash = '#';
		}
		// Calculate from HEX TO RGB color value
		$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
		//// CALCULATE
		for ($i=0; $i<3; $i++) {
		    // See if brighter or darker
			if ($percent > 0) {
				// Lighter
				$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
			} else {
				// Darker
				$positivePercent = $percent - ($percent*2);
				$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
			}
			// In case rounding up causes us to go to 256
			if ($rgb[$i] > 255) {
				$rgb[$i] = 255;
			}
		}
		// Calculate from RBG to Hex value
		$hex = '';
		for($i=0; $i < 3; $i++) {
			// Convert the decimal digit to hex
			$hexDigit = dechex($rgb[$i]);
			// Add a leading zero if necessary
			if(strlen($hexDigit) == 1) {
				$hexDigit = "0" . $hexDigit;
			}
			// Append to the hex string
			$hex .= $hexDigit;
		}
		return $hash.$hex;
	}
}