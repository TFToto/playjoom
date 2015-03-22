<?php
/**
 * Contains the default folder template for the media output.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage views.media
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Media component
 *
 * @package	PlayJoom.Administrator
 * @subpackage	com_playjoom
 * @since 1.0
 */
class PlayJoomViewSaveTracks extends JViewLegacy {

	function display($tpl = null)	{

		// Set the toolbar information
		JToolbarHelper::title(JText::_('COM_PLAYJOOM_SAVETRACKS_RUNNING'), 'runinng');

		$file_array = explode('*|*', JFactory::getApplication()->getUserState('com_playjoom.path.array'));
		if (count($file_array) > 1) {
			$this->total = count($file_array); //-1 because the array will alway starts with index number 0
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
		$dhtml = "<button onclick=\"window.location = 'index.php?option=com_playjoom&view=audiotracks';\" class=\"btn btn-small\">
		<i class=\"icon-cancel\" title=\"$title\"></i>
		$title</button>";

		$bar->appendButton('Custom', $dhtml, 'Add_folder-tracks');
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
						url: 'index.php?option=com_playjoom&task=savetracks.append&format=raw&total=".$this->total."&oneprocent=".$this->one_procent."&status='+jQuery( \"#progressbar\" ).progressbar( \"value\" ),
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
				                    window.location = 'index.php?option=com_playjoom&view=audiotracks';
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

		$document->addScript(JURI::base(true).'/components/com_playjoom/assets/js/jquery.ui.widget.min.js');
		$document->addScript(JURI::base(true).'/components/com_playjoom/assets/js/jquery.ui.progressbar.min.js');
	}
}
