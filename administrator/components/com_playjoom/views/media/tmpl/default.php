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

// No direct access.
defined('_JEXEC') or die;
$user = JFactory::getUser();

// add style sheet
$document	= JFactory::getDocument();
$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/addtracks.css');
$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/jqueryFileTree.css');
$document->addStyleDeclaration('div.addtracksheader { background-image: url('.JURI::root(true).'/administrator/components/com_playjoom/images/header/icon-48-addtracks.gif);}');

//load templates for media viewer
echo $this->loadTemplate('head');
echo $this->loadTemplate('folder');
echo '<div class="spacescetion"></div>';

if ($user->authorise('core.create', 'com_playjoom')) {
    echo $this->loadTemplate('upload');
}
    
echo '<table width="100%">';
	echo '<tr valign="top">';
		 echo '<td>';
	          if (($user->authorise('core.create', 'com_playjoom')) and $this->require_ftp) {
	          	
	          	  echo '<form action="index.php?option=com_playjoom&amp;task=ftpValidate" name="ftpForm" id="ftpForm" method="post">';
					  echo '<fieldset title="'.JText::_('COM_PLAYJOOM_DESCFTPTITLE').'">';
					       echo '<legend>'.JText::_('COM_PLAYJOOM_DESCFTPTITLE').'</legend>';
					       echo JText::_('COM_PLAYJOOM_DESCFTP');
					       echo '<label for="username">'.JText::_('JGLOBAL_USERNAME').'</label>';
					       echo '<input type="text" id="username" name="username" class="inputbox" size="70" value="" />';
					       echo '<label for="password">'.JText::_('JGLOBAL_PASSWORD').'</label>';
					       echo '<input type="password" id="password" name="password" class="inputbox" size="70" value="" />';
					  echo '</fieldset>';
				 echo '</form>';
			  }
              
			  //Important form for submitting the upload datas
			  echo '<form action="index.php?option=com_playjoom" name="adminForm" id="mediamanager-form" method="post" enctype="multipart/form-data" >';
                  echo '<input type="hidden" name="task" value="" />';
                  echo '<input type="hidden" name="cb1" id="cb1" value="0" />';
                  echo '<input class="update-folder" type="hidden" name="folder" id="folder" value="'.$this->state->folder.'" />';
              echo '</form>';
		 echo '</td>';
	echo '</tr>';
echo '</table>';