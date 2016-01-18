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
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Get numbers of albums and years, count the numbers in the getOptions array
$number_of_albums = PlayJoomArtistHelper::getOptions('album',base64_decode(JRequest::getVar('artist')));
$number_of_years = PlayJoomArtistHelper::getOptions('year',base64_decode(JRequest::getVar('artist')));
?>
				               
<fieldset class="batch">
		                       
		                               <legend><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		                                  <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
                                          <button type="submit" class="small button"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			                              <button type="button" class="small button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		                            
                                          <?php
                                          echo '<p class="filter-selector">';
                                          
			                              if (count($number_of_albums) > 1) {
			                              	echo '<select name="filter_album" class="PJ-filtermenu" onchange="this.form.submit()">';
			                              	echo '<option value="">'. JText::_('COM_PLAYJOOM_FILTER_ALBUM').'</option>';
			                              	echo JHtml::_('select.options', PlayJoomArtistHelper::getOptions('album',base64_decode(JRequest::getVar('artist'))), 'value', 'text', $this->state->get('filter.album'));
			                              	echo '</select>';
			                              }
			                              ?>	
			                              <select name="filter_category_id" class="PJ-filtermenu" onchange="this.form.submit()">
				                              <option value=""><?php echo JText::_('COM_PLAYJOOM_FILTER_GENRE');?></option>
				                              <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_playjoom'), 'value', 'text', $this->state->get('filter.category_id'));?>
			                              </select>
			                              <?php 
			                              if (count($number_of_years) > 1) {
			                              	echo '<select name="filter_year" class="PJ-filtermenu" onchange="this.form.submit()">';
			                              	echo '<option value="">'. JText::_('COM_PLAYJOOM_FILTER_YEAR').'</option>';
			                              	echo JHtml::_('select.options', PlayJoomArtistHelper::getOptions('year',base64_decode(JRequest::getVar('artist'))), 'value', 'text', $this->state->get('filter.year'));
			                              	echo '</select>';
			                              }
			                              echo '</p>';
echo '</fieldset>';
echo '<div class="divider-view"></div>';
