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
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');

// add style sheet
$document	= & JFactory::getDocument();
$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/addtracks.css');
$document->addStyleDeclaration('div.addtracksheader { background-image: url('.JURI::root(true).'/administrator/components/com_playjoom/images/header/icon-48-addtracks.gif);}');

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=audiotracks').'" method="post" name="adminForm" id="adminForm">';
    
    echo '<fieldset>';

        echo '<div class="fltrt">';
         
            // Cancel Button
            $onclickValue = JRequest::getBool('refresh', 0) ? 'window.parent.location.href=window.parent.location.href;' : ''.'window.parent.SqueezeBox.close()';
            echo '<button type="button" onclick="'.$onclickValue.'">'.JText::_('JCANCEL').'</button>';
            
            switch(JRequest::getVar('select')) {
            	
            	case 'server' :
            		echo '<button type="button" onclick="Joomla.submitform(\'audiotracks.save\', this.form);">'.JText::_('COM_PLAYJOOM_SELECT_BUTTON_ADD_LOCALFOLDER').'</button>';    		
    		    break;
            }
    		
        echo '</div>';
        
        // Set Title
        echo '<div class="addtracksheader" >'.JText::_('COM_PLAYJOOM_TOOLBAR_ADD_NEW_TRACKS').'</div>';
    echo '</fieldset>';
    echo '<div class="spacescetion"></div>';
    echo '<fieldset>';
        
        /**
         * Load the template
         */
        switch(JRequest::getVar('select')) {
        	
        	case 'upload' :
        		echo $this->loadTemplate('upload');    		
    		break;
    	    
    	    case 'server' :
    	    	echo $this->loadTemplate('browser');
    		break;
    
    	    default :
    	    	echo $this->loadTemplate('select');
        
        }

        echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="task_save" value="add_tracks" />';
        echo '<input type="hidden" name="boxchecked" value="0" />';
        echo JHtml::_('form.token');
    
    echo '</fieldset>';
    
echo '</form>';