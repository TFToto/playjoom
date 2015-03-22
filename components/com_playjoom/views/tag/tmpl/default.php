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
 * @copyright Copyright (C) 2010-2014 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-10-27 11:53:25 +0100 (So, 27 Okt 2013) $
 * @revision $Revision: 859 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/album/tmpl/default.php $
 */

defined('_JEXEC') or die;
// Note that there are certain parts of this layout used only when there is exactly one tag.

//Get plugin contents
$item_onBeforePJContent = $this->events->onBeforePJContent;
$item_onAfterPJContent  = $this->events->onAfterPJContent;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
$isSingleTag = (count($this->item) == 1);

echo $item_onBeforePJContent;

echo '<div class="tag-category'.$this->pageclass_sfx.'">';

	//Tag title configuration
	if($this->params->get('show_tag_title', 1)) {
		echo '<h2 class="subheader">';
			echo '<i class="fa fa-tag"></i>'.$this->item[0]->title;
		echo '</h2>';
	}

	// We only show a tag description if there is a single tag.
	if (count($this->item) == 1 && (($this->params->get('tag_list_show_tag_image', 1)) || $this->params->get('tag_list_show_tag_description', 1))) {
		echo '<div class="category-desc">';
			$images = json_decode($this->item[0]->images);
			if ($this->params->get('tag_list_show_tag_image', 1) == 1 && !empty($images->image_fulltext)) {
				echo '<img src="'.htmlspecialchars($images->image_fulltext).'">';
			}
			if ($this->params->get('tag_list_show_tag_description') == 1 && $this->item[0]->description) {
				echo JHtml::_('content.prepare', $this->item[0]->description, '', 'com_tags.tag');
			}
			echo '<div class="clr"></div>';
		echo '</div>';
	}
	// If there are multiple tags and a description or image has been supplied use that.
	if ($this->params->get('tag_list_show_tag_description', 1) || $this->params->get('show_description_image', 1)) {
		if ($this->params->get('show_description_image', 1) == 1 && $this->params->get('tag_list_image')) {
			echo '<img src="'.$this->params->get('tag_list_image').'">';
		}
		if ($this->params->get('tag_list_description', '') > '') {
			echo JHtml::_('content.prepare', $this->params->get('tag_list_description'), '', 'com_tags.tag');
		}
	}

	echo $this->loadTemplate('items');

	if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) {
		echo '<div class="pagination">';
			if ($this->params->def('show_pagination_results', 1)) {
				echo '<p class="counter pull-right"> '.$this->pagination->getPagesCounter().'</p>';
			}
			echo $this->pagination->getPagesLinks();
		echo '</div>';
	}
echo '</div>';

echo $item_onAfterPJContent;
