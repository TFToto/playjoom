<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ADMIN_SYSTEM_INFORMATION'); ?></legend>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="25%">
					<?php echo JText::_('COM_ADMIN_SETTING'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_ADMIN_VALUE'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">&#160;</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_PHP_BUILT_ON'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['php'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_DATABASE_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['dbversion'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_DATABASE_COLLATION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['dbcollation'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_PHP_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['phpversion'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_WEB_SERVER'); ?></strong>
				</td>
				<td>
					<?php echo JHtml::_('system.server', $this->info['server']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_WEBSERVER_TO_PHP_INTERFACE'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['sapi_name'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_PLAYJOOM_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['pj_version'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_JOOMLA_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['version'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_PLATFORM_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['platform'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_USER_AGENT'); ?></strong>
				</td>
				<td>
					<?php echo htmlspecialchars($this->info['useragent']);?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ADMIN_3THPARTY_INFORMATION'); ?></legend>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="25%">
					<?php echo JText::_('COM_ADMIN_SETTING'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_ADMIN_VALUE'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">&#160;</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_GET3ID_VERSION'); ?></strong>
				</td>
				<td>
					<?php echo $this->info['getID3_version'];?>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_JQUERY_VERSION'); ?></strong>
				</td>
				<td>
					<div id="getjQueryversion"></div>
					<script type="text/javascript">jQuery( "#getjQueryversion" ).append( document.createTextNode( jQuery.fn.jquery ) );</script>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_MOOTOOLS_VERSION'); ?></strong>
				</td>
				<td>
					<div id="getMooToolsversion"></div>
					<script type="text/javascript">jQuery( "#getMooToolsversion" ).append( document.createTextNode( MooTools.version ) );</script></td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_BOOTSTRAP_VERSION'); ?></strong>
				</td>
				<td>
					<div id="getBootstrapversion"></div>
					<script type="text/javascript">
						jQuery(function () {
							jQuery.get("../media/jui/css/bootstrap.min.css", function (data) {
								jQuery( "#getBootstrapversion" ).append( document.createTextNode( data.match(/[.\d]+[.\d]/) ) );
							});
						});
					</script></td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_FOUNDATION_VERSION'); ?></strong>
				</td>
				<td>
					<?php
					$doc = JFactory::getDocument();
					$doc->addScript(JURI::root(true).'/templates/teglofound/foundation/js/foundation.min.js');
					?>
					<div id="getFoundationversion"></div>
					<script type="text/javascript">jQuery( "#getFoundationversion" ).append( document.createTextNode( Foundation.version ) );</script>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_SOUNDMANAGER_VERSION'); ?></strong>
				</td>
				<td>
					<?php
					$doc = JFactory::getDocument();
					$doc->addScript(JURI::root(true).'/plugins/playjoom/360player/js/soundmanager2-jsmin.js');
					?>
					<div id="getSoundManagerversion"></div>
					<script type="text/javascript">jQuery( "#getSoundManagerversion" ).append( document.createTextNode( soundManager.versionNumber ) );</script>
				</td>
			</tr>
			<tr>
				<td>
					<strong><?php echo JText::_('COM_ADMIN_FONTAWESOME_VERSION'); ?></strong>
				</td>
				<td>
					<div id="getFontAwesomeversion"></div>
					<script type="text/javascript">
						jQuery(function () {
							jQuery.get("../templates/teglofound/css/font-awesome.min.css", function (data) {
								jQuery( "#getFontAwesomeversion" ).append( document.createTextNode( data.match(/[.\d]+[.\d]/) ) );
							});
						});
					</script></td>
			</tr>
		</tbody>
	</table>
</fieldset>