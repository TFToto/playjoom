<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$canDo = PlayJoomHelper::getActions();

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'cover.cancel' || document.formvalidator.isValid(document.id('cover-form'))) {
			Joomla.submitform(task, document.getElementById('cover-form'));
		}
	}
</script>

<?php 
if (isset($this->item->id) && is_numeric($this->item->id)) {
	
	echo '<form action="'.JRoute::_('index.php?option=com_playjoom&layout=edit&id='.(int) $this->item->id).'" method="post" name="adminForm" id="cover-form" class="form-validate form-horizontal" enctype="multipart/form-data">';
	
	echo '<div class="row-fluid">';
	// Begin Content -->
	echo '<div class="span60 form-horizontal">';
	echo '<ul class="nav nav-tabs">';
	echo '<li class="active"><a href="#general" data-toggle="tab">'.JText::_( 'COM_PLAYJOOM_PLAYJOOM_DETAILS' ).'</a></li>';
	echo '<li><a href="#cover_img" data-toggle="tab">Cover Image</a></li>';
	echo '</ul>';
		
	echo '<div class="tab-content">';
		
	echo '<div class="tab-pane active" id="general">';
	echo '<div class="row-fluid">';
	echo '<div class="span90">';
	
	foreach($this->form->getFieldset('details') as $field) {
	
		echo '<div class="control-group">';
		echo '<div class="control-label">'.$field->label.'</div>';
		echo '<div class="controls">'.$field->input.'</div>';
		echo '</div>';
	
	}
	
	echo '</div>';
	echo '</div>';
	echo '</div>';
	
	echo '<div class="tab-pane" id="cover_img">';
		
	echo '<div class="row-fluid">';
	echo '<div class="span90">';
	
	echo PlayJoomHelper::getCoverThumb($this->item, '../tmp/admin_tmp_img_albumtumb', 550);
	
	echo '</div>';
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
	
} else {
	echo '<form action="'.JRoute::_('index.php?option=com_playjoom&layout=edit&id='.(int) $this->item->id).'" method="post" name="adminForm" id="cover-form" class="form-validate form-horizontal" enctype="multipart/form-data">';
	    echo '<fieldset>';
		    echo '<label id="jform_mylistvalue-lbl" for="jform_mylistvalue" class=" required"><span class="star">Select album</span></label>';
		    echo '<select name="artistalbum" class="required">';
		        echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ALBUM').'</option>';
		        echo JHtml::_('select.options', $this->OptionsNewCover, 'value', 'text', null);
		    echo '</select>';
		
		    echo '<div class="row-fluid">';
		        echo '<div class="span6">';
		            echo '<input type="file" id="upload-file" name="Filedata" />';
		            foreach($this->form->getFieldset('image') as $field) {			 
			            echo '<div class="control-group">';
			                echo '<div class="control-label">'.$field->label.'</div>';
			                echo '<div class="controls">'.$field->input.'</div>';
			            echo '</div>';
		            }
		        echo '</div>';
		    echo '</div>';
	
        echo '</fieldset>';
       // echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="task" value="playjoom.edit" />';
        echo JHtml::_('form.token');
    echo '</form>';
}