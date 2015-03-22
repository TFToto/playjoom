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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// require helper file
//JLoader::register('PlayJoomHelper', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'playjoom.php');
JLoader::register('PlayJoomLogging', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'logging.php'); 

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_playjoom')) {   
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once JPATH_COMPONENT.'/helpers/playjoom.php';
require_once JPATH_COMPONENT.'/helpers/media.php';

//Global definitions of the Components paths
define( 'PLAYJOOM_PATH', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom' );
define( 'PLAYJOOM_ADMINPATH', JPATH_SITE .DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom' );
 
define( 'PLAYJOOM_BASE_PATH', PlayJoomHelper::getPJPath());
define( 'PLAYJOOM_BASE_PATHURL', PlayJoomHelper::getPJPath());

// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by PlayJoom
$controller = JControllerLegacy::getInstance('PlayJoom');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();