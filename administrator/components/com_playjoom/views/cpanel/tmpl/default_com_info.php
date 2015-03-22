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
jimport( 'joomla.application.component.helper' );

echo JHtml::_('sliders.start', 'audiofile-slider'); 
echo JHtml::_('sliders.panel',JText::_('COM_PLAYJOOM_SLIDER_CPANEL_INFO'),null);
                                                    
echo '<table class="adminlist">';

echo '<tr>';
echo '<td class="key"></td>';
echo '<td><a href="http://playjoom.teglo.info" target="_blank"><img src="components/com_playjoom/images/playjoom-logo.gif" align="middle" alt="Playjoom logo" style="border: none; margin: 8px;" /></a></td>';
echo '</tr>';

echo '<tr>';
echo '<td class="key" width="120">Web</td>';
echo '<td><a href="'.PlayJoomHelper::GetInstallInfo("authorUrl","playjoom.xml").'" target="_blank">'.PlayJoomHelper::GetInstallInfo("authorUrl","playjoom.xml").'</a></td>';
echo '</tr>';	

echo '<tr>';
echo '<td class="key">'.JText::_('COM_PLAYJOOM_INSTALL_VERSION').'</td>';
echo '<td>'.PlayJoomHelper::GetInstallInfo("version","playjoom.xml").'</td>';
echo '</tr>';

if ($this->cparams->get('version_check', 1) == 1)
{
	echo '<tr>';
       echo '<td class="key" valign="top">'.JText::_('COM_PLAYJOOM_AVAILABLE_VERSION').'</td>';
       echo '<td>'.PlayJoomHelper::GetAvailableVersion().'</td>';
    echo '</tr>';
}

echo '<tr>';
echo '<td class="key">'.JText::_('Date').'</td>';
echo '<td>'.PlayJoomHelper::GetInstallInfo("creationDate","playjoom.xml").'</td>';
echo '</tr>';
	   
echo '<tr>';
echo '<td class="key" valign="top">'.JText::_('Copyright').'</td>';
echo '<td>'.PlayJoomHelper::GetInstallInfo("copyright","playjoom.xml").'</td>';
echo '</tr>';
	   
echo '<tr>';
echo '<td class="key">'.JText::_('Author').'</td>';
echo '<td>'.PlayJoomHelper::GetInstallInfo("author","playjoom.xml").'</td>';
echo '</tr>';
	   
echo '<tr>';
echo '<td class="key" valign="top">'.JText::_('Description').'</td>';
echo '<td>'.PlayJoomHelper::GetInstallInfo("description","playjoom.xml").'</td>';
echo '</tr>';
	   
echo '<tr>';
echo '<td class="key">'.JText::_('License').'</td>';
echo '<td>GNU/GPL</td>';
echo '</tr>';

echo '</table>';
 
echo JHtml::_('sliders.end');