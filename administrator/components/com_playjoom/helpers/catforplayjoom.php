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
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Categories Component Category Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @since		1.6
 */
class CategoriesModelPlayJoom extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_CATEGORIES';


	public function getTable($type = 'Category', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
    public function getForm($data = array(), $loadData = true)
	{
				
		// Initialise variables.
		$extension	= $this->getState('category.extension');

		// A workaround to get the extension into the model for save requests.
		if (empty($extension) && isset($data['extension'])) {
			$extension	= $data['extension'];
			$parts		= explode('.',$extension);

			$this->setState('category.extension', $extension);
			$this->setState('category.component', $parts[0]);
			$this->setState('category.section', $parts[1]);
		}
		return $form;
	}
	
	
	/**
	 * Method to get a category.
	 *
	 * @param	integer	An optional id of the object to get, otherwise the id from the model state is used.
	 * @return	mixed	Category data object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($result = parent::getItem($pk)) {

			// Prime required properties.
			if (empty($result->id)) {
				$result->parent_id	= $this->getState('category.parent_id');
				$result->extension	= $this->getState('category.extension');
			}

			// Convert the metadata field to an array.
			$registry = new JRegistry();
			$registry->loadJSON($result->metadata);
			$result->metadata = $registry->toArray();

			// Convert the created and modified dates to local user time for display in the form.
			jimport('joomla.utilities.date');
			$tz	= new DateTimeZone(JFactory::getApplication()->getCfg('offset'));

			if (intval($result->created_time)) {
				$date = new JDate($result->created_time);
				$date->setTimezone($tz);
				$result->created_time = $date->toMySQL(true);
			}
			else {
				$result->created_time = null;
			}

			if (intval($result->modified_time)) {
				$date = new JDate($result->modified_time);
				$date->setTimezone($tz);
				$result->modified_time = $date->toMySQL(true);
			}
			else {
				$result->modified_time = null;
			}
		}

		return $result;
	}
	

	/**
	 * Method to save the data for a new Playjoom category.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data, $value, $mediatype, $parent_id)
	{
		//Build data array for Playjoom category / Genre
		$data = array(
                 "parent_id" => $parent_id,
                 "level" => 2,
		         "lft" => null,
		         "rgt" => null,
		         "alias" => null,
		         "id" => null,
		         "asset_id" => null,
		         "path" => $mediatype."/".$value,
		         "extension" => "com_playjoom",
		         "title" => $value,
		         "note" => null,
		         "description" => null,
		         "published" => 1,
		         "checked_out" => null,
		         "checked_out_time" => null,
		         "access" => 1,
		         "metadesc" => null,
		         "metakey" => null,
		         "created_user_id" => null,
		         "created_time" => null,
		         "modified_user_id" => null,
		         "modified_time" => null,
		         "language" => "*"        
                     );		
                     
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		//$pk			= (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;
        $pk = $data['id'];
		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Load the row if saving an existing category.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
			$table->setLocation($data['parent_id'], 'last-child');
		}

		// Alter the title for save as copy
		if (!$isNew && $data['id'] == 0 && $table->parent_id == $data['parent_id']) {
			$m = null;
			$data['alias'] = '';
			if (preg_match('#\((\d+)\)$#', $table->title, $m)) {
				$data['title'] = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $table->title);
			}
			else {
				$data['title'] .= ' (2)';
			}
		}

		// Bind the data.
		if (!$table->bind($data)) {
			print_r ($data);
			$this->setError($table->getError());
			return false;
		}

		// Bind the rules.
		if (isset($data['rules'])) {
			$rules = new JRules($data['rules']);
			$table->setRules($rules);
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the tree path.
		if (!$table->rebuildPath($table->id)) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}
	
}
