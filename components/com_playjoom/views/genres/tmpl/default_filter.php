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
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JLoader::import( 'helpers.genres', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

JHtml::_('formbehavior.chosen', 'select');

//Get User objects
$user = JFactory::getUser();

echo '<fieldset class="batch">';
		echo '<legend>'.JText::_('JSEARCH_FILTER_LABEL').'</legend>';
		echo '<input type="text" name="filter_search" id="filter_search" value="'.$this->escape($this->state->get('filter.search')).'" title="'.JText::_('COM_CONTENT_FILTER_SEARCH_DESC').'" />';
        echo '<button type="submit" class="small button">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
	    echo '<button type="button" class="small button" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';		
	    
	    echo '<p class="filter-selector">';
	    echo '<select name="filter_artist" class="PJ-filtermenu" onchange="this.form.submit()">';
			echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
		    echo JHtml::_('select.options', PlayJoomGenresHelper::getOptions('artist'), 'value', 'text', $this->state->get('filter.artist'));			
	    echo '</select>';                                          
			                              
	    echo '<select name="filter_year" class="PJ-filtermenu" onchange="this.form.submit()">';
	        echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_YEAR').'</option>';
		    echo JHtml::_('select.options', PlayJoomGenresHelper::getOptions('year'), 'value', 'text', $this->state->get('filter.year'));			
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