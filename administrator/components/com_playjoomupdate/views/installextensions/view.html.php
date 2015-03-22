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
 * @date $Date: 2013-03-20 09:46:28 +0100 (Mi, 20 Mrz 2013) $
 * @revision $Revision: 764 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/administrator/components/com_playjoomupdate/views/update/view.html.php $
 */

defined('_JEXEC') or die;

/**
 * PlayJoom Update default Viewer
 *
 * @package     PlayJoom.Administrator
 * @subpackage  com_playjoomupdate
 * @since       0.9
 */
class PlayjoomupdateViewInstallextensions extends JViewLegacy {

	function display($tpl = null)	{

		// Set the toolbar information
		JToolbarHelper::title(JText::_('COM_PLAYJOOMUPDATE'), 'update');

		$path_array = explode('*|*', JFactory::getApplication()->getUserState('com_playjoomupdate.paths.array'));
		if (count($path_array) > 1) {
			$this->total = count($path_array);
		} else {
			$this->total = 1;
		}
		$this->one_procent = 1/($this->total/100);

		// Set the toolbar
		$this->addToolBar();
		// Set the document
		$this->setDocument();

		parent::display();
	}

	/**
	 * Method for to set the toolbar items
	 *
	 * @return void
	 */
	protected function addToolBar() {

		JFactory::getApplication()->input->set('hidemainmenu', true);

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		$title = JText::_('JTOOLBAR_CANCEL');
		$dhtml = "<button onclick=\"window.location = 'index.php?option=com_pjupdate';\" class=\"btn btn-small\">
		<i class=\"icon-cancel\" title=\"$title\"></i>
		$title</button>";

	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() {

		//load javascripts
		JHtml::_('jquery.framework');

		$document = JFactory::getDocument();

		$js = "jQuery(function(){
				jQuery(\"#progressbar\").progressbar({
					value: 0
				});

				function load() {
					jQuery.ajax({
						url: 'index.php?option=com_playjoomupdate&task=update.executesetup&format=raw&total=".$this->total."&oneprocent=".$this->one_procent."&status='+jQuery( \"#progressbar\" ).progressbar( \"value\" ),
						success: function(data) {

							ajax = eval('(' + data + ')');

							if(ajax!=false) {

								jQuery(\"#progressbar\").progressbar({
									value: ajax.status
								});

								jQuery(\"#message\").html( ajax.message );
								jQuery(\"#message_path\").html( ajax.message_path );

								if(ajax.status<100) {
									load();
								} else {
				                    window.location = 'index.php?option=com_playjoomupdate&task=update.cleanup';
				                }
							}
						}
					});
				}

				load();
			});";
		$css = '#progressbar {
				   width:500px;
				   height: 30px;
			    }';
		$document->addScriptDeclaration($js);
		$document->addStyleDeclaration($css);

		$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/ui-lightness/jquery-ui.css');

		$document->addScript(JURI::base(true).'/components/com_playjoom/assets/js/jquery-1.9.1.min.js');
		$document->addScript(JURI::base(true).'/components/com_playjoom/assets/js/jquery.ui.widget.min.js');
		$document->addScript(JURI::base(true).'/components/com_playjoom/assets/js/jquery.ui.progressbar.min.js');
	}
}
