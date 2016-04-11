<?php
/**
 * Contains the Update helper methods for PlayJoom Update Component.
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

/**
 * PlayJoom update helper.
 *
 * @package     PlayJoom.Administrator
 * @subpackage  com_playjoomupdate
 * @since       0.9
 */
class PlayjoomadminHelper extends JObject {

	public static $PJConfig = null;

	public static function getConfig($namespace = null, $file = null) {

		if ($file === null || $namespace == null) {

			//Set standard config file
			$file = null;
		}
		self::$PJConfig = self::_createPJUpdateConfig($file, $namespace);

		return self::$PJConfig;
	}

	protected static function _createPJUpdateConfig($file, $namespace) {

		jimport('joomla.registry.registry');

		if (is_file($file)) {
			include_once $file;
		}

		// Create the registry with a default namespace of config
		$registry = new JRegistry();

		// Build the config name.
		$name = 'PJConfig'.$namespace;

		// Handle the PHP configuration type.
		if (class_exists($name)) {
			// Create the JConfig object
			$config = new $name();

			// Load the configuration values into the registry
			$registry->loadObject($config);
		}

		return $registry;
	}
}
