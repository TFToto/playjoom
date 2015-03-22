<?php
/**
 * @package Joomla 3.x.x
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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

JHtml::_('behavior.framework');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal');

JPluginHelper::importPlugin('playjoom');
$dispatcher	= JDispatcher::getInstance();

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
$items = $this->items;
$n = count($this->items);

$modal_add2playlist_config = "{handler: 'iframe', size: {x: 550, y: 180}}";

JPluginHelper::importPlugin('playjoom');
$dispatcher	= JDispatcher::getInstance();

$tag_items_arr = array();

echo '<form action="'.htmlspecialchars(JUri::getInstance()->toString()).'" method="post" name="adminForm" id="adminForm" class="form-inline">';
	if ($this->params->get('show_headings') || $this->params->get('filter_field') || $this->params->get('show_pagination_limit')) {
		echo '<fieldset class="filters btn-toolbar">';
			if ($this->params->get('filter_field')) {
				echo '<div class="btn-group">';
					echo '<label class="filter-search-lbl element-invisible" for="filter-search">';
						echo JText::_('COM_TAGS_TITLE_FILTER_LABEL').'&#160;';
					echo '</label>';
					echo '<input type="text" name="filter-search" id="filter-search" value="'.$this->escape($this->state->get('list.filter')).'" class="inputbox" onchange="document.adminForm.submit();" title="'.JText::_('COM_TAGS_FILTER_SEARCH_DESC').'" placeholder="'.JText::_('COM_TAGS_TITLE_FILTER_LABEL').'" />';
				echo '</div>';
			}
			if ($this->params->get('show_pagination_limit')) {
				echo '<div class="btn-group pull-right">';
					echo '<label for="limit" class="element-invisible">';
						echo JText::_('JGLOBAL_DISPLAY_NUM');
					echo '</label>';
					echo $this->pagination->getLimitBox();
				echo '</div>';
			}

			echo '<input type="hidden" name="filter_order" value="" />';
			echo '<input type="hidden" name="filter_order_Dir" value="" />';
			echo '<input type="hidden" name="limitstart" value="" />';
			echo '<input type="hidden" name="task" value="" />';
			echo '<div class="clearfix"></div>';
		echo '</fieldset>';
	}

	if ($this->items == false || $n == 0) {
		echo '<p>'.JText::_('COM_TAGS_NO_ITEMS').'</p>';
	} else {

		echo '<ul class="side-nav category list-striped">';

		foreach ($items as $i => $item) {

			if ($item->core_state == 0) {
				echo '<li class="system-unpublished cat-list-row'.$i % 2 .'">';
			} else {

				// Create new stdClass Object
				$tag_item = new stdClass;

				if (isset($item->core_params)) {

					if ($item->core_params != '') {
						$tag_item_arr = json_decode($item->core_params);

						// Add some data to tag item object
						$tag_item->title = $this->escape($item->core_title);
						$tag_item->alias = $this->escape($item->core_alias);
						$tag_item->id = $item->content_item_id;
						$tag_item->file = $this->escape($tag_item_arr->file);
					}
				}

				//Plugins integration
				$this->events = new stdClass;

				$results = $dispatcher->trigger('onPrepareTrackLink', array(&$tag_item, $this->params));
				$this->events->PrepareTrackLink = trim(implode("\n", $results));

				$results = $dispatcher->trigger('onBeforeTrackLink', array(&$tag_item, $this->params));
				$this->events->BeforeTrackLink = trim(implode("\n", $results));

				$results = $dispatcher->trigger('onAfterTrackLink', array(&$tag_item, $this->params));
				$this->events->AfterTrackLink = trim(implode("\n", $results));

				//Check for Trackcontrol
				if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false) {
					$NoLink = sprintf ("%02d", $i +1);
					$TitleLink = $tag_item->title;
				} else {
					if (isset($tag_item->id)) {
						$NoLink = '<a href="index.php?option=com_playjoom&amp;view=broadcast&amp;id='.$tag_item->id.'" target="_blank" class="direct_link">'.sprintf ("%02d", $i +1).'</a>';
						$TitleLink = $this->events->PrepareTrackLink;
					} else {
						$NoLink = null;
						$TitleLink = null;
					}
				}

				echo '<li class="cat-list-row'.$i % 2 .' clearfix" >';

				//$track_text = '<span class="trackno">'.$NoLink.'</span>'.$itemElement->events->BeforeTrackLink.'<span class="tracktitle">'.$TitleLink.'</span>'.$itemElement->events->AfterTrackLink.'<span class="trackminutes">['.PlayJoomHelper::Playtime($tag_item_arr->length).' '.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').']</span><span class="add2playlist"><a href="index.php?option=com_playjoom&amp;view=addtoplaylist&amp;layout=modal&amp;tmpl=component&amp;id='.$tag_item->id.'" class="modal" style="margin-left: 45px;" rel="'.$modal_add2playlist_config.'">'.JText::_('COM_PLAYJOOM_ALBUM_ADD2PLAYLIST').'</span></a>';
				if ($NoLink && $TitleLink && $tag_item_arr->length) {
					$track_text =
						'<span class="trackno">'.$NoLink.'</span>'.
						$this->events->BeforeTrackLink.
						'<span class="tracktitle">'.$TitleLink.'</span>'.
						$this->events->AfterTrackLink.
						'<span class="trackminutes">['.PlayJoomHelper::Playtime($tag_item_arr->length).' '.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').']</span>';

					echo $track_text;
				}
			}

			array_push($tag_items_arr, $tag_item);

			$images  = json_decode($item->core_images);

			if ($this->params->get('tag_list_show_item_image', 1) == 1 && !empty($images->image_intro)) {

				echo '<img src="'.htmlspecialchars($images->image_intro).'" alt="'.htmlspecialchars($images->image_intro_alt).'">';
			}

			if ($this->params->get('tag_list_show_item_description', 1)) {
				echo '<span class="tag-body">';
					echo JHtml::_('string.truncate', $item->core_body, $this->params->get('tag_list_item_maximum_characters'));
				echo '</span>';
			}

			echo '<div class="clear"></div>';
			echo '</li>';
			if (isset($tag_item->id)) {
				echo '<li><span class="add2playlist"><a href="index.php?option=com_playjoom&amp;view=addtoplaylist&amp;layout=modal&amp;tmpl=component&amp;id='.$tag_item->id.'" class="modal" rel="'.$modal_add2playlist_config.'"><i class="fa fa-list-alt"></i>'.JText::_('COM_PLAYJOOM_ALBUM_ADD2PLAYLIST').'</span></a></li>';
			}
			echo '<li class="divider"></li>';
		}
		echo '</ul>';
	}
echo '</form>';