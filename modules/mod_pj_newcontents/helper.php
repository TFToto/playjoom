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
 * @PlayJoom Module
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_playjoom/helpers/route.php';

jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_playjoom/models');

abstract class modnewContentsHelper
{
	public static function getList(&$params)
	{
		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic tracks model
		$model = JModelLegacy::getInstance('Sections', 'PlayjoomModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));

		// Access filter
		$access = !JComponentHelper::getParams('com_playjoom')->get('show_noauth', 1);
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		//$model->setState('filter.access', $access);

		// Category filter
		//$model->setState('filter.category_id', $params->get('catid', array()));

		// User filter
		$userId = JFactory::getUser()->get('id');
		switch ($params->get('user_id'))
		{
			case 'by_me':
				//$model->setState('filter.author_id', (int) $userId);
				break;
			case 'not_me':
				//$model->setState('filter.author_id', $userId);
				//$model->setState('filter.author_id.include', false);
				break;

			case '0':
				break;

			default:
				//$model->setState('filter.author_id', (int) $params->get('user_id'));
				break;
		}

		// Filter by language
		//$model->setState('filter.language',$app->getLanguageFilter());

		//  Featured switch
		switch ($params->get('show_featured'))
		{
			case '1':
				$model->setState('filter.featured', 'only');
				break;
			case '0':
				$model->setState('filter.featured', 'hide');
				break;
			default:
				$model->setState('filter.featured', 'show');
				break;
		}
		// Set ordering
		$order_map = array(
			'm_dsc' => 'a.mod_datetime DESC, a.add_datetime',
			'mc_dsc' => 'CASE WHEN (a.mod_datetime = '.$db->quote($db->getNullDate()).') THEN a.add_datetime ELSE a.mod_datetime END',
			'c_dsc' => 'a.add_datetime',
		);
		                //a.add_datetime
		//$ordering = JArrayHelper::getValue($order_map,null);
		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering'));
		$dir = 'DESC';

		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);

		$items = $model->getItems();

		//create item link
		foreach ($items as &$item) 
		{
			//Check for Trackcontrol
		    if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false)
	        {
	        	$item->link = null; 
	        }
	        else 
	        {
	        	$item->link = JRoute::_('index.php?option=com_playjoom&view=broadcast&id='.$item->id);
	        }
			
		}
		

		return $items;
	}
}
