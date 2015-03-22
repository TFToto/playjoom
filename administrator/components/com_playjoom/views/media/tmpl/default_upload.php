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
 * @subpackage views.media.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die;

$user  = JFactory::getUser();
$input = JFactory::getApplication()->input;
?>
<div class="row-fluid">
	
	<!-- Begin Content -->
	<div class="span10">
		<?php if (($user->authorise('core.create', 'com_playjoom')) and $this->require_ftp): ?>
			<form action="index.php?option=com_playjoom&amp;task=ftpValidate" name="ftpForm" id="ftpForm" method="post">
				<fieldset title="<?php echo JText::_('COM_MEDIA_DESCFTPTITLE'); ?>">
					<legend><?php echo JText::_('COM_MEDIA_DESCFTPTITLE'); ?></legend>
					<?php echo JText::_('COM_MEDIA_DESCFTP'); ?>
					<label for="username"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
					<input type="text" id="username" name="username" class="inputbox" size="70" value="" />

					<label for="password"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
					<input type="password" id="password" name="password" class="inputbox" size="70" value="" />
				</fieldset>
			</form>
		<?php endif; ?>

		<form action="index.php?option=com_playjoom" name="adminForm" id="mediamanager-form" method="post" enctype="multipart/form-data" >
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="cb1" id="cb1" value="0" />
			<input class="update-folder" type="hidden" name="folder" id="folder" value="<?php echo $this->state->folder; ?>" />
		</form>

		<?php if ($user->authorise('core.create', 'com_playjoom')):?>
		<!-- File Upload Form -->
		<div id="collapseUpload" class="collapse">
			<form action="<?php echo JURI::base(); ?>index.php?option=com_playjoom&amp;task=file.upload&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JSession::getFormToken();?>=1&amp;format=<?php echo $this->config->get('enable_flash', 1) == '1' ? 'json' : 'html' ?>" id="uploadForm" class="form-inline" name="uploadForm" method="post" enctype="multipart/form-data">
				<div id="uploadform">
					<fieldset id="upload-noflash" class="actions">
							<label for="upload-file" class="control-label"><?php echo JText::_('COM_PLAYJOOM_UPLOAD_FILE'); ?></label>
								<input type="file" id="upload-file" name="Filedata" /><button class="btn btn-primary" id="upload-submit"><i class="icon-upload icon-white"></i> <?php echo JText::_('COM_PLAYJOOM_START_UPLOAD'); ?></button>
								<p class="help-block"><?php echo $this->config->get('upload_maxsize', 100) == '0' ? JText::_('COM_PLAYJOOM_UPLOAD_FILES_NOLIMIT') : JText::sprintf('COM_PLAYJOOM_UPLOAD_FILES', $this->config->get('upload_maxsize')); ?></p>

					</fieldset>
					<div id="upload-flash" class="hide">
						<div class="btn-toolbar">
							<div class="btn-group"><a class="btn" href="#" id="upload-browse"><i class="icon-folder"></i> <?php echo JText::_('COM_PLAYJOOM_BROWSE_FILES'); ?></a><a class="btn" href="#" id="upload-clear"><i class="icon-remove"></i> <?php echo JText::_('COM_PLAYJOOM_CLEAR_LIST'); ?></a></div>
							<div class="btn-group"><a class="btn btn-primary" href="#" id="upload-start"><i class="icon-upload icon-white"></i> <?php echo JText::_('COM_PLAYJOOM_START_UPLOAD'); ?></a></div>
						</div>
						<div class="clearfix"></div>
						<p class="overall-title"></p>
						<div class="overall-progress"></div>
						<div class="clearfix"></div>
						<p class="current-title"></p>
						<div class="current-progress"></div>
						<p class="current-text"></p>
					</div>
					<ul class="upload-queue list-striped list-condensed" id="upload-queue">
						<li style="display:none;"></li>
					</ul>
					<input class="update-folder" type="hidden" name="folder" id="folder" value="<?php echo $this->state->folder; ?>" />
					<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_playjoom'); ?>" />
				</div>
			</form>
		</div>
		<div id="collapseFolder" class="collapse">
			<form action="index.php?option=com_playjoom&amp;task=folder.create&amp;tmpl=<?php echo $input->getCmd('tmpl', 'index');?>" name="folderForm" id="folderForm" class="form-inline" method="post">
					<div class="path">
						<input class="inputbox" type="text" id="folderpath" readonly="readonly" />
						<input class="inputbox" type="text" id="foldername" name="foldername"  />
						<input class="update-folder" type="hidden" name="folderbase" id="folderbase" value="<?php echo $this->state->folder; ?>" />
						<button type="submit" class="btn"><i class="icon-folder-open"></i> <?php echo JText::_('COM_PLAYJOOM_CREATE_FOLDER'); ?></button>
					</div>
					<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
		<?php endif;?>

	</div>
	<!-- End Content -->
</div>
