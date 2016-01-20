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
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/sections/tmpl/default_filter.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JLoader::import( 'helpers.sections', JPATH_SITE .DS.'components'.DS.'com_playjoom');

$user = JFactory::getUser();

switch($this->params->get('show_section')) {
	
	case 'artist' :
		echo '<fieldset id="filter-bar">';
		    echo '<div class="filter-search fltlft">';
		        echo '<label class="filter-search-lbl" for="filter_search">'.JText::_('JSEARCH_FILTER_LABEL').'</label><br />';
		        echo '<input type="text" name="filter_search" id="filter_search" value="'.$this->escape($this->state->get('filter.search')).'" title="'.JText::_('COM_CONTENT_FILTER_SEARCH_DESC').'" />';
                echo '<button type="submit" class="btn">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
			    echo '<button type="button" class="btn" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';                       
		                       		                             
			    echo '<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">';
				    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_GENRE').'</option>';
				    echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('genre'), 'value', 'text', $this->state->get('filter.category_id'));
			    echo '</select>';			                              
			    
			    if ($this->params->get('show_all_users', 1)
			     || JAccess::check($user->get('id'), 'core.admin') == 1) {
			    	echo '<select name="filter_user_id" class="inputbox" onchange="this.form.submit()">';
				        echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_USER').'</option>';
				        echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.user_id'));
			        echo '</select>';
			    }
			echo '</div>';
        echo '</fieldset>';
    
    break;
    
	case 'album' :
		echo '<fieldset id="filter-bar">';
		     echo '<div class="filter-search fltlft">';
		         echo '<label class="filter-search-lbl" for="filter_search">'.JText::_('JSEARCH_FILTER_LABEL').'</label><br />';
		         echo '<input type="text" name="filter_search" id="filter_search" value="'.$this->escape($this->state->get('filter.search')).'" title="'.JText::_('COM_CONTENT_FILTER_SEARCH_DESC').'" />';
                 echo '<button type="submit" class="btn">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
			     echo '<button type="button" class="btn" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';
			     
			     echo '<select name="filter_artist" class="inputbox" onchange="this.form.submit()">';
				     echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
				     echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('artist'), 'value', 'text', $this->state->get('filter.artist'));				
			     echo '</select>';
			     
			     echo '<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">';
				     echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_GENRE').'</option>';
				     echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('genre'), 'value', 'text', $this->state->get('filter.category_id'));
			     echo '</select>';
			     
			     echo '<select name="filter_year" class="inputbox" onchange="this.form.submit()">';
				     echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_YEAR').'</option>';
				     echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('year'), 'value', 'text', $this->state->get('filter.year'));				
			     echo '</select>';

			     if ($this->params->get('show_all_users', 1)
			      || JAccess::check($user->get('id'), 'core.admin') == 1) {
			     	echo '<select name="filter_user_id" class="inputbox" onchange="this.form.submit()">';
				        echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_USER').'</option>';
				        echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.user_id'));
			        echo '</select>';
			     }
		     echo '</div>';
         echo '</fieldset>';
		                    
    break;
	
	case 'year' :
	    
		echo '<fieldset id="filter-bar">';		                               
		    echo '<div class="filter-select fltrt">';
		        echo '<select name="filter_year" class="inputbox" onchange="this.form.submit()">';
				    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_YEAR').'</option>';
				    echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('year'), 'value', 'text', $this->state->get('filter.year'));				
			    echo '</select>';
			                              
			    if ($this->params->get('show_all_users', 1)
			     || JAccess::check($user->get('id'), 'core.admin') == 1) {
			    	echo '<select name="filter_user_id" class="inputbox" onchange="this.form.submit()">';
			    	    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_USER').'</option>';
				        echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.user_id'));
			        echo '</select>';
			    }
		    echo '</div>';
        echo '</fieldset>';

	break;

   default :
		echo '<fieldset id="filter-bar">';
		    echo '<div class="filter-search fltlft">';
		        echo '<label class="filter-search-lbl" for="filter_search">'.JText::_('JSEARCH_FILTER_LABEL').'</label><br />';
		        echo '<input type="text" name="filter_search" id="filter_search" value="'.$this->escape($this->state->get('filter.search')).'" title="'.JText::_('COM_CONTENT_FILTER_SEARCH_DESC').'" />';
                echo '<button type="submit" class="btn">'.JText::_('JSEARCH_FILTER_SUBMIT').'</button>';
			    echo '<button type="button" class="btn" onclick="document.id(\'filter_search\').value=\'\';this.form.submit();">'.JText::_('JSEARCH_FILTER_CLEAR').'</button>';
		    echo '</div>';
		    
		    echo '<div class="filter-select fltrt">';
			    echo '<select name="filter_artist" class="inputbox" onchange="this.form.submit()">';
				    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
				    echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('artist'), 'value', 'text', $this->state->get('filter.artist'));			
			    echo '</select>';
                
                echo '<select name="filter_album" class="inputbox" onchange="this.form.submit()">';
				    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ALBUM').'</option>';
				    echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('album'), 'value', 'text', $this->state->get('filter.album'));				
			    echo '</select>';	
			    
			    echo '<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">';
				    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_GENRE').'</option>';
				    echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('genre'), 'value', 'text', $this->state->get('filter.category_id'));
			    echo '</select>';
			    
			    echo '<select name="filter_year" class="inputbox" onchange="this.form.submit()">';
				    echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_YEAR').'</option>';
				    echo JHtml::_('select.options', PlayJoomSectionHelper::getOptions('year'), 'value', 'text', $this->state->get('filter.year'));				
			    echo '</select>';

			    if ($this->params->get('show_all_users', 1)
			     || JAccess::check($user->get('id'), 'core.admin') == 1) {
			    	echo '<select name="filter_user_id" class="inputbox" onchange="this.form.submit()">';
				        echo '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_USER').'</option>';
				        echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.user_id'));
			        echo '</select>';
			    }
		    echo '</div>';
        echo '</fieldset>';
}