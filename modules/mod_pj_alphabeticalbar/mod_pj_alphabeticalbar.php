<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_alphabeticalbar
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die; 

// Include the syndicate functions only once
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
require_once JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php';
require_once JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'playjoom.php';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$alphabeticalindex = modAlphabeticalBarHelper::getIndexHTML();
$alphabeticalbar   = $alphabeticalindex[0];

require(JModuleHelper::getLayoutPath('mod_pj_alphabeticalbar'));
