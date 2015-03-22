<?php
/**
 * @package Joomla 1.6.x
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2014 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * PlayJoom Table class for audiotrack
 */
class PlayJoomTableAudioTrack extends JTable {
	/**
	* Constructor
	*
	* @param object Database connector object
	*/
	function __construct(&$db) {

		parent::__construct('#__jpaudiotracks', 'id', $db);
	}
	/**
	* Overloaded bind function
	*
	* @param       array           named array
	* @return      null|string     null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	public function bind($array, $ignore = '') {

		if (isset($array['params']) && is_array($array['params'])) {
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}

		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$rules = new JRules($array['rules']);
			$this->setRules($rules);
		}

		return parent::bind($array, $ignore);
	}

	/**
	* Overloaded load function
	*
	* @param       int $pk primary key
	* @param       boolean $reset reset data
	* @return      boolean
	* @see JTable:load
	*/
	public function load($pk = null, $reset = true) {
		if (parent::load($pk, $reset)) {

			// Convert the params field to a registry.
			$params = new JRegistry;
			$params->loadString($this->params);
			//$params->loadJSON($this->params);
			$this->params = $params;
			return true;
		} else {
			return false;
		}
	}
	/**
	* Method to compute the default name of the asset.
	* The default name is in the form `table_name.id`
	* where id is the value of the primary key of the table.
	*
	* @return      string
 	* @since       1.6
	*/
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		return 'com_playjoom.audiotrack.'.(int) $this->$k;
	}

	/**
	* Method to return the title to use for the asset table.
	*
	* @return      string
	* @since       1.6
	*/
	protected function _getAssetTitle() {
		return $this->name;
	}
	/**
	* Get the parent asset id for the record
	*
	* @return      int
	* @since       1.6
	*/
	protected function _getAssetParentId(JTable $table = null, $id = null) {

		// Initialise variables.
		$assetId = null;

		// This is a project under a department.
		if ($this->parent_id_key) {

			// Build the query to get the asset id for the parent category.
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName('asset_id'));
			$query->from($this->_db->quoteName('#__parent_item_table'));
			$query->where($this->_db->quoteName('id') . ' = '
					. (int) $this->parent_id_key);

			// Get the asset id from the database.
			$this->_db->setQuery($query);
			if ($result = $this->_db->loadResult())
				$assetId = (int) $result;
		}

		// Return the asset id.
		if ($assetId) {
			return $assetId;
		}
		return parent::_getAssetParentId($table, $id);
	}
}