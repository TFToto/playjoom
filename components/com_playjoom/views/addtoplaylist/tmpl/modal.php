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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
//window.parent.SqueezeBox.close()
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

if (PlayJoomModelAddtoplaylist::getTrackInfo((int)JRequest::getInt('id')) != 0 
 || PlayJoomModelAddtoplaylist::getTrackInfo((int)JRequest::getInt('id')) != '')
{
	echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=addtoplaylist&layout=modal&tmpl=component').'" method="post" name="adminForm" id="adminForm">';
        echo '<fieldset class="addtrack">';
		    echo '<label class="addtrack" for="playjoom">'.JText::_('COM_PLAYJOOM_ADDTRACK_LABEL').' <b>'.PlayJoomModelAddtoplaylist::getTrackInfo((int)JRequest::getInt('id')).'</b> '.JText::_('COM_PLAYJOOM_ADDTRACK_LABEL2').'</label>';
		        echo '<div class="filter-select fltrt">';			
			        echo '<select name="list_id" class="inputbox" onchange="this.form.submit();">';
				        echo '<option value="">'.JText::_('COM_PLAYJOOM_CHOOSE_PLAYLIST').'</option>';
				        echo JHtml::_('select.options', PlayJoomModelAddtoplaylist::getPlaylists(), 'value', 'text', $this->escape(null));				
			        echo '</select>';
		        echo '</div>';
	    echo '</fieldset>';
	    echo '<div>';
	        echo '<input type="hidden" name="task" value="" />';
            echo '<input type="hidden" name="track_id" value="'.(int)JRequest::getInt('id').'" />';
	        echo JHtml::_('form.token');
	    echo '</div>';
    echo '</form>';
}
else 
{
	echo '<fieldset class="addtrack">';
		echo '<div class="filter-select fltrt">';
	        echo JText::_('COM_PLAYJOOM_ADDTRACK_LABEL_SUCCESS');
	        echo '<br />';
	        echo '<br />';
	        echo '<button type="button" onclick="window.parent.SqueezeBox.close();">'.JText::_('COM_PLAYJOOM_ADDTRACK_LABEL_CLOSE').'</button>';
	    echo '</div>';
	echo '</fieldset>';
}