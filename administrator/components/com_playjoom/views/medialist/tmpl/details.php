<?php
/**
 * Contains the default folder template for the media output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Admin
 * @subpackage views.medialist.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;
$user = JFactory::getUser();

?>
<form target="_parent" action="index.php?option=com_playjoom&amp;tmpl=index&amp;folder=<?php echo rtrim(strtr(base64_encode($this->state->folder), '+/', '-_'), '='); ?>" method="post" id="mediamanager-form" name="mediamanager-form">
	<div class="manager" style="margin-top:-80px">
	<?php echo JText::_('COM_PLAYJOOM_AUDIOTRACK_FIELD_PATH').': /'.$this->state->folder; ?>
	<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="1%"><?php echo JText::_('JGLOBAL_PREVIEW'); ?></th>
			<th><?php echo JText::_('COM_PLAYJOOM_FILE_NAME'); ?></th>
			<th width="8%"><?php echo JText::_('COM_PLAYJOOM_FILESIZE'); ?></th>
		<?php if ($user->authorise('core.delete', 'com_playjoom')):?>
			<th width="2%"><?php echo JText::_('JACTION_DELETE'); ?></th>
		<?php endif;?>
		</tr>
	</thead>
	<tbody>
	<?php echo $this->loadTemplate('up'); ?>
		<?php for ($i=0, $n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0, $n=count($this->documents); $i<$n; $i++) :
			$this->setDoc($i);
			echo $this->loadTemplate('doc');
		endfor; ?>

		<?php for ($i=0, $n=count($this->images); $i<$n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('img');
		endfor; ?>

	</tbody>
	</table>
	<input type="hidden" name="task" value="list" />
	<input type="hidden" name="username" value="" />
	<input type="hidden" name="password" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
