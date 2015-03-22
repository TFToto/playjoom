<?php
/**
 * Contains the default folder template for the media output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Admin
 * @subpackage views.medialist
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Media component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_playjoom
 * @since 1.0
 */
class PlayJoomViewMediaList extends JViewLegacy
{
	function display($tpl = null)
	{
		// Do not allow cache
		JResponse::allowCache(false);

		$app	= JFactory::getApplication();
		$style = $app->getUserStateFromRequest('media.list.layout', 'layout', 'details', 'word');

		$lang	= JFactory::getLanguage();

		JHtml::_('behavior.framework', true);

		$document = JFactory::getDocument();
		$document->addStyleSheet('../media/media/css/medialist-'.$style.'.css');
		if ($lang->isRTL()) :
			$document->addStyleSheet('../media/media/css/medialist-'.$style.'_rtl.css');
		endif;

		$document->addScriptDeclaration("
		window.addEvent('domready', function() {
			window.parent.document.updateUploader();
			$$('a.img-preview').each(function(el) {
				el.addEvent('click', function(e) {
					new Event(e).stop();
					window.top.document.preview.fromElement(el);
				});
			});
		});");

		$images = $this->get('images');
		$documents = $this->get('documents');
		$folders = $this->get('folders');
		$state = $this->get('state');
		
		$this->assign('baseURL', JURI::root());
		$this->assignRef('images', $images);
		$this->assignRef('documents', $documents);
		$this->assignRef('folders', $folders);
		$this->assignRef('state', $state);

		parent::display($tpl);
	}

	function setFolder($index = 0)
	{
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

	function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_img = &$this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}

	function setDoc($index = 0)
	{
		if (isset($this->documents[$index])) {
			$this->_tmp_doc = &$this->documents[$index];
		} else {
			$this->_tmp_doc = new JObject;
		}
	}
}
