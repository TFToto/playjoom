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
class PlayjoomupdateViewDefault extends JViewLegacy
{
	/**
	 * Renders the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @since  0.9
	 */
	public function display($tpl=null)
	{
		// Get data from the model
		$this->state = $this->get('State');

		// Load useful classes
		$model = $this->getModel();
		$this->loadHelper('select');

		// Assign view variables
		$ftp = $model->getFTPOptions();
		$this->assign('updateInfo', $model->getUpdateInformation());
		$this->assign('methodSelect', PlayjoomupdateHelperSelect::getMethods($ftp['enabled']));

		// Set the toolbar information
		JToolbarHelper::title(JText::_('COM_PLAYJOOMUPDATE_OVERVIEW'), 'install');
		JToolbarHelper::custom('update.purge', 'purge', 'purge', 'JTOOLBAR_PURGE_CACHE', false, false);

		// Add toolbar buttons
		JToolbarHelper::preferences('com_playjoomupdate');

		// Load mooTools
		JHtml::_('behavior.framework', true);
		JHtml::_('stylesheet', 'media/mediamanager.css', array(), true);

		// Load our Javascript
		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true).'/administrator/components/com_playjoomupdate/assets/js/default.js');

		// Render the view
		parent::display($tpl);
	}

}
