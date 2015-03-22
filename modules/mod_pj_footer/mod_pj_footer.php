<?php
/**
 * @package     Joomla.Site
 * @subpackage  MOD_PJ_FOOTER
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app		= JFactory::getApplication();
$date		= JFactory::getDate();
$cur_year	= $date->format('Y');
$csite_name	= $app->getCfg('sitename');
require_once JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'playjoom.php';

if (JString::strpos(JText :: _('MOD_PJ_FOOTER_LINE1'), '%date%')) {
	$line1 = str_replace('%date%', $cur_year, JText :: _('MOD_PJ_FOOTER_LINE1'));
} else {
	$line1 = JText :: _('MOD_PJ_FOOTER_LINE1');
}

if (JString::strpos($line1, '%sitename%')) {
	$lineone = str_replace('%sitename%', $csite_name, $line1);
} else {
	$lineone = $line1;
}
if ($params->get('show_pj_version') == 'true') {
	$PJ_version = PlayJoomHelper::GetInstallInfo("version","playjoom.xml");
	$linethree = 'SW Version '.$PJ_version;
} else {
	$linethree = null;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_pj_footer', $params->get('layout', 'default'));