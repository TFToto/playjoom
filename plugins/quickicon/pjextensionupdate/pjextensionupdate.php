<?php
/**
 * @package     PlayJoom.Plugin
 * @subpackage  Quickicon.PJExtensionupdate
 *
 * @copyright   Copyright (C) 2010 - 2014 by teglo.info, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * PlayJoom udpate notification plugin
 *
 * @package     PlayJoom.Plugin
 * @subpackage  Quickicon.PJExtensionupdate
 * @since       0.10
 */
class PlgQuickiconPJExtensionupdate extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Returns an icon definition for an icon which looks for extensions updates
	 * via AJAX and displays a notification when such updates are found.
	 *
	 * @param   string  $context  The calling context
	 *
	 * @return  array  A list of icon definition associative arrays, consisting of the
	 *                 keys link, image, text and access.
	 *
	 * @since   2.5
	 */
	public function onGetIcons($context)
	{
		if ($context != $this->params->get('context', 'mod_pj_quickicon') || !JFactory::getUser()->authorise('core.manage', 'com_installer'))
		{
			return;
		}

		JHtml::_('jquery.framework');

		$ajax_url = JUri::base().'index.php?option=com_installer&view=update&task=update.ajax';
		$script = "var plg_quickicon_extensionupdate_ajax_url = '$ajax_url';\n";
		$script .= 'var plg_quickicon_extensionupdate_text = {"UPTODATE" : "'.
				JText::_('PLG_QUICKICON_PJEXTENSIONUPDATE_UPTODATE', true).'", "UPDATEFOUND": "'.
				JText::_('PLG_QUICKICON_PJEXTENSIONUPDATE_UPDATEFOUND', true).'", "ERROR": "'.
				JText::_('PLG_QUICKICON_PJEXTENSIONUPDATE_ERROR', true)."\"};\n";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		$document->addScript(JURI::root(true).'/plugins/quickicon/pjextensionupdate/js/pj_extension_checker.js','text/javascript',true,false);

		return array(
				array(
						'link' => 'index.php?option=com_installer&view=update',
						'image' => 'fa fa-bell-o',
						'icon' => 'header/icon-48-extension.png',
						'text' => JText::_('PLG_QUICKICON_PJEXTENSIONUPDATE_CHECKING'),
						'id' => 'plg_quickicon_extensionupdate',
						'group' => 'MOD_QUICKICON_MAINTENANCE'
				)
		);
	}
}
