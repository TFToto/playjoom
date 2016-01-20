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
$dispatcher->trigger('onContentBeforeDisplay', array('com_playjoom.file', &$this->_tmp_img, &$params));

$ImageContent = file_get_contents($this->_tmp_img->path);
$coverthumb = PlayJoomMediaHelper::getCoverThumb($ImageContent, $this->_tmp_img);
?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div class="imgBorder center">
					<?php echo $coverthumb; ?>
				</div>
			</div>
			<div class="controls">
			<?php if ($user->authorise('core.delete', 'com_playjoom')):?>
				<a class="delete-item" target="_top" href="index.php?option=com_playjoom&amp;task=file.delete&amp;tmpl=index&amp;<?php echo JSession::getFormToken(); ?>=1&amp;folder=<?php echo rtrim(strtr(base64_encode($this->state->folder), '+/', '-_'), '='); ?>&amp;rm[]=<?php echo $this->_tmp_img->name; ?>" rel="<?php echo $this->_tmp_img->name; ?>"><?php echo JHtml::_('image', 'media/remove.png', JText::_('JACTION_DELETE'), array('width' => 16, 'height' => 16), true); ?></a>
			<?php endif;?>
			</div>
			<div class="imginfoBorder">
				<?php echo $this->escape(substr($this->_tmp_img->title, 0, 10) . (strlen($this->_tmp_img->title) > 10 ? '...' : '')); ?>
			</div>
		</div>
<?php
$dispatcher->trigger('onContentAfterDisplay', array('com_playjoom.file', &$this->_tmp_img, &$params));
?>
