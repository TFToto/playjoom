<?php
/**
 * @package Joomla 1.6.x
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Load the behaviors.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');


echo '<script type="text/javascript">';
    echo 'Joomla.submitbutton = function(task) {';
	echo 'if (task == \'article.cancel\' || document.formvalidator.isValid(document.id(\'item-form\'))) {';
	    echo $this->form->getField('description')->save();
		echo 'Joomla.submitform(task, document.getElementById(\'item-form\'));';
	echo '} else {';
		echo 'alert(\''.$this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')).'\');';
	echo '}';
  echo '}';
echo '</script>';

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&layout=edit&id='.(int) $this->item->id).'" method="post" name="adminForm" id="item-form" class="form-validate">';

	echo '<div class="row-fluid">';
		// Begin Content -->
		echo '<div class="span60 form-horizontal">';
			echo '<ul class="nav nav-tabs">';
				echo '<li class="active"><a href="#general" data-toggle="tab">'.JText::_( 'COM_PLAYJOOM_PLAYJOOM_DETAILS' ).'</a></li>';
				echo '<li><a href="#file" data-toggle="tab">File Settings</a></li>';
				echo '<li><a href="#text-contents" data-toggle="tab">Text Contents</a></li>';
				echo '<li><a href="#metadata" data-toggle="tab">Metadatas</a></li>';
				echo '<li><a href="#file-access" data-toggle="tab">File Access</a></li>';
				echo '<li><a href="#file-rules" data-toggle="tab">File Rules</a></li>';
			echo '</ul>';

			echo '<div class="tab-content">';
				echo '<div class="tab-pane active" id="general">';
					echo '<div class="row-fluid">';
						echo '<div class="span90">';
							foreach($this->form->getFieldset('trackdetails') as $field) {
								echo '<div class="control-group">';
									echo '<div class="control-label">'.$field->label.'</div>';
									echo '<div class="controls">'.$field->input.'</div>';
								echo '</div>';
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';

				echo '<div class="tab-pane" id="file">';
					echo '<div class="row-fluid">';
						echo '<div class="span90">';
							foreach($this->form->getFieldset('file') as $field) {
								echo '<div class="control-group">';
									echo '<div class="control-label">'.$field->label.'</div>';
									echo '<div class="controls">'.$field->input.'</div>';
								echo '</div>';
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';

				echo '<div class="tab-pane" id="text-contents">';
					echo '<div class="row-fluid">';
						echo '<div class="span60">';
							foreach($this->form->getFieldset('text-items') as $field) {
								echo '<div class="control-group">';
									echo '<div class="control-label">'.$field->label.'</div>';
									echo '<div class="controls">'.$field->input.'</div>';
								echo '</div>';
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';

				echo '<div class="tab-pane" id="metadata">';
					echo '<div class="row-fluid">';
						echo '<div class="span60">';
							foreach($this->form->getFieldset('metadata') as $field) {
								echo '<div class="control-group">';
									echo '<div class="control-label">'.$field->label.'</div>';
									echo '<div class="controls">'.$field->input.'</div>';
								echo '</div>';
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';

				echo '<div class="tab-pane" id="file-access">';
					echo '<div class="row-fluid">';
						echo '<div class="span60">';
							foreach($this->form->getFieldset('file-access') as $field) {
								echo '<div class="control-group">';
									echo '<div class="control-label">'.$field->label.'</div>';
									echo '<div class="controls">'.$field->input.'</div>';
								echo '</div>';
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';

				echo '<div class="tab-pane" id="file-rules">';
					echo '<div class="row-fluid">';
						echo '<div class="span60">';
							echo $this->form->getInput('rules');
						echo '</div>';
					echo '</div>';
				echo '</div>';

			echo '</div>';
			//<!-- End Tabs -->
		echo '</div>';
		// End Content -->
	echo '</div>';

	echo '<input type="hidden" name="task" value="playjoom.edit" />';
	echo JHtml::_('form.token');

echo '</form>';