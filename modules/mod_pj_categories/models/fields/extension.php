<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_menu_categories
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield'); 

class JFormFieldCategorie_Extension extends JFormField {

	protected $type = 'Categorie_Extension';

	public function __construct()
	{
		parent::__construct();

		//Get configuration
		$app    = JFactory::getApplication();
		$config = JFactory::getConfig();
	}

	public function getInput() {

		$menu = '<select id="'.$this->id.'" onchange="Joomla.submitbutton(\'module.apply\')" name="'.$this->name.'">';
			$menu .= '<option value="">'.JText::_('MOD_PJ_CATEGOIES_SELECT_EXTENSION').'</option>';
			$menu .= JHtml::_('select.options', self::getCategoryExtensions(), 'value', 'text', $this->value);
		$menu .= '</select>';

		return $menu;
	}
	
	public static function getCategoryExtensions() {
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('c.extension AS text, c.extension AS value');
		$query->from('#__categories AS c');
		$query->group('c.extension');
	
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}