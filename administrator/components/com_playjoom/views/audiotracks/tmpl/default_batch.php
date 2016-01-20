<?php
/**
 * Contains the batch input template for the list of audiotracks output.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage views.audiotracks.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// no direct access
defined('_JEXEC') or die;

echo '<div class="modal hide fade" id="collapseModal">';

	echo '<div class="modal-header">';
		echo '<button type="button" class="close" data-dismiss="modal">&#215;</button>';
		echo '<h3>'.JText::_('COM_PLAYJOOM_AUDIOTRACKS_BATCH_OPTIONS').'</h3>';
	echo '</div>';

	echo '<div class="modal-body">';

		echo '<p>'.JText::_('COM_PLAYJOOM_AUDIOTRACKS_BATCH_TIP').'</p>';

		echo '<fieldset id="batch-choose-action" class="combo">';
			echo JHtml::_('batch.access');
			echo JHtml::_('batch.user');
			//echo JHtml::_('batch.tag');

			echo '<label id="batch-user-lbl" for="batch-user" class="hasTip" title="Set User::Not making a selection will keep the original user when processing.">'.JText::_('COM_PLAYJOOM_AUDIOTRACKS_SET_GENRE').'</label>';
              echo '<select name="batch[category_id]" class="inputbox" id="batch-category-id">';
		          echo '<option value="">'.JText::_('JSELECT').'</option>';
	              echo JHtml::_('select.options', JHtml::_('category.categories', 'com_playjoom', null));
	          echo '</select>';

	          echo '<label id="batch-user-lbl" for="batch-user" class="hasTip" title="Set User::Not making a selection will keep the original user when processing.">'.JText::_('COM_PLAYJOOM_AUDIOTRACKS_SET_TRACKLEVEL').'</label>';
	          echo '<select name="batch[trackfilter_id]" class="inputbox" id="batch-trackfilter-id">';
	          echo '<option value="">'.JText::_('JSELECT').'</option>';
	          echo JHtml::_('select.options', JHtml::_('category.categories', 'com_playjoom.trackfilter', null));
	          echo '</select>';

	      echo '</fieldset>';

	      echo '<fieldset id="batch-choose-action" class="combo">';
	          echo '<label class="filter-search-lbl">'.JText::_('COM_PLAYJOOM_AUDIOTRACKS_SET_ARTIST').'</label><input type="text" name="batch[artist]" value="" title="COM_PLAYJOOM_AUDIOTRACKS_SET_ARTIST_DESC" />';
          echo '</fieldset>';

          echo '<fieldset id="batch-choose-action" class="combo">';
              echo '<label class="filter-search-lbl">'.JText::_('COM_PLAYJOOM_AUDIOTRACKS_SET_ALBUM').'</label><input type="text" name="batch[album]" value="" title="COM_PLAYJOOM_AUDIOTRACKS_SET_ALBUM_DESC" />';
          echo '</fieldset>';

          echo '<fieldset id="batch-choose-action" class="combo">';
              echo '<label class="filter-search-lbl">'.JText::_('COM_PLAYJOOM_AUDIOTRACKS_SET_YEAR').'</label><input type="text" name="batch[year]" value="" title="COM_PLAYJOOM_AUDIOTRACKS_SET_YEAR_DESC" />';
          echo '</fieldset>';

			echo '<div class="control-label">';
				echo '<label id="batch_id3tags-lbl" for="batch_id3tags">'.JText::_('COM_PLAYJOOM_SET_ID3TAGS').'</label>';
			echo '</div>';
			echo '<div class="controls">';
				echo '<fieldset id="batch_id3tags" class="radio btn-group" ><input type="radio" id="batch_id3tags0" name="batch[id3tags]" value="1" /><label for="batch_id3tags0" >'.JText::_('JYES').'</label><input type="radio" id="batch_id3tags1" name="batch[id3tags]" value="0" checked="checked" /><label for="batch_id3tags1" >'.JText::_('JNO').'</label></fieldset>';
			echo '</div>';

     echo '</div>';

     // Buttons for processes
     echo '<div class="modal-footer">';
         echo '<button class="btn btn-primary" type="submit" onclick="submitbutton(\'audiotrack.batch\');">'.JText::_('JGLOBAL_BATCH_PROCESS').'</button>';
         echo '<button class="btn" type="button" onclick="document.id(\'batch-category-id\').value=\'\';document.id(\'batch-access\').value=\'\';document.id(\'batch-language-id\').value=\'\';document.id(\'batch-user-id\').value=\'\'">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';
     echo '</div>';
echo '</div>';