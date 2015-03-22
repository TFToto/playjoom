<?php
/**
 * Contains the default viewer method for the PlayJomm update component.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage views.playjoomupdate
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

/**
 * PlayJoom Update default Viewer
 *
 * @package     PlayJoom.Administrator
 * @subpackage  com_playjoomupdate
 * @since       0.9
 */
class PlayjoomupdateViewUpdate extends JViewLegacy {

	/**
	 * Renders the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 */
	public function display($tpl=null)
	{
		$password = JFactory::getApplication()->getUserState('com_playjoomupdate.password', null);
		$filesize = JFactory::getApplication()->getUserState('com_playjoomupdate.filesize', null);
		$ajaxUrl = JURI::base().'components/com_playjoomupdate/restore.php';
		//$returnUrl = 'index.php?option=com_playjoomupdate&task=update.finalise';
		$returnUrl = 'index.php?option=com_playjoomupdate&task=update.extensionssetup';

		// Set the toolbar information
		JToolbarHelper::title(JText::_('COM_PLAYJOOMUPDATE_OVERVIEW'), 'install');

		// Add toolbar buttons
		JToolbarHelper::preferences('com_playjoomupdate');

		// Load mooTools
		JHtml::_('behavior.framework', true);

		$updateScript = <<<ENDSCRIPT
var playjoomupdate_password = '$password';
var playjoomupdate_totalsize = '$filesize';
var playjoomupdate_ajax_url = '$ajaxUrl';
var playjoomupdate_return_url = '$returnUrl';

ENDSCRIPT;

		// Load our Javascript
		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true).'/administrator/components/com_playjoomupdate/assets/js/update.js');
		$document->addScript(JURI::root(true).'/administrator/components/com_playjoomupdate/assets/js/json2.js');
		$document->addScript(JURI::root(true).'/administrator/components/com_playjoomupdate/assets/js/encryption.js');

		JHtml::_('script', 'system/progressbar.js', true, true);
		JHtml::_('stylesheet', 'media/mediamanager.css', array(), true);
		$document->addScriptDeclaration($updateScript);

		// Render the view
		parent::display($tpl);
	}

}
