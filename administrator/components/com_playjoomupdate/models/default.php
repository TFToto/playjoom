<?php
/**
 * Contains a model methods for the PlayJomm update component .
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage helpers.playjoomupdate
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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Playjoom update overview Model
 *
 * @package     PlayJoom.Administrator
 * @subpackage  com_playjoomupdate
 * @since       0.9
 */
class PlayjoomUpdateModelDefault extends JModelLegacy {

	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct($config = array()) {

		//parent::__construct($subject, $config);

		//Get config for PlayJoomUpdate
		$this->UpdateConf = PlayjoomupdateHelper::getConfig('Update',JPATH_COMPONENT_ADMINISTRATOR.'/playjoomupdate.conf.php');

		parent::__construct($config);
	}
	/**
	 * Detects if the PlayJoom update site currently in use matches the one
	 * configured in this component. If they don't match, it changes it.
	 *
	 * @return	void
	 *
	 * @since	2.5.4
	 */
	public function applyUpdateSite()	{

		// Determine the intended update URL
		$params = JComponentHelper::getParams('com_playjoomupdate');
		switch ($params->get('updatesource', 'nochange'))
		{
			// "Beta version branch"
			case 'beta':
				$updateURL = $this->UpdateConf->get('PJ_Update_updateURL_beta');
				break;

			// "Stable version branch - Recommended"
			case 'stable':
				$updateURL = $this->UpdateConf->get('PJ_Update_updateURL_stable');
				break;

			// "Testing version usally release candidate version"
			case 'testing':
				$updateURL = $this->UpdateConf->get('PJ_Update_updateURL_testing');
				break;

			// "Custom"
			case 'custom':
				$updateURL = $params->get('customurl', '');
				break;

			// "Do not change"
			case 'nochange':
			default:
				return;
				break;
		}

		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('us') . '.*')
			->from($db->quoteName('#__update_sites_extensions') . ' AS ' . $db->quoteName('map'))
			->join(
				'INNER', $db->quoteName('#__update_sites') . ' AS ' . $db->quoteName('us')
				. ' ON (' . 'us.update_site_id = map.update_site_id)'
			)
			->where('map.extension_id = ' . $db->quote($this->UpdateConf->get('PJUpdate_extension_id')));
		$db->setQuery($query);
		$update_site = $db->loadObject();

		if ($update_site->location != $updateURL) {

			// Modify the database record
			$update_site->last_check_timestamp = 0;
			$update_site->location = $updateURL;
			$db->updateObject('#__update_sites', $update_site, 'update_site_id');

			// Remove cached updates
			$query->clear()
				->delete($db->quoteName('#__updates'))
				->where($db->quoteName('extension_id') . ' = ' . $db->quote($this->UpdateConf->get('PJUpdate_extension_id')));
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Makes sure that the PlayJoom update cache is up-to-date
	 *
	 * @param   boolean  $force  Force reload, ignoring the cache timeout
	 *
	 * @return	void
	 *
	 * @since	0.9
	 */
	public function refreshUpdates($force = false) {

		if ($force)	{
			$cache_timeout = 0;
		} else	{
			$update_params = JComponentHelper::getParams('com_installer');
			$cache_timeout = $update_params->get('cachetimeout', 6, 'int');
			$cache_timeout = 3600 * $cache_timeout;
		}

		$updater = JUpdater::getInstance();
		$results = $updater->findUpdates($this->UpdateConf->get('PJUpdate_extension_id'), $cache_timeout);
	}

	/**
	 * Returns an array with the PlayJoom update informations
	 *
	 * @return  array
	 *
	 * @since   0.9
	 */
	public function getUpdateInformation() {

		$dispatcher	= JDispatcher::getInstance();

		// Initialise the return array
		$ret = array(
			'installed'		=> PJVERSION,
			'latest'		=> null,
			'object'		=> null
		);

		// Fetch the update information from the database
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__updates'))
			->where($db->qn('extension_id') . ' = ' . $db->q($this->UpdateConf->get('PJUpdate_extension_id')));
		$db->setQuery($query);
		$updateObject = $db->loadObject();

		if (is_null($updateObject))
		{
			$ret['latest'] = PJVERSION;
			return $ret;
		}
		else
		{
			$ret['latest'] = $updateObject->version;
		}

		// Fetch the full udpate details from the update details URL
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Fetch the full udpate details from the update details URL: '.$updateObject->detailsurl, 'priority' => JLog::INFO, 'section' => 'admin')));


		$update = new PlayjoomupdateHelper;
		$update->loadFromXML($updateObject->detailsurl);

		// Pass the update object
		if($ret['latest'] == PJVERSION) {
			$ret['object'] = null;
		} else {
			$ret['object'] = $update;
		}

		return $ret;
	}

	/**
	 * Returns an array with the configured FTP options
	 *
	 * @return  array
	 *
	 * @since   2.5.4
	 */
	public function getFTPOptions() {

		$config = JFactory::getConfig();
		return array(
			'host'		=> $config->get('ftp_host'),
			'port'		=> $config->get('ftp_port'),
			'username'	=> $config->get('ftp_user'),
			'password'	=> $config->get('ftp_pass'),
			'directory'	=> $config->get('ftp_root'),
			'enabled'	=> $config->get('ftp_enable'),
		);
	}

	/**
	 * Removes all of the updates from the table and enable all update streams.
	 *
	 * @return  boolean  Result of operation
	 *
	 * @since   3.0
	 */
	public function purge() {

		$db = JFactory::getDBO();

		// Modify the database record
		$update_site = new stdClass;
		$update_site->last_check_timestamp = 0;
		$update_site->enabled = 1;
		$update_site->update_site_id = 6;
		$db->updateObject('#__update_sites', $update_site, 'update_site_id');

		$query = $db->getQuery(true)
			->delete($db->qn('#__updates'))
			->where($db->qn('update_site_id') . ' = ' . $db->q('6'));
		$db->setQuery($query);

		if ($db->execute())
		{
			$this->_message = JText::_('JLIB_INSTALLER_PURGED_UPDATES');
			return true;
		}
		else
		{
			$this->_message = JText::_('JLIB_INSTALLER_FAILED_TO_PURGE_UPDATES');
			return false;
		}
	}

	/**
	 * Downloads the update package to the site
	 *
	 * @return  bool|string False on failure, basename of the file in any other case
	 *
	 * @since   2.5.4
	 */
	public function download() {

		$updateInfo = $this->getUpdateInformation();
		$packageURL = $updateInfo['object']->downloadurl->_data;
		$basename = basename($packageURL);

		// Find the path to the temp directory and the local package
		$config = JFactory::getConfig();
		$tempdir = $config->get('tmp_path');
		$target = $tempdir . '/' . $basename;

		// Do we have a cached file?
		if (!JFile::exists($target)){
			// Not there, let's fetch it
			return $this->downloadPackage($packageURL, $target);
		} else {
			// Is it a 0-byte file? If so, re-download please.
			$filesize = @filesize($target);
			if(empty($filesize)) return $this->downloadPackage($packageURL, $target);

			// Yes, it's there, skip downloading
			return $basename;
		}
	}
	/**
	 * Downloads a package file to a specific directory
	 *
	 * @param   string  $url     The URL to download from
	 * @param   string  $target  The directory to store the file
	 *
	 * @return  boolean True on success
	 *
	 * @since   2.5.4
	 */
	protected function downloadPackage($url, $target) {

		JLoader::import('helpers.download', JPATH_COMPONENT_ADMINISTRATOR);
		$result = AdmintoolsHelperDownload::download($url, $target);

		if(!$result) {
			return false;
		} else {
			return basename($target);
		}
	}
	/**
	 * Removes the extracted package file
	 *
	 * @return  void
	 *
	 * @since   0.9
	 */
	public function cleanUp()	{

		// Remove the update package
		$config = JFactory::getConfig();
		$tempdir = $config->get('tmp_path');

		$file = JFactory::getApplication()->getUserState('com_playjoomupdate.file', null);
		$archive = JFactory::getApplication()->getUserState('com_playjoomupdate.unpackage.name', null);
		$target = $tempdir.'/'.$file;
		$archive_target = $tempdir.'/'.$archive;

		if (!@unlink($target)) {
			JFile::delete($target);
		}

		if (!@unlink($archive)) {
			JFolder::delete($archive);
		}

		// Unset the update filename from the session
		JFactory::getApplication()->setUserState('com_playjoomupdate.file', null);
	}
	/**
	 * Method for to get an array with all folder path which should be installed.
	 *
	 * @return	array with all folders to install
	 *
	 * @since	0.10.1
	 */
	public function getFolderArray() {

		$temp_unpack_path = JFactory::getApplication()->getUserState('com_playjoomupdate.unpackage.name', null);

		return array_merge(
				self::getComponentFolders($temp_unpack_path),
				self::getFileFolders($temp_unpack_path),
				self::getLanguageFolders($temp_unpack_path),
				self::getLibraryFolders($temp_unpack_path),
				self::getModuleFolders($temp_unpack_path),
				self::getPackageFolders($temp_unpack_path),
				self::getPluginFolders($temp_unpack_path),
				self::getTemplateFolders($temp_unpack_path)
			);
	}
	/**
	 * Method for to get an array with all file which should be copied.
	 *
	 * @return	array All files which have to copy
	 *
	 * @since	0.10.1
	 */
	public function getFilesArray() {

		$temp_unpack_path = JFactory::getApplication()->getUserState('com_playjoomupdate.unpackage.name', null);
		return JFolder::files($temp_unpack_path,null,true,true,array('.svn', 'CVS', '.DS_Store', '__MACOSX','extensions'));
	}
	/**
	 * Method for to unpacking the update package
	 *
	 * @return bool
	 */
	public function unpackUpdatePackage() {

		$config	= JFactory::getConfig();
		$tmp_dest = $config->get('tmp_path') . DIRECTORY_SEPARATOR;

		$updateInfo = self::getUpdateInformation();
		$packageURL = $updateInfo['object']->downloadurl->_data;

		// Unpack the downloaded package file
		$installer_archive = JInstallerHelper::unpack($tmp_dest.basename($packageURL),true);

		if (!$installer_archive) {
			JLog::add('Unpack update package not possible. ', JLog::ERROR, 'Update');
			return false;
		} else {
			//save archive name in user session
			JFactory::getApplication()->setUserState('com_playjoomupdate.unpackage.name', $tmp_dest.basename($installer_archive['extractdir']));

			return true;
		}
	}
	/**
	 * Method to create a array with component paths
	 *
	 * @param string $path
	 * @return array with all component paths
	 */
	public function getComponentFolders ($path) {

		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'components')) {
			return JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'components',null,null,true);
		} else {
			return array();
		}
	}
	/**
	 * Method to create a array with template paths
	 *
	 * @param string $path
	 * @return array with all template paths
	 */
	public function getTemplateFolders ($path) {

		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'templates')) {
			return JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'templates','tpl_',null,true);
		} else {
			return array();
		}
	}
	/**
	 * Method to create a array with file paths
	 *
	 * @param string $path
	 * @return array with all template paths
	 */
	public function getFileFolders ($path) {
		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'file')) {
			return JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'file','f_',null,true);
		} else {
			return array();
		}
	}
	/**
	 * Method to create a array with language paths
	 *
	 * @param string $path
	 * @return array with all template paths
	 */
	public function getLanguageFolders ($path) {
		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'language')) {
			return JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'language','lang_',null,true);
		} else {
			return array();
		}
	}
	/**
	 * Method to create a array with libaray paths
	 *
	 * @param string $path
	 * @return array with all template paths
	 */
	public function getLibraryFolders ($path) {
		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'libraries')) {
			return JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'libraries','lib_',null,true);
		} else {
			return array();
		}
	}
	/**
	 * Method to create a array with module paths
	 *
	 * @param string $path
	 * @return array with all module paths
	 */
	public function getModuleFolders ($path) {

		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'modules')) {
			return JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'modules',null,null,true);
		} else {
			return array();
		}
	}
	/**
	 * Method to create a array with package paths
	 *
	 * @param string $path
	 * @return array with all template paths
	 */
	public function getPackageFolders ($path) {
		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'package')) {
			return JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'package','pkg_',null,true);
		} else {
			return array();
		}
	}
	/**
	 * Method to create a array with plugin paths
	 *
	 * @param string $path
	 * @return array with all plugin paths
	 */
	public function getPluginFolders ($path, $all_plugin_paths=array()) {

		if (JFolder::exists($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'plugins')) {

			$plugin_folder = JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'plugins',null,null,false);

			foreach ($plugin_folder as $plugin_install) {
				$plugin_items = JFolder::folders($path.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_install,null,null,true);
				$all_plugin_paths = array_merge($plugin_items,$all_plugin_paths);
			}
			return $all_plugin_paths;
		} else {
			return array();
		}
	}
	/**
	 * Method to a current protection status for extension
	 *
	 * @param intger $value
	 * @param array $manifest
	 *
	 * @return boolean
	 */
	public function setExtensionProtected($extension_id, $value) {

		$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
		$options['text_file'] = 'playjoom_update.php';
		JLog::addLogger($options, JLog::INFO, array('Update', 'databasequery', 'jerror'));

		$db = JFactory::getDbo();

		$obj = new stdClass();
		$obj->extension_id = $extension_id;
		$obj->protected = $value;

		$db->updateObject('#__extensions', $obj, 'extension_id', true);
		JLog::add('Set extension id '.$extension_id.' to protection status '.$value, JLog::INFO, 'Update');
	}
	/**
	 * Method to a current protection status for extension
	 *
	 * @param string $value
	 *
	 * @return boolean
	 */
	public function checkExtensionProtected($manifest) {

		$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
		$options['text_file'] = 'playjoom_update.php';
		JLog::addLogger($options, JLog::INFO, array('Update', 'databasequery', 'jerror'));

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('extension_id, protected');
		$query->from('#__extensions');
		$query->where($db->quoteName('type') . ' = ' . $db->quote($manifest->attributes()->type));
		$query->where($db->quoteName('name') . ' = ' . $db->quote($manifest->name));
		$query->where('protected = 1');

		$db->setQuery($query);
		$result = $db->loadObject();

		if (isset($result->protected) && $result->protected == 1) {
			JLog::add('The extension '.$manifest->name.' with id '.$result->extension_id.' is protected.', JLog::INFO, 'Update');
			return $result->extension_id;
		} else {
			return false;
		}
	}
	/**
	 * Method to a current protection status for extension
	 *
	 * @param string $value
	 *
	 * @return boolean
	 */
	public function checkNewExtensionID($manifest) {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('extension_id');
		$query->from('#__extensions');
		$query->where($db->quoteName('type') . ' = ' . $db->quote($manifest->attributes()->type));
		$query->where($db->quoteName('name') . ' = ' . $db->quote($manifest->name));

		$db->setQuery($query);
		$result = $db->loadObject();

		if (isset($result->extension_id) && $result->extension_id != 0) {
			return $result->extension_id;
		} else {
			return false;
		}
	}

}
