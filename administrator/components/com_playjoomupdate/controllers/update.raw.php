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

jimport('joomla.filesystem.file');
jimport('joomla.installer.install');

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
	 * Class Constructor
	 *
	 * @param	array	$config		An optional associative array of configuration settings.
	 * @return	void
	 * @since	1.5
	 */
	function __construct($config = array()) {

		parent::__construct($config);

		// Map the apply task to the save method.
		$this->registerTask('update', 'executesetup');
		$this->registerTask('update', 'runningcopy');
	}
	/**
	 * Performs the copy procedure of each file without of the install pachages
	 *
	 * @return  void
	 *
	 * @since   0.10.1
	 */
	public function runningcopy() {

		$temp_unpack_path = JFactory::getApplication()->getUserState('com_playjoomupdate.unpackage.name', null);

		$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
		$options['text_file'] = 'playjoom_update.php';
		JLog::addLogger($options, JLog::INFO, array('Update', 'databasequery', 'jerror'));

		// Send the appropriate error code response.
		JResponse::clearHeaders();
		JResponse::setHeader('Content-Type', 'application/json; charset=utf-8');
		JResponse::sendHeaders();

		$total = $_GET['total'];
		$one_procent = 1/($total/100);
		$curr_index = number_format($_GET['total']*$_GET['status']/100,0,'','');

		$filespath_from_cache = explode('*|*', JFactory::getApplication()->getUserState('com_playjoomupdate.filespaths.array'));

		$php_array['status'] = $_GET['status']+$one_procent;

		if (isset($filespath_from_cache[$curr_index])) {

			//Copy file
			$src = $filespath_from_cache[$curr_index];
			$dest = str_replace($temp_unpack_path,JPATH_ROOT,$filespath_from_cache[$curr_index]);

			JFile::copy($src, $dest);
		}

		// Bei 100% ist Schluss ;)
		if($php_array['status']>100) {
			$php_array['status'] = 100;
		}

		if($php_array['status'] != 100
		&& isset($filespath_from_cache[$curr_index])) {
			$php_array['message'] = JText::_('COM_PLAYJOOMUPDATE_INSTALLING_EXTENSIONS_CURRENT_STATUS').' '.($curr_index + 1).' / '.$total.' - '.round($php_array['status'],1).'%';
			$php_array['message_path'] = JText::_('COM_PLAYJOOMUPDATE_COPY_FILE_TO_PATH_STATUS').' '.$dest;
		} else {
			$php_array['message'] = JText::_('COM_PLAYJOOMUPDATE_COPY_FILES_DONE');
		}

		// Output as PHP arrays as JSON Objekt
		echo json_encode($php_array);
	}
	/**
	 * Performs the setups of each package
	 *
	 * @return  void
	 *
	 * @since   0.10.1
	 */
	public function executesetup() {

		$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
		$options['text_file'] = 'playjoom_update.php';
		JLog::addLogger($options, JLog::INFO, array('Update', 'databasequery', 'jerror'));

		// Send the appropriate error code response.
		JResponse::clearHeaders();
		JResponse::setHeader('Content-Type', 'application/json; charset=utf-8');
		JResponse::sendHeaders();

		$total = $_GET['total'];
		$one_procent = 1/($total/100);
		$curr_index = number_format($_GET['total']*$_GET['status']/100,0,'','');

		$path_from_cache = explode('*|*', JFactory::getApplication()->getUserState('com_playjoomupdate.paths.array'));

		$php_array['status'] = $_GET['status']+$one_procent;

		if (isset($path_from_cache[$curr_index])) {
			//Install extensions
			$installer = JInstaller::getInstance();
			$installer->setPath('source', $path_from_cache[$curr_index]);
			$installer->setPath('extension_root', $path_from_cache[$curr_index]);

			//Check extension packages for install
			if (!$installer->setupInstall()) {
				JLog::add('Some problems with install package!', JLog::ERROR, 'Update');
				$package_status = false;
			} else {
				JLog::add('The Install package '.$path_from_cache[$curr_index].' is okay.', JLog::INFO, 'Update');
				$package_status = true;

				$manifest = $installer->getManifest();

				//Get file array
				$model = $this->getModel('default');
				$extension_id = $model->checkExtensionProtected($manifest);

				//Install extension package
				$install_extensions = new JInstaller();

				if ($extension_id != 0) {
					$model->setExtensionProtected($extension_id, 0);
					$install_extensions->install($path_from_cache[$curr_index]);
					JLog::add('Install package '.$manifest->name.' is done.', JLog::INFO, 'Update');
					$model->setExtensionProtected($model->checkNewExtensionID($manifest), 1);
				} else {
					$install_extensions->install($path_from_cache[$curr_index]);
				}
			}
		}

		// Bei 100% ist Schluss ;)
		if($php_array['status']>100) {
			$php_array['status'] = 100;
		}

		if($php_array['status'] != 100
		&& isset($path_from_cache[$curr_index])) {
			$php_array['message'] = JText::_('COM_PLAYJOOMUPDATE_INSTALLING_EXTENSIONS_CURRENT_STATUS').' '.($curr_index + 1).' / '.$total.' - '.round($php_array['status'],1).'%';
			if ($package_status) {
				$php_array['message_path'] = JText::_('COM_PLAYJOOMUPDATE_INSTALLING_EXTENSIONS_PATH_STATUS').' '.$path_from_cache[$curr_index].' Done.';
			} else {
				$php_array['message_path'] = JText::_('COM_PLAYJOOMUPDATE_INSTALLING_EXTENSIONS_PATH_STATUS').' '.$path_from_cache[$curr_index].' failed.';
			}
		} else {
			$php_array['message'] = JText::_('COM_PLAYJOOMUPDATE_INSTALLING_EXTENSIONS_DONE');
		}

		// Output as PHP arrays as JSON Objekt
		echo json_encode($php_array);
	}
}
