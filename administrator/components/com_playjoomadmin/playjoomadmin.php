<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

// No access check.

//Load helper methods
require_once JPATH_COMPONENT.'/helpers/playjoomadmin.php';

$controller	= JControllerLegacy::getInstance('PlayJoomAdmin');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
