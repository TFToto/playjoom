<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!$moduleclass_sfx) {
	$moduleclass = 'side-nav';
} else {
	$moduleclass = $moduleclass_sfx;
}
?>
<ul class="<?php echo $moduleclass; ?>">
<?php foreach ($list as $item) :  ?>
	<li>
		<a href="<?php echo $item->link; ?>">
			<?php echo $item->title; ?></a>
	</li>
	<li class="divider"></li>
<?php endforeach; ?>
</ul>
