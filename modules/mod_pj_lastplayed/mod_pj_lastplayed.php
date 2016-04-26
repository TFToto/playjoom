<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_lastplayed
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Load the PlayJoom libaray language file.
$lang = JFactory::getLanguage();
$lang->load('lib_playjoom', JPATH_SITE);

// Include the syndicate functions only once
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php';
require_once JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'playjoom.php';

// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.application.component.helper');
JLoader::register('PJDatetime', JPATH_LIBRARIES.'/playjoom/datetime/datetime.php', true);

$list = modLastPlayedHelper::getList($params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_pj_lastplayed', $params->get('layout', 'default'));