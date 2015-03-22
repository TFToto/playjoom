<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Template.Isis
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

function renderMessage($msgList)
{
	$buffer  = null;
	$alert = array('error' => 'error', 'warning' => '', 'warning' => 'alert-info', 'message' => 'success');

	if (is_array($msgList))
	{
		foreach ($msgList as $type => $msgs)
		{
			$buffer .= '<div class="alert-box radius ' . $alert[$type]. '">';
			if (count($msgs))
			{
				foreach ($msgs as $msg)
				{
					$buffer .= "\n\t\t" .JText::_($type)." | ". $msg . "<a href=\"\" class=\"close\">×</a>";
				}
			}
			$buffer .= "\n</div>";
		}
	}

	return $buffer;
}
