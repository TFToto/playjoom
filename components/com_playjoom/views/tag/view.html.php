<?php
/**
 * @package Joomla 3.0.x
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
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
 * @date $Date: 2013-10-26 10:26:45 +0200 (Sa, 26 Okt 2013) $
 * @revision $Revision: 856 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/plugins/playjoom/playlist/playlist.php $
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Tag part
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 * @since       3.1
 */
class PlayJoomViewTag extends JViewLegacy
{
	protected $state;

	protected $items;

	protected $item;

	protected $children;

	protected $pagination;

	protected $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @since   3.1
	 */
	public function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$params		= $app->getParams();

		// Get some data from the models
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$item		= $this->get('Item');
		$children	= $this->get('Children');
		$parent 	= $this->get('Parent');
		$pagination	= $this->get('Pagination');

		$tag_items_arr = array();

		// Change to catch
		/*if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}*/
		// Check whether access level allows access.
		// TODO: SHould already be computed in $item->params->get('access-view')
		$user	= JFactory::getUser();
		$groups	= $user->getAuthorisedViewLevels();
		foreach ($item as $itemElement) {

			if (!in_array($itemElement->access, $groups)) {
				unset($itemElement);
			}

			// Prepare the data.
			if (!empty($itemElement)) {

				$temp = new JRegistry;
				$temp->loadString($itemElement->params);
				$itemElement->params = clone($params);
				$itemElement->params->merge($temp);
				$itemElement->params = (array) json_decode($itemElement->params);
			}
		}

		if ($items !== false) {

			foreach ($items as $itemElement) {

				$itemElement->event = new stdClass;

				// For some plugins.
				!empty($itemElement->core_body)? $itemElement->text = $itemElement->core_body : $itemElement->text = null;

				// Create new stdClass Object
				$tag_item = new stdClass;

				if (isset($itemElement->core_params)) {

					if ($itemElement->core_params != '') {
						$tag_item_arr = json_decode($itemElement->core_params);

						// Add some data to tag item object
						$tag_item->title = $this->escape($tag_item_arr->title);
						$tag_item->artist = $this->escape($tag_item_arr->artist);
						$tag_item->album = $this->escape($tag_item_arr->album);
						$tag_item->alias = $this->escape($itemElement->core_alias);
						$tag_item->id = $itemElement->content_item_id;
						$tag_item->file = $this->escape($tag_item_arr->file);
						$tag_item->pathatlocal = $this->escape($tag_item_arr->pathatlocal);

						array_push($tag_items_arr, $tag_item);
					}
				}
			}
		}

		$this->state      = &$state;
		$this->items      = &$items;
		$this->children   = &$children;
		$this->parent     = &$parent;
		$this->pagination = &$pagination;
		$this->user       = &$user;
		$this->item       = &$item;

		$dispatcher = JEventDispatcher::getInstance();

		JPluginHelper::importPlugin('playjoom');
		$dispatcher->trigger('onContentPrepare', array ('com_tags.tag', &$item, &$itemElement->core_params, 0));

		$this->events = new stdClass;
		$results = $dispatcher->trigger('onBeforePJContent', array(&$tag_items_arr, &$this->params, 'tag'));
		$this->events->onBeforePJContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterPJContent', array(&$tag_items_arr, &$this->params, 'tag'));
		$this->events->onAfterPJContent = trim(implode("\n", $results));

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		// Merge tag params. If this is single-tag view, menu params override tag params
		// Otherwise, article params override menu item params
		$this->params	= $this->state->get('params');
		$active	= $app->getMenu()->getActive();
		$temp	= clone ($this->params);

		// Check to see which parameters should take priority
		if ($active)
		{
			$currentLink = $active->link;
			// If the current view is the active item and an tag view for one tag, then the menu item params take priority
			if (strpos($currentLink, 'view=tag') && (strpos($currentLink, '&id[0]='.(string) $item[0]->id)))
			{
				// $item->params are the article params, $temp are the menu item params
				// Merge so that the menu item params take priority
				$this->params->merge($temp);
				// Load layout from active query (in case it is an alternative menu item)
				if (isset($active->query['layout'])) {
					$this->setLayout($active->query['layout']);
				}
			}
			else
			{
				// Current view is not tags, so the global params take priority since tags is not an item.
				// Merge the menu item params with the global params so that the article params take priority
				$temp->merge($this->state->params);
				$this->params = $temp;

				// Check for alternative layouts (since we are not in a single-article menu item)
				// Single-article menu item layout takes priority over alt layout for an article
				if ($layout = $this->params->get('tags_layout'))
				{
					$this->setLayout($layout);
				}
			}
		}
		else
		{
			// Merge so that item params take priority
			$temp->merge($item[0]->params);
			$item[0]->params = $temp;
			// Check for alternative layouts (since we are not in a single-tag menu item)
			// Single-tag menu item layout takes priority over alt layout for an article
			if ($layout = $item[0]->params->get('tag_layout'))
			{
				$this->setLayout($layout);
			}
		}

		// Increment the hit counter
		$model = $this->getModel();
		$model->hit();

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$title 		= null;

		//load javascript and css script
		$document	= JFactory::getDocument();

		// add style sheet
		if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
			$document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/tag.css');
		}

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_TAGS_DEFAULT_PAGE_TITLE'));
		}

		if ($menu && ($menu->query['option'] != 'com_tags'))
		{
			$this->params->set('page_subheading', $menu->title);
		}

		$title = $this->state->params->get('page_title');

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);

		foreach ($this->item as $itemElement)
		{
			if ($itemElement->metadesc)
			{
				$this->document->setDescription($itemElement->metadesc);
			}
			elseif ($itemElement->metadesc && $this->params->get('menu-meta_description'))
			{
				$this->document->setDescription($this->params->get('menu-meta_description'));
			}

			if ($itemElement->metakey)
			{
				$this->document->setMetadata('keywords', $itemElement->metakey);
			}
			elseif (!$itemElement->metakey && $this->params->get('menu-meta_keywords'))
			{
				$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
			}

			if ($this->params->get('robots'))
			{
				$this->document->setMetadata('robots', $this->params->get('robots'));
			}

			if ($app->getCfg('MetaAuthor') == '1')
			{
				$this->document->setMetaData('author', $itemElement->created_user_id);
			}

		}

		// TODO create tag feed document
		// Add alternative feed link

		if ($this->params->get('show_feed_link', 1) == 1)
		{
			$link	= '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}
	}
}
