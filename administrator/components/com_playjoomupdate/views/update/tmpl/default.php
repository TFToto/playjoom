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

?>

<p class="nowarning"><?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_UPDATE_INPROGRESS') ?></p>
<div class="playjoomupdate_spinner" ></div>

<div id="update-progress">
	<div id="extprogress">
		<div class="extprogrow">
			<?php
			echo JHtml::_(
				'image', 'media/bar.gif', JText::_('COM_PLAYJOOMUPDATE_VIEW_PROGRESS'),
				array('class' => 'progress', 'id' => 'progress'), true
			); ?>
		</div>
		<div class="extprogrow">
			<span class="extlabel"><?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_UPDATE_PERCENT'); ?></span>
			<span class="extvalue" id="extpercent"></span>
		</div>
		<div class="extprogrow">
			<span class="extlabel"><?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_UPDATE_BYTESREAD'); ?></span>
			<span class="extvalue" id="extbytesin"></span>
		</div>
		<div class="extprogrow">
			<span class="extlabel"><?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_UPDATE_BYTESEXTRACTED'); ?></span>
			<span class="extvalue" id="extbytesout"></span>
		</div>
		<div class="extprogrow">
			<span class="extlabel"><?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_UPDATE_FILESEXTRACTED'); ?></span>
			<span class="extvalue" id="extfiles"></span>
		</div>
	</div>
</div>
