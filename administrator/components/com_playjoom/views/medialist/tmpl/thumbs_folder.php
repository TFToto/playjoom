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
?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div align="center" class="imgBorder">
					<a href="index.php?option=com_playjoom&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo rtrim(strtr(base64_encode($this->_tmp_folder->path_relative), '+/', '-_'), '='); ?>" target="folderframe">
						<?php echo JHtml::_('image', 'media/folder.png', JText::_('COM_PLAYJOOM_FOLDER'), array('width' => 80, 'height' => 80, 'border' => 0), true); ?></a>
				</div>
			</div>
			<div class="controls">
			<?php if ($user->authorise('core.delete', 'com_playjoom')):?>
				<a class="delete-item" target="_top" href="index.php?option=com_playjoom&amp;task=folder.delete&amp;tmpl=index&amp;<?php echo JSession::getFormToken(); ?>=1&amp;folder=<?php echo rtrim(strtr(base64_encode($this->state->folder), '+/', '-_'), '='); ?>&amp;rm[]=<?php echo $this->_tmp_folder->name; ?>" rel="<?php echo $this->_tmp_folder->name; ?> :: <?php echo $this->_tmp_folder->files+$this->_tmp_folder->folders; ?>"><?php echo JHtml::_('image', 'media/remove.png', JText::_('JACTION_DELETE'), array('width' => 16, 'height' => 16, 'border' => 0), true); ?></a>
			<?php endif;?>
			</div>
			<div class="imginfoBorder">
				<a href="index.php?option=com_playjoom&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo rtrim(strtr(base64_encode($this->_tmp_folder->path_relative), '+/', '-_'), '='); ?>" target="folderframe"><?php echo substr($this->_tmp_folder->name, 0, 10) . (strlen($this->_tmp_folder->name) > 10 ? '...' : ''); ?></a>
			</div>
		</div>
