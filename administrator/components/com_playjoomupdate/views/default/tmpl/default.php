<?php
/**
 * Contains the default template for the PlayJomm update component.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage template.playjoomupdate
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoomUpdate Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die;

JHtml::_('formbehavior.chosen', 'select');

$ftpFieldsDisplay = $this->ftp['enabled'] ? '' : 'style = "display: none"';
?>

<form action="index.php" method="post" id="adminForm">

<?php if (is_null($this->updateInfo['object'])): ?>
<fieldset>
	<legend>
		<?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_NOUPDATES') ?>
	</legend>
	<p>
		<?php echo JText::sprintf('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_NOUPDATESNOTICE', PJVERSION); ?>
	</p>
</fieldset>

<?php else: ?>

<fieldset>
	<legend>
		<?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_UPDATEFOUND') ?>
	</legend>

	<table class="table table-striped">
		<tbody>
			<tr>
				<td>
					<?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_INSTALLED') ?>
				</td>
				<td>
					<?php echo $this->updateInfo['installed'] ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_LATEST') ?>
				</td>
				<td>
					<?php echo $this->updateInfo['latest'] ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_PACKAGE') ?>
				</td>
				<td>
					<a href="<?php echo $this->updateInfo['object']->downloadurl->_data ?>">
						<?php echo $this->updateInfo['object']->downloadurl->_data ?>
					</a>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<button class="btn" type="submit"><i class="icon-download "></i>
						<?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_INSTALLUPDATE') ?>
					</button>
				</td>
			</tr>
		</tfoot>
	</table>
</fieldset>

<?php endif; ?>

<?php echo JHtml::_('form.token'); ?>
<input type="hidden" name="task" value="update.download" />
<input type="hidden" name="option" value="com_playjoomupdate" />
</form>

<div class="download_message" style="display: none">
	<p></p>
	<p class="nowarning"> <?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_DEFAULT_DOWNLOAD_IN_PROGRESS'); ?></p>
	<div class="playjoomupdate_spinner" />
</div>
