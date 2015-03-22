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
$params = new JRegistry;


$dispatcher	= JDispatcher::getInstance();
$dispatcher->trigger('onContentBeforeDisplay', array('com_playjoom.file', &$this->_tmp_doc, &$params));

echo '<tr>';
	echo '<td>';
		echo '<a  title="'.$this->_tmp_doc->name.'">';
		    if ($this->_tmp_doc->icon_16 && file_exists(JPATH_ROOT.DIRECTORY_SEPARATOR.$this->_tmp_doc->icon_16)) {
		    	echo '<img src="'.JURI::root(true).'/'.$this->_tmp_doc->icon_16.'" width="16" height="16" />';
		    } else { 
		    	echo '<img src="'.JURI::root(true).'/administrator/components/com_playjoom/images/mime-icon-16/blank.png" alt="other file" width="16" height="16" />';
		    }
		echo '</a>';
		
	echo '</td>';
	echo '<td class="description"  title="'.$this->_tmp_doc->name.'">';
	    echo $this->_tmp_doc->title;
	echo '</td>';
	echo '<td class="filesize">';
	    echo PlayJoomMediaHelper::parseSize($this->_tmp_doc->size);
	echo '</td>';
		
	if ($user->authorise('core.delete', 'com_playjoom')) {
		echo '<td>';
			echo '<a class="delete-item" target="_top" href="index.php?option=com_playjoom&amp;task=file.delete&amp;tmpl=component&amp;'.JSession::getFormToken().'=1&amp;folder='.rtrim(strtr(base64_encode($this->state->folder), '+/', '-_'), '=').'&amp;rm[]='.$this->_tmp_doc->name.'" rel="'.$this->_tmp_doc->name.'">'.JHtml::_('image', 'media/remove.png', JText::_('JACTION_DELETE'), array('width' => 16, 'height' => 16, 'border' => 0), true).'</a>';
		echo '</td>';
	}
echo '</tr>';

$dispatcher->trigger('onContentAfterDisplay', array('com_playjoom.file', &$this->_tmp_doc, &$params));