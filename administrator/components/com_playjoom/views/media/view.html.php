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
class PlayJoomViewMedia extends JViewLegacy {

	function display($tpl = null)	{

		$app	= JFactory::getApplication();
		$config = JComponentHelper::getParams('com_playjoom');

		//Get User Objects
		$user  = JFactory::getUser();
		$canDo = PlayJoomHelper::getActions();

		$lang	= JFactory::getLanguage();

		$style = $app->getUserStateFromRequest('media.list.layout', 'layout', 'details', 'word');

		$document = JFactory::getDocument();
		$document->setBuffer($this->loadTemplate('navigation'), 'modules', 'submenu');

		JHtml::_('behavior.framework', false);

		$document->addScript(JURI::base(true).'/components/com_playjoom/assets/js/mediamanager.js');
		JHtml::_('stylesheet', 'media/mediamanager.css', array(), true);

		if ($lang->isRTL()) {
			JHtml::_('stylesheet', 'media/mediamanager_rtl.css', array(), true);
		}

		if ($config->get('enable_flash', 1)) {
			$fileTypes = $config->get('upload_audio_extensions', 'mp3,wav,flac');
			$types = explode(',', $fileTypes);
			$displayTypes = '';		// this is what the user sees
			$filterTypes = '';		// this is what controls the logic
			$firstType = true;
			foreach($types as $type) {
				if(!$firstType) {
					$displayTypes .= ', ';
					$filterTypes .= '; ';
				} else {
					$firstType = false;
				}
				$displayTypes .= '*.'.$type;
				$filterTypes .= '*.'.$type;
			}
			$typeString = '{ \''.JText::_('COM_PLAYJOOM_FILES', 'true').' ('.$displayTypes.')\': \''.$filterTypes.'\' }';

			PlayJoomMediaHelper::AddUploaderScripts('upload-flash',
				array(
					'onBeforeStart' => 'function(){ Uploader.setOptions({url: document.id(\'uploadForm\').action + \'&folder=\' + document.id(\'mediamanager-form\').folder.value}); }',
					'onComplete' 	=> 'function(){ MediaManager.refreshFrame(); }',
					'targetURL' 	=> '\\document.id(\'uploadForm\').action',
					'typeFilter' 	=> $typeString,
					'fileSizeMax'	=> (int) ($config->get('upload_maxsize', 100) * 1024 * 1024),
				     )
			    );
		}

		if (DIRECTORY_SEPARATOR == '\\')
		{
			$base = str_replace(DIRECTORY_SEPARATOR, "\\\\", PLAYJOOM_BASE_PATH);
		} else {
			$base = PLAYJOOM_BASE_PATH;
		}
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
			$base = null;
		}
		
		$js = "
			var basepath = '".$base."';
			var viewstyle = '".$style."';
		" ;

		$document->addScriptDeclaration($js);

		/*
		 * Display form for FTP credentials?
		 * Don't set them here, as there are other functions called before this one if there is any file write operation
		 */
		$ftp = !JClientHelper::hasCredentials('ftp');

		$session	= JFactory::getSession();
		$state		= $this->get('state');

		$this->assignRef('session', $session);
		$this->assignRef('config', $config);
		$this->assignRef('state', $state);
		$this->assign('require_ftp', $ftp);

		//Add Toolbar and access check
		if ($canDo->get('core.edit')
				|| $canDo->get('core.create') && !JRequest::getVar('id')
				|| JAccess::check($user->get('id'), 'core.admin') == 1) {

			// Set the toolbar
			$this->addToolBar();

			// Display the template
			parent::display($tpl);
			echo JHtml::_('behavior.keepalive');
		}
		else {
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {

		JFactory::getApplication()->input->set('hidemainmenu', true);

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		$user = JFactory::getUser();

		// Set the titlebar text
		JToolbarHelper::title(JText::_('COM_PLAYJOOM_TOOLBAR_ADD_NEW_TRACKS'), 'mediamanager.png');

		// Add a create folder add tracks
		if ($user->authorise('core.create', 'com_playjoom')) {

			$app	= JFactory::getApplication();
			$style = $app->getUserStateFromRequest('media.list.layout', 'layout', 'details', 'word');

			//Add Thumbnail button
			$title = JText::_('COM_PLAYJOOM_THUMBNAIL_VIEW');
			$button_status_thumbnail = ($style == "thumbs") ? 'active' : '';
			$dhtml = "<button id=\"thumbs\" onclick=\"MediaManager.setViewType('thumbs')\" class=\"btn ".$button_status_thumbnail." btn-small\">
			<i class=\"icon-grid-view-2\" title=\"$title\"></i>
			$title</button>";
			$bar->appendButton('Custom', $dhtml, 'Add_folder-tracks');

			//Add Details button
			$title = JText::_('COM_PLAYJOOM_DETAIL_VIEW');
			$button_status_details = ($style == "details") ? 'active' : '';
			$dhtml = "<button id=\"details\" onclick=\"MediaManager.setViewType('details')\" class=\"btn ".$button_status_details." btn-small\">
			<i class=\"icon-list-view\" title=\"$title\"></i>
			$title</button>";
			$bar->appendButton('Custom', $dhtml, 'Add_folder-tracks');

			JToolbarHelper::divider();

			//Add tracks button
			$title = JText::_('COM_PLAYJOOM_SELECT_BUTTON_ADD_LOCALFOLDER');
			$dhtml = "<button onclick=\"Joomla.submitbutton('savetracks.save', this.form);\" class=\"btn btn-small btn-success\">
			<i class=\"icon-music icon-white\" title=\"$title\"></i>
			$title</button>";
			$bar->appendButton('Custom', $dhtml, 'Add_folder-tracks');

			//Add Upload button
			$title = JText::_('JTOOLBAR_UPLOAD');
			$dhtml = "<button data-toggle=\"collapse\" data-target=\"#collapseUpload\" class=\"btn btn-small\">
			<i class=\"icon-upload\" title=\"$title\"></i>
			$title</button>";
			$bar->appendButton('Custom', $dhtml, 'Add_folder-tracks');

			//Add Create Folder button
			$title = JText::_('COM_PLAYJOOM_CREATE_FOLDER');
			$dhtml = "<button data-toggle=\"collapse\" data-target=\"#collapseFolder\" class=\"btn btn-small\">
			<i class=\"icon-folder-open\" title=\"$title\"></i>
			$title</button>";
			$bar->appendButton('Custom', $dhtml, 'Add_folder-tracks');

			JToolbarHelper::divider();
		}

		JToolBarHelper::cancel('audiotrack.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::help('JHELP_CONTENT_MEDIA_MANAGER');
	}

	function setFolder($index = 0)	{

		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}
}
