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
 * HTML View class for the Tags component
 *
 * @package     Joomla.Site
 * @subpackage  com_tags
 * @since       3.1
 */
class TagsViewTag extends JViewLegacy
{
	public function display($tpl = null)
	{
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$document->link = JRoute::_(TagsHelperRoute::getTagRoute($app->input->getInt('id')));

		$app->input->set('limit', $app->getCfg('feed_limit'));
		$siteEmail = $app->getCfg('mailfrom');
		$fromName  = $app->getCfg('fromname');
		$feedEmail = $app->getCfg('feed_email', 'author');
		$document->editor = $fromName;
		if ($feedEmail != "none")
		{
			$document->editorEmail = $siteEmail;
		}

		// Get some data from the model
		$items    = $this->get('Items');
		foreach ($items as $item)
		{
			// Strip HTML from feed item title
			$title = $this->escape($item->core_title);
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

			// URL link to tagged item
			// Change to new routing once it is merged
			$link = JRoute::_($item->link);

			// Strip HTML from feed item description text
			$description = $item->core_body;
			$author			= $item->core_created_by_alias ? $item->core_created_by_alias : $item->author;
			$date = ($item->displayDate ? date('r', strtotime($item->displayDate)) : '');

			// Load individual item creator class
			$feeditem = new JFeedItem;
			$feeditem->title       = $title;
			$feeditem->link        = $link;
			$feeditem->description = $description;
			$feeditem->date        = $date;
			$feeditem->category    = $item->title;
			$feeditem->author      = $author;

			if ($feedEmail == 'site')
			{
				$item->authorEmail = $siteEmail;
			}
			elseif ($feedEmail === 'author')
			{
				$item->authorEmail = $item->author_email;
			}

			// Loads item info into RSS array
			$document->addItem($feeditem);
		}

	}
}
