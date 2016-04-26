<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.'/helpers/route.php';

// require helper file
JLoader::register('PlayJoomHelper', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'playjoom.php');
JLoader::import( 'helpers.cover', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');
JLoader::register('PJDatetime', JPATH_LIBRARIES.'/playjoom/datetime/datetime.php', true);

define( 'SERVER_REF', "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']);

//$controller = JController::getInstance('PlayJoom');
$controller = JControllerLegacy::getInstance('PlayJoom');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
