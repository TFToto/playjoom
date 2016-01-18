<?php
/**
 * Contains the plugin methods for to get the list of results for album sarching in PlayJoom frontend.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package	PlayJoom.Plugin
 * @subpackage Search.pj_albums
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * PJ_Artists Search plugin
 *
 * @package	PlayJoom.Plugin
 * @subpackage Search.pj_albums
 * @since		0.9.473
 */
class plgSearchPj_albums extends JPlugin
{

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @return array An array of search areas
	 */
	function onContentSearchAreas()
	{
		static $areas = array(
			'albums' => 'PLG_SEARCH_ALBUMS_ALBUMS'
			);
			return $areas;
	}

	/**
	 * Album Search method
	 *
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, add_datetime
	 *
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$tag = JFactory::getLanguage()->getTag();

		//For getting the xml parameters
		$params = JComponentHelper::getParams('com_playjoom');

		require_once JPATH_SITE.'/components/com_playjoom/helpers/route.php';
		require_once JPATH_SITE.'/components/com_playjoom/helpers/playjoom.php';
		require_once JPATH_SITE.'/components/com_playjoom/helpers/logging.php';

		$searchText = $text;
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		//Get plugin params
		$sContent		= null;
		$sArchived		= null;
		$limit			= $this->params->def('pj_albums_search_limit',		50);

		$nullDate		= $db->getNullDate();
		$date = JFactory::getDate();
		$now = $date->toSql();

		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$section	= JText::_('PLG_SEARCH_ALBUMS');

		$wheres = array();
		switch ($phrase) {
			case 'exact':
				$text		= $db->Quote($db->escape($text, true), false);
				$wheres2	= array();
				$wheres2[]	= 'a.album LIKE '.$text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote('%'.$db->escape($word, true).'%', false);
					$wheres2	= array();
					$wheres2[]	= 'a.album LIKE '.$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		$morder = '';
		switch ($ordering) {
			case 'oldest':
				$order = 'a.add_datetime ASC';
				break;

			case 'popular':
				$order = 'a.hits DESC';
				break;

			case 'alpha':
				$order = 'a.album ASC';
				break;

			case 'category':
				$order = 'c.title ASC, a.artist ASC';
				$morder = 'a.album ASC';
				break;

			case 'newest':
			default:
				$order = 'a.add_datetime DESC';
				break;
		}

		$rows = array();
		$query	= $db->getQuery(true);

		$query->clear();
		$query->select('a.id, a.catid, a.pathatlocal, a.file, a.title AS tracktitle, a.album AS album, a.artist AS artist, a.metadesc, a.metakey, a.add_datetime AS created,'
						.'CONCAT_WS(" / ", '.$db->Quote($section).', c.title) AS section, "1" AS browsernav');
		$query->from('#__jpaudiotracks AS a');
		$query->innerJoin('#__categories AS c ON c.id=a.catid');
		$query->where('('. $where .')');
		$query->group('a.album,a.artist');
		$query->order($order);

		//Filtering by user
		if (JAccess::check($user->get('id'), 'core.admin') != 1) {

			//Get user id
			$users = $user->get('id');

			$userCheck = $params->get('show_all_users', 1);
			$userCheck = (int)$userCheck + $params->get('show_nobody', 1);

			if ($userCheck == 1) {

				if ($params->get('show_all_users', 1)) {
					$query->where('add_by >= 1');
				}

				if ($params->get('show_nobody', 1)) {
					$users = '0,'.$users;
					$query->where('add_by IN ('.$users.')');
				}
			}
			elseif ($userCheck == 0) {
				$query->where('add_by = '.$users.'');
			}
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin')
				&& !$params->get('show_noauth', 1)) {

			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$groups = '0,'.$groups;
			$query->where('a.access IN ('.$groups.')');
		}

		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();

		$limit -= count($list);


		if (isset($list)) {

			JLoader::import( 'helpers.cover', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');
			$Cover = new PlayJoomHelperCover();

			foreach($list as $key => $item) {

				//Get Album thumbnail
				$SamplerCheck = PlayJoomHelper::checkForSampler($item->album,$item->artist);

				if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
					$cover = new PlayJoomHelperCover();
					$coverthumb = $cover->getCoverHTMLTag($item, $SamplerCheck);
				} else {
					$coverthumb = null;
				}

				$list[$key]->href = 'index.php?option=com_playjoom&view=album&album='.base64_encode($item->album).'&artist='.base64_encode($item->artist);
				$list[$key]->title = $item->album.' ('.$item->artist.')';
				$list[$key]->coverimg = $coverthumb;
				$list[$key]->browsernav = '2';
			}
		}
		$rows[] = $list;

		$results = array();
		if (count($rows))
		{
			foreach($rows as $row)
			{
				$new_row = array();
				foreach($row AS $key => $track) {

					if (count($row) == 1) {
						JFactory::getApplication()->redirect($track->href);
					} else {
						if (JFile::exists($track->pathatlocal.DIRECTORY_SEPARATOR.$track->file)) {
							$new_row[] = $track;
						}
					}
				}
				$results = array_merge($results, (array) $new_row);
			}
		}

		return $results;
	}
}
