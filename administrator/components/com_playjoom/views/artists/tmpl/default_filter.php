<?php
/**
 * Contains the template for the filter function.
 * 
  * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Admin
 * @subpackage views.artists
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

echo '<fieldset id="filter-bar">';
    echo '<div class="filter-search fltlft">';
	    echo '<label class="filter-search-lbl" for="filter_search">'.JText::_('JSEARCH_FILTER_LABEL').'</label>';
		echo '<input type="text" name="filter_search" id="filter_search" value="'.$this->escape($this->state->get('filter.search')).'" title="'.JText::_('COM_CONTENT_FILTER_SEARCH_DESC').'" />';
        echo '<button type="submit" class="btn">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
	    echo '<button type="button" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';
	echo '</div>';
		
	echo '<div class="filter-select fltrt">';

	    echo '<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">';
		    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_GENRE').'</option>';
		    echo JHtml::_('select.options', $this->FilterItemsGenres, 'value', 'text', $this->state->get('filter.category_id'));
	    echo '</select>';
    echo '</div>';
echo '</fieldset>';