<?php
/**
 * Contains the complete template for the PlayJomm update component.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage template.playjoomupdate
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoomUpdate Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die;
?>

<fieldset>
	<legend>
		<?php echo JText::_('COM_PLAYJOOMUPDATE_VIEW_COMPLETE_HEADING') ?>
	</legend>
	<p>
		<?php echo JText::sprintf('COM_PLAYJOOMUPDATE_VIEW_COMPLETE_MESSAGE', PJVERSION); ?>
	</p>
</fieldset>
