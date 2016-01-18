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
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_playjoom&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="playjoom-form" class="form-validate">
 
        <div class="width-60 fltlft">
                <fieldset class="adminform">
                        <legend><?php echo JText::_( 'COM_PLAYJOOM_PLAYJOOM_DETAILS' ); ?></legend>
                        <ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
                                <li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
                        </ul>
        </div>
 
        <div class="width-40 fltrt">
                <?php echo JHtml::_('sliders.start', 'playjoom-slider'); ?>
<?php foreach ($params as $name => $fieldset): ?>
                <?php echo JHtml::_('sliders.panel', JText::_($fieldset->label), $name.'-params');?>
        <?php if (isset($fieldset->description) && trim($fieldset->description)): ?>
                <p class="tip"><?php echo $this->escape(JText::_($fieldset->description));?></p>
        <?php endif;?>
                <fieldset class="panelform" >
                        <ul class="adminformlist">
        <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                                <li><?php echo $field->label; ?><?php echo $field->input; ?></li>
        <?php endforeach; ?>
                        </ul>
                </fieldset>
<?php endforeach; ?>
 
                <?php echo JHtml::_('sliders.end'); ?>
        </div>
 
        <div>
                <input type="hidden" name="task" value="playjoom.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>