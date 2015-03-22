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
defined('_JEXEC') or die('Restricted access');

// Imports
jimport('joomla.installer.installer');

//Install the modules
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_newcontents');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_populartracks');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_alphabeticalbar');
//$installer = new JInstaller();
//$installer->install($this->parent->getPath('source').'/extensions/mod_pj_albumgallery');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_statistics');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_lastplayed');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_quickicon');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_menu');
//Install the plugins
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/playjoom/trackcontrol');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/playjoom/trackvote');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/playjoom/playbutton');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/playjoom/360player');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/playjoom/tracktimeinfo');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/system/pjtemplatetoggle');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/system/pjauth');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/system/pjlogger');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/search/pj_tracks');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/search/pj_artists');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/search/pj_albums');
//Install the templates
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/templates/teglo');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/templates/tegloadmin');
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/templates/tegloflag');
?>

<h3>PlayJoom Installation</h3>
<h4><a href="index.php?option=com_playjoom">Go to PlayJoom</a></h4>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<th colspan="3"><?php echo JText::_('Core'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom - '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3">Admin<?php echo JText::_('Module'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Quick Icon'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Admin Menu'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3">Site<?php echo JText::_('Module'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom New Content'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
        <tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Popular Content'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
        <tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Alphabeticalbar'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Ablumgallery'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Statistics'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Last Played'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3"><?php echo JText::_('Plugins'); ?></th>
		</tr>
        <tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Trackcontrol'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
        <tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Trackvote'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Playbutton'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Track Time Info'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>	
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Template Toggle'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Track Search'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Artist Search'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Album Search'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PlayJoom Auth'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'PlayJoom Logger'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3">Templates</th>
		</tr>
        <tr class="row0">
			<td class="key" colspan="2"><?php echo 'PJ Teglo'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
        <tr class="row1">
			<td class="key" colspan="2"><?php echo 'PJ Teglo Admin'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'PJ Teglo Mobile'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>		
	</tbody>
</table>