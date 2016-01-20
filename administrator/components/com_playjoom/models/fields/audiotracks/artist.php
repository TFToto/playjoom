<?php
/**
 * Contains the constructor methods for to get the form fields for the artist filter menu in audiotracks PlayJoom backend.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage models.fields.audiotracks
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2014 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldAudiotracks_Artist extends JFormField {

	protected $type = 'Audiotracks_Artist';

	public function __construct()
	{
		parent::__construct();

		//Get configuration
		$app    = JFactory::getApplication();
		$config = JFactory::getConfig();
	}

	public function getInput() {

		$app = JFactory::getApplication();
		$curr_state = $app->getUserStateFromRequest('filter.artist', 'filter_artist');

		$model = JModelLegacy::getInstance('Audiotracks', 'PlayjoomModel', array('ignore_request' => true));

		$menu = '<select name="filter_artist" id="filter_artist" onchange="this.form.submit()">';
			$menu .= '<option value="">'.JText::_('COM_PLAYJOOM_FILTER_ARTIST').'</option>';
			$menu .= JHtml::_('select.options', $model->getFilterOptionsArtists(), 'value', 'text', $curr_state);
		$menu .= '</select>';

		return $menu;
	}
}