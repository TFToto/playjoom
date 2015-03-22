<?php
/**
 * Contains the controller methods for PlayJoom Update.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage controllers.playjoomupdate
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
 * The PlayJoom update controller for the Update view
 *
 * @package     PlayJoom.Administrator
 * @subpackage  com_playjoomupdate
 * @since       0.9
 */
class PlayjoomupdateControllerUpdate extends JControllerLegacy
{
	/**
	 * Performs the download of the update package
	 *
	 * @return  void
	 *
	 * @since   2.5.4
	 */
	public function download() {
		$this->_applyCredentials();

		$model = $this->getModel('Default');
		$file = $model->download();

		$message = null;
		$messageType = null;

		if ($file) {
			JFactory::getApplication()->setUserState('com_playjoomupdate.file', $file);
			if ($model->unpackUpdatePackage()) {
				$url = 'index.php?option=com_playjoomupdate&task=update.copyfiles';
			} else {
				$url = 'index.php?option=com_playjoomupdate';
				$message = JText::_('COM_PLAYJOOMUPDATE_VIEW_UPDATE_DOWNLOADFAILED');
			}
		} else {
			JFactory::getApplication()->setUserState('com_playjoomupdate.file', null);
			$url = 'index.php?option=com_playjoomupdate';
			$message = JText::_('COM_PLAYJOOMUPDATE_VIEW_UPDATE_DOWNLOADFAILED');
		}

		$this->setRedirect($url, $message, $messageType);
	}
	/**
	 * Start the installation of the new PlayJoom version
	 *
	 * @return  void
	 *
	 * @since   0.10.1
	 */
	public function copyfiles() {

		$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
		$options['text_file'] = 'playjoom_update.php';
		JLog::addLogger($options, JLog::INFO, array('Update', 'databasequery', 'jerror'));

		// We don't want this form to be cached.
		header('Pragma: no-cache');
		header('Cache-Control: no-cache');
		header('Expires: -1');

		$model = $this->getModel('Default');
		$files_array = $model->getFilesArray();

		$cache_array_step1 = implode('*|*', $files_array);
		JFactory::getApplication()->setUserState('com_playjoomupdate.filespaths.array', $cache_array_step1);

		$this->display_step1();
	}
	/**
	 * Execute the extensions setup
	 *
	 * @return  void
	 *
	 * @since   0.10.1
	 */
	public function extensionssetup() {

		$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
		$options['text_file'] = 'playjoom_update.php';
		JLog::addLogger($options, JLog::INFO, array('Update', 'databasequery', 'jerror'));

		// We don't want this form to be cached.
		header('Pragma: no-cache');
		header('Cache-Control: no-cache');
		header('Expires: -1');

		//Get file array
		$model = $this->getModel('default');
		$folder_array = $model->getFolderArray();

		$cache_array = implode('*|*', $folder_array);
		JFactory::getApplication()->setUserState('com_playjoomupdate.paths.array', $cache_array);

		$this->display_step2();
	}
	/**
	 * Clean up after ourselves
	 *
	 * @return  void
	 *
	 * @since   0.9
	 */
	public function cleanup() {
		$this->_applyCredentials();

		$model = $this->getModel('Default');

		$model->cleanUp();

		$url = 'index.php?option=com_playjoomupdate&layout=complete';
		$this->setRedirect($url);
	}
	/**
	 * Purges updates.
	 *
	 * @return  void
	 *
	 * @since	0.9
	 */
	public function purge() {

		// Purge updates
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('Default');
		$model->purge();

		$url = 'index.php?option=com_playjoomupdate';
		$this->setRedirect($url, $model->_message);
	}
	/**
	 * Method to display a view.
	 *
	 * @param	boolean  $cachable   If true, the view output will be cached
	 * @param	array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	PlayjoomupdateControllerUpdate  This object to support chaining.
	 *
	 * @since	2.5.4
	 */
	public function display_step1($cachable = false, $urlparams = array()) {

		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->get('view', 'copyfiles');
		$vFormat = $document->getType();
		$lName   = $this->input->get('layout', 'default');

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
			// Get the model for the view.
			$model = $this->getModel('Default');

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->document = $document;
			$view->display();
		}

		return $this;
	}
	/**
	* Method to display a view.
	*
	* @param	boolean  $cachable   If true, the view output will be cached
	* @param	array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	*
	* @return	PlayjoomupdateControllerUpdate  This object to support chaining.
	*
	* @since	2.5.4
	*/
	public function display_step2($cachable = false, $urlparams = array()) {

		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->get('view', 'installextensions');
		$vFormat = $document->getType();
		$lName   = $this->input->get('layout', 'default');

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
			// Get the model for the view.
			$model = $this->getModel('Default');

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->document = $document;
			$view->display();
		}

		return $this;
	}
	/**
	 * Applies FTP credentials to PlayJoom itself, when required
	 *
	 * @return  void
	 *
	 * @since	0.9
	 */
	protected function _applyCredentials()
	{
		if (!JClientHelper::hasCredentials('ftp'))
		{
			$user = JFactory::getApplication()->getUserStateFromRequest('com_playjoomupdate.ftp_user', 'ftp_user', null, 'raw');
			$pass = JFactory::getApplication()->getUserStateFromRequest('com_playjoomupdate.ftp_pass', 'ftp_pass', null, 'raw');

			if ($user != '' && $pass != '')
			{
				// Add credentials to the session
				if (JClientHelper::setCredentials('ftp', $user, $pass))
				{
					$return = false;
				}
				else
				{
					$return = JError::raiseWarning('SOME_ERROR_CODE', JText::_('JLIB_CLIENT_ERROR_HELPER_SETCREDENTIALSFROMREQUEST_FAILED'));
				}
			}
		}
	}
}