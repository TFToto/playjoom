<?php
/**
 * Contains a helper method for to create a select menu for two installing methods .
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage helpers.playjoomupdate
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoomUpdate Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die;

/**
 * Joomla! update selection list helper.
 *
 * @package     PlayJoom.Administrator
 * @subpackage  com_playjoomupdate
 * @since       2.5.4
 */
class PlayjoomupdateHelperSelect
{
	/**
	 * Returns an HTML select element with the different extraction modes
	 *
	 * @param   string  $default  The default value of the select element
	 *
	 * @return  string
	 *
	 * @since   0.9
	 */
	public static function getMethods($default = 'direct') {
		$options = array();
		$options[] = JHtml::_('select.option', 'direct', JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_METHOD_DIRECT'));
		$options[] = JHtml::_('select.option', 'ftp', JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_METHOD_FTP'));

		return JHtml::_('select.genericlist', $options, 'method', '', 'value', 'text', $default, 'extraction_method');
	}
}
