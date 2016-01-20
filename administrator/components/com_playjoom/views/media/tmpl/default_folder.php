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

$user = JFactory::getUser();

echo '<form action="index.php?option=com_playjoom&amp;task=folder.create&amp;tmpl=component&amp;view=media" name="folderForm" id="folderForm" method="post">';
	echo '<legend>'.JText::_('COM_PLAYJOOM_FILES').'</legend>';
	    echo '<div id="folderview">';
	        echo '<div class="view">';
			    echo '<iframe class="thumbnail" src="index.php?option=com_playjoom&amp;view=mediaList&amp;tmpl=component&amp;folder='.rtrim(strtr(base64_encode($this->state->folder), '+/', '-_'), '=').'" id="folderframe" name="folderframe" width="100%" height="1000px" marginwidth="0" marginheight="0" scrolling="auto"></iframe>';
		    echo '</div>';
			echo JHtml::_('form.token');
		echo '</div>';
echo '</form>';
