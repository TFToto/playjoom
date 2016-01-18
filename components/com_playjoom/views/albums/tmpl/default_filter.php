<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();

echo '<fieldset class="batch">';
		echo '<legend>'.JText::_('JSEARCH_FILTER_LABEL').'</legend>';
		echo '<input type="text" name="filter_search" id="filter_search" value="'.$this->escape($this->state->get('filter.search')).'" title="'.JText::_('COM_CONTENT_FILTER_SEARCH_DESC').'" />';
		echo '<button type="submit" class="small button">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
		echo '<button type="button" class="small button" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';

		echo '<p class="filter-selector">';
		echo '<select name="filter_artist" class="PJ-filtermenu" onchange="this.form.submit()">';
			echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
			echo JHtml::_('select.options', $this->FilterItemsArtists, 'value', 'text', $this->state->get('filter.artist'));				
		echo '</select>';
			     
		echo '<select name="filter_category_id" class="PJ-filtermenu" onchange="this.form.submit()">';
			echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_GENRE').'</option>';
			echo JHtml::_('select.options', $this->FilterItemsGenres, 'value', 'text', $this->state->get('filter.category_id'));
		echo '</select>';
			     
		echo '<select name="filter_year" class="PJ-filtermenu" onchange="this.form.submit()">';
			echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_YEAR').'</option>';
			echo JHtml::_('select.options', $this->FilterItemsYears, 'value', 'text', $this->state->get('filter.year'));				
		echo '</select>';

		if ($this->params->get('show_all_users', 1)
			|| JAccess::check($user->get('id'), 'core.admin') == 1) {
			echo '<select name="filter_user_id" class="PJ-filtermenu" onchange="this.form.submit()">';
				echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_USER').'</option>';
				echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.user_id'));
			echo '</select>';
		}
		echo '</p>';
echo '</fieldset>';
echo '<div class="divider-view"></div>';