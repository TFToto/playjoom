<?php
/**
 * Contains the default layouts methods for to get the filters in PlayJoom backend.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Layout
 * @subpackage layout.playjoom.searchtools
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2014 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2014-03-13 14:27:32 +0100 (Do, 13 Mrz 2014) $
 * @revision $Revision: 913 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/layouts/playjoom/searchtools/grid/sort.php $
 */

defined('JPATH_BASE') or die;

$data = $displayData;

$metatitle = JHtml::tooltipText(JText::_($data->tip ? $data->tip : $data->title), JText::_('JGLOBAL_CLICK_TO_SORT_THIS_COLUMN'), 0);
JHtml::_('bootstrap.tooltip');
?>
<a href="#" onclick="return false;" class="js-stools-column-order hasTooltip" data-order="<?php echo $data->order; ?>" data-direction="<?php echo strtoupper($data->direction); ?>" data-name="<?php echo JText::_($data->title); ?>" title="<?php echo $metatitle; ?>">
	<?php if (!empty($data->icon)) : ?>
		<i class="<?php echo $data->icon; ?>"></i>
	<?php endif; ?>
	<?php if (!empty($data->title)) : ?>
		<?php echo JText::_($data->title); ?>
	<?php endif; ?>
	<?php if ($data->order == $data->selected) : ?>
		<i class="<?php echo $data->orderIcon; ?>"></i>
	<?php endif; ?>
</a>
