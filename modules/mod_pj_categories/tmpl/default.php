<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_categories
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

echo '<ul class="categories-module'.$moduleclass_sfx.'">';
	require JModuleHelper::getLayoutPath('mod_pj_categories', $params->get('layout', 'default') . '_items');
echo '</ul>';