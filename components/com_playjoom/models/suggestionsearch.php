<?php
/**
 * Contains the model methods for to get the content of a suggestionssearch in PlayJoom frontend.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Site
 * @subpackage models.suggestionsearch
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * PlayJoom Model
 */
class PlayJoomModelSuggestionsearch extends JModelList {

	/**
	 * Method for to get a list of artist items
	 *
	 * @return array database items of artist names
	 */
	public function getArtistResults() {

		//Get User objects
		$user	= JFactory::getUser();

		//For getting the xml parameters
		$app = JFactory::getApplication();
		$params		= $app->getParams();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id, a.artist AS label, a.artist AS value, "'.JText::_('MOD_PJ_SEARCH_LABEL_ARTIST').'" AS category, "artists" AS area');
		$query->from('#__jpaudiotracks AS a');

		$search = JRequest::getVar('term');
		$search = $db->Quote('%'.$db->escape($search, true).'%', false);
		$query->where('(a.artist LIKE '.$search.')');
		$query->group('a.artist');

		// Implement View Level Access
		if (!$user->authorise('core.admin')
			 && !$params->get('show_noauth', 1)) {

			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$groups = '0,'.$groups;
			$query->where('a.access IN ('.$groups.')');
		}

		//Filtering by user
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			//Get user id
			$users = $user->get('id');

			$userCheck = $params->get('show_all_users', 1);
			$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

			if ($userCheck == 1) {

				if ($params->get('show_all_users', 1)) {
					$query->where('a.add_by >= 1');
				}

				if ($params->get('show_nobody', 1)) {
					$users = '0,'.$users;
					$query->where('a.add_by IN ('.$users.')');
				}
			} elseif ($userCheck == 0) {
				$query->where('a.add_by = '.$users.'');
			}
		}

		// Get the options.
		$db->setQuery($query);

		$results = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $results;
	}

	/**
	 * Method for to get a list of album items
	 *
	 * @return array database items of album names
	 */
	public function getAlbumResults() {

		//Get User objects
		$user	= JFactory::getUser();

		//For getting the xml parameters
		$app = JFactory::getApplication();
		$params		= $app->getParams();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id, a.album AS label, a.album AS value, "'.JText::_('MOD_PJ_SEARCH_LABEL_ALBUMS').'" AS category, "albums" AS area');
		$query->from('#__jpaudiotracks AS a');

		$search = JRequest::getVar('term');
		$search = $db->Quote('%'.$db->escape($search, true).'%', false);
		$query->where('(a.album LIKE '.$search.')');
		$query->group('a.album');

		// Implement View Level Access
		if (!$user->authorise('core.admin')
				&& !$params->get('show_noauth', 1)) {

			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$groups = '0,'.$groups;
			$query->where('a.access IN ('.$groups.')');
		}

		//Filtering by user
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			//Get user id
			$users = $user->get('id');

			$userCheck = $params->get('show_all_users', 1);
			$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

			if ($userCheck == 1) {

				if ($params->get('show_all_users', 1)) {
					$query->where('a.add_by >= 1');
				}

				if ($params->get('show_nobody', 1)) {
					$users = '0,'.$users;
					$query->where('a.add_by IN ('.$users.')');
				}
			} elseif ($userCheck == 0) {
				$query->where('a.add_by = '.$users.'');
			}
		}

		// Get the options.
		$db->setQuery($query);

		$results = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $results;
	}

	/**
	 * Method for to get a list of track items
	 *
	 * @return array database items of tack names
	 */
	public function getTrackResults() {

		//Get User objects
		$user	= JFactory::getUser();

		//For getting the xml parameters
		$app = JFactory::getApplication();
		$params		= $app->getParams();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id, a.title AS label, a.title AS value, "'.JText::_('MOD_PJ_SEARCH_LABEL_TITLE').'" AS category, "tracks" AS area');
		$query->from('#__jpaudiotracks AS a');

		$search = JRequest::getVar('term');
		$search = $db->Quote('%'.$db->escape($search, true).'%', false);
		$query->where('(a.title LIKE '.$search.')');
		$query->group('a.title');

		// Implement View Level Access
		if (!$user->authorise('core.admin')
				&& !$params->get('show_noauth', 1)) {

			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$groups = '0,'.$groups;
			$query->where('a.access IN ('.$groups.')');
		}

		//Filtering by user
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			//Get user id
			$users = $user->get('id');

			$userCheck = $params->get('show_all_users', 1);
			$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

			if ($userCheck == 1) {

				if ($params->get('show_all_users', 1)) {
					$query->where('a.add_by >= 1');
				}

				if ($params->get('show_nobody', 1)) {
					$users = '0,'.$users;
					$query->where('a.add_by IN ('.$users.')');
				}
			} elseif ($userCheck == 0) {
				$query->where('a.add_by = '.$users.'');
			}
		}

		// Get the options.
		$db->setQuery($query);

		$results = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $results;
	}

}