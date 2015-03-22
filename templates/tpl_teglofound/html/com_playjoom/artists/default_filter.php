<?php
/**
 * Contains the default filter template for the artist output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Site
 * @subpackage views.artists.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2012-04-08 14:07:01 +0200 (So, 08. Apr 2012) $
 * @revision $Revision: 455 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/helpers/playjoom.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$user = JFactory::getUser();

JHtml::_('formbehavior.chosen', 'select');

echo '<fieldset class="batch">';
		echo '<legend>'.JText::_('JSEARCH_FILTER_LABEL').'</legend>';
		echo '<input type="text" name="filter_search" id="filter_search" value="'.$this->escape($this->state->get('filter.search')).'" title="'.JText::_('COM_CONTENT_FILTER_SEARCH_DESC').'" />';
        echo '<button type="submit" class="small button">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
		echo '<button type="button" class="small button" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';                       
		
		echo '<p class="filter-selector">';
		echo '<select name="filter_category_id" class="PJ-filtermenu" onchange="this.form.submit()">';
			echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_GENRE').'</option>';
			echo JHtml::_('select.options', $this->FilterItemsGenres, 'value', 'text', $this->state->get('filter.category_id'));
		echo '</select>';			                              
			    
		if ($this->params->get('show_all_users', 1)
			|| JAccess::check($user->get('id'), 'core.admin') == 1) {
			echo '<select name="filter_user_id" class="PJ-filtermenu" onchange="this.form.submit()">';
				echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_USER').'</option>';
				echo JHtml::_('select.options', $this->owner, 'value', 'text', $this->state->get('filter.user_id'));
			echo '</select>';
		}
		echo '</p>';
echo '</fieldset>';
echo '<div class="divider-view"></div>';