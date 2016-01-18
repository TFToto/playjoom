<?php
/**
 * Contains the default navigation template for the media output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Admin
 * @subpackage views.media.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die;
$app	= JFactory::getApplication();
$style = $app->getUserStateFromRequest('media.list.layout', 'layout', 'details', 'word');

?>
<ul class="nav nav-list">
	<li style="margin-bottom:15px;"><a href="#" id="thumbs" onclick="MediaManager.setViewType('thumbs')" class="btn <?php echo ($style == "thumbs") ? 'active' : '';?>">
	<i class="icon-grid-view-2"></i> <?php echo JText::_('COM_PLAYJOOM_THUMBNAIL_VIEW'); ?></a></li>
	<li style="margin-bottom:15px;"><a href="#" id="details" onclick="MediaManager.setViewType('details')" class="btn <?php echo ($style == "details") ? 'active' : '';?>">
	<i class="icon-list-view"></i> <?php echo JText::_('COM_PLAYJOOM_DETAIL_VIEW'); ?></a><li>
	<hr />
	<li style="margin-bottom:15px;"><button data-toggle="collapse" data-target="#collapseFolder" class="btn"><i class="icon-folder" title="<?php echo JText::_('COM_PLAYJOOM_CREATE_FOLDER');?>"></i><?php echo JText::_('COM_PLAYJOOM_CREATE_FOLDER');?></button><li>
	<li style="margin-bottom:15px;"><button data-toggle="collapse" data-target="#collapseUpload" class="btn"><i class="icon-upload" title="<?php echo JText::_('JTOOLBAR_UPLOAD');?>"></i><?php echo JText::_('JTOOLBAR_UPLOAD');?></button><li>
</ul>