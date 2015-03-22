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
 
echo '<div class="width-50 fltlft">';
    echo '<fieldset class="adminform">';
        echo '<legend>'.JText::_( 'COM_PLAYJOOM_SELECT_UPLOAD' ).'</legend>';
        echo '<div class="desc_txt">'.JText::_( 'COM_PLAYJOOM_SELECT_UPLOAD_DESC' ).'</div>';
            echo '<a href="'.JRoute::_('index.php?option=com_playjoom&amp;view=media&amp;tmpl=component').'" class="selectionbutton" title="'.JText::_('COM_PLAYJOOM_SELECT_UPLOAD_DESC').'">'.JText::_('COM_PLAYJOOM_SELECT_BUTTON_UPLOAD').'</a>';
        echo '<div class="spacescetion"></div>';
    echo '</fieldset>';
echo '</div>';

echo '<div class="width-50 fltlft">';
    echo '<fieldset class="adminform">';
        echo '<legend>'.JText::_( 'COM_PLAYJOOM_SELECT_LOCALFOLDER' ).'</legend>';
        echo '<div class="desc_txt">'.JText::_( 'COM_PLAYJOOM_SELECT_LOCALFOLDER_DESC' ).'</div>';
            echo '<a href="'.JRoute::_('index.php?option=com_playjoom&amp;view=addtracks&amp;select=server&amp;tmpl=component').'" class="selectionbutton" title="'.JText::_('COM_PLAYJOOM_SELECT_LOCALFOLDER_DESC').'">'.JText::_('COM_PLAYJOOM_SELECT_BUTTON_LOCALFOLDER').'</a>';
        echo '<div class="spacescetion"></div>';
    echo '</fieldset>';
echo '</div>';