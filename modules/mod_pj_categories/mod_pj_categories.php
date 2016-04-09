<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_categories
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the helper functions only once
require_once __DIR__ . '/helper.php';

//Replace Joomla! core library with PlayJoom own
JLoader::register('JCategories', JPATH_LIBRARIES.'/playjoom/categories/categories.php', true);

$cacheid = md5($module->id);

$cacheparams               = new stdClass;
$cacheparams->cachemode    = 'id';
$cacheparams->class        = 'ModPJCategoriesHelper';
$cacheparams->method       = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams   = $cacheid;

$list = JModuleHelper::moduleCache($module, $params, $cacheparams);

$query_itmes = '&amp;moduletype='.base64_encode($module->module).'&amp;moduletitle='.base64_encode($module->title);

if (!empty($list)) {
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	$startLevel      = reset($list)->getParent()->level;
	require JModuleHelper::getLayoutPath('mod_pj_categories', $params->get('layout', 'default'));
}