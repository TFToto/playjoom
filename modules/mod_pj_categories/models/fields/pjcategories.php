<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_categories
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');
jimport( 'joomla.application.module.helper' );

/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @since  11.1
 */
class JFormFieldPJCategories extends JFormFieldList {
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'PJCategories';

	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @return  array    The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions() {

		$options = array();
		if (JRequest::getVar('id')) {
			$extension = $this->getCurrentCategoryExtensions()->cat_extension;
		} else {
			$extension = (string) $this->element['scope'];
		}
		$published = (string) $this->element['published'];

		// Load the category options for a given extension.
		if (!empty($extension)) {
			// Filter over published state or not depending upon if it is present.
			if ($published)
			{
				$options = JHtml::_('category.options', $extension, array('filter.published' => explode(',', $published)));
			}
			else
			{
				$options = JHtml::_('category.options', $extension);
			}

			// Verify permissions.  If the action attribute is set, then we scan the options.
			if ((string) $this->element['action'])
			{
				// Get the current user object.
				$user = JFactory::getUser();

				foreach ($options as $i => $option)
				{
					/*
					 * To take save or create in a category you need to have create rights for that category
					 * unless the item is already in that category.
					 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
					 */
					if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
					{
						unset($options[$i]);
					}
				}
			}

			if (isset($this->element['show_root']))
			{
				array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
			}
		}
		else
		{
			JLog::add(JText::_('JLIB_FORM_ERROR_FIELDS_CATEGORY_ERROR_EXTENSION_EMPTY'), JLog::WARNING, 'jerror');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
	/**
	 * Method for to get the currents category items for selected extension
	 *  
	 * @return json Just params content of current module
	 */
	public static function getCurrentCategoryExtensions() {
	
		if (JRequest::getVar('id')) { 
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('m.params');
			$query->from('#__modules AS m');
			$query->where('m.id = '.(int)JRequest::getVar('id'));
	
			$db->setQuery($query);
			return json_decode($db->loadObject()->params);
		} else {
			return null;
		}
	}
}