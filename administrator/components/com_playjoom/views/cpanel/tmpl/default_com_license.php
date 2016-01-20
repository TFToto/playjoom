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
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

echo JHtml::_('sliders.start', 'audiofile-slider'); 
echo JHtml::_('sliders.panel',JText::_('COM_PLAYJOOM_SLIDER_CPANEL_LICENSE'),null);
                                                    
echo '<table class="adminlist">';

echo '<tr>';
echo '<td valign="top" class="key">GNU/GPL</td>';
echo '<td><b>This program is free software:</b> You can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
      <br />
      <br />
      This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
      <br />
      <br />
      You should have received a copy of the GNU General Public License along with this program.  
      <br />
      <br />  
      If not, see <a href="http://www.gnu.org/licenses">http://www.gnu.org/licenses</a></td>';
echo '</tr>';

echo '</table>';
 
echo JHtml::_('sliders.end');