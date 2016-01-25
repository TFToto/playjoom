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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();;    
   	
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

?>
<form action="<?php echo JRoute::_('index.php?option=com_playjoom&view=adminplaylists&action=del');?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="addtrack">
		  <?php echo $this->loadTemplate('toolbar');?>
		 <div class="filter-select fltrt">	
		 <ul class="playlist">
		   <li id="playlist_info"><?php echo JText::_('COM_PLAYJOOM_DELPLAYLIST_NAME'); ?>:&nbsp;<?php echo $this->playlistinfo->name; ?></li>
		   <li id="playlist_info"><?php echo JText::_('COM_PLAYJOOM_DELPLAYLIST_CREATE'); ?>:&nbsp;<?php echo $this->playlistinfo->create_date; ?></li>
		   <li id="playlist_info"><?php echo JText::_('COM_PLAYJOOM_DELPLAYLIST_MODIFIER'); ?>:&nbsp;<?php echo $this->playlistinfo->modifier_date; ?></li>
		   <li id="playlist_info"><?php echo JText::_('COM_PLAYJOOM_DELPLAYLIST_NO_OF_TRACKS'); ?>:&nbsp;<?php echo PlayJoomHelper::getPlaylistEntries(JRequest::getVar('id')); ?></li>
		 </ul>
		</div>
	</fieldset>
	<div>
		<input type="hidden" name="task" value="" />
        <input type="hidden" name="list_id" value="<?php echo $this->playlistinfo->id; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>