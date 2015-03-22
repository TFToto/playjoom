<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$canEdit = $displayData['params']->get('access-edit');
?>

<div class="icons">
	<?php if (empty($displayData['print'])) : ?>

		<?php if ($canEdit || $displayData['params']->get('show_print_icon') || $displayData['params']->get('show_email_icon')) : ?>
			<div class="btn-group pull-right">
				<a href="#" data-dropdown="<?php echo $displayData['item_id']; ?>" class="tiny button dropdown radius"><i class="fa fa-cog fa-1x"></i></a><br>
				<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
				<ul id="<?php echo $displayData['item_id']; ?>" data-dropdown-content class="f-dropdown">
					<?php if ($displayData['params']->get('show_print_icon')) : ?>
						<li class="print-icon"><i class="fa fa-print icon-set fa-1x"></i> <?php echo JHtml::_('icon.print_popup', $displayData['item'], $displayData['params']); ?> </li>
					<?php endif; ?>
					<?php if ($displayData['params']->get('show_email_icon')) : ?>
						<li class="email-icon"><i class="fa fa-envelope-o icon-set fa-1x"></i> <?php echo JHtml::_('icon.email', $displayData['item'], $displayData['params']); ?> </li>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
						<li class="edit-icon"><i class="fa fa-pencil icon-set fa-1x"></i> <?php echo JHtml::_('icon.edit', $displayData['item'], $displayData['params']); ?> </li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="clear"></div>
		<?php endif; ?>

	<?php else : ?>

		<div class="pull-right">
			<?php echo JHtml::_('icon.print_screen', $displayData['item'], $displayData['params']); ?>
		</div>

	<?php endif; ?>
</div>
