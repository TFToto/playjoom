<?php
/**
 * @package     Playjoom.Plugin
 * @subpackage  Quickicon.Joomlaupdate
 *
 * @copyright   Copyright (C) 2010 - 2014 by teglo, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla! udpate notification plugin
 *
 * @package     PlayJoom.Plugin
 * @subpackage  Quickicon.playjoomupdate
 * @since       0.10.1
 */
class PlgQuickiconPlayJoomupdate extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method is called when the Quick Icons module is constructing its set
	 * of icons. You can return an array which defines a single icon and it will
	 * be rendered right after the stock Quick Icons.
	 *
	 * @param   string  $context  The calling context
	 *
	 * @return  array  A list of icon definition associative arrays, consisting of the
	 *                 keys link, image, text and access.
	 *
	 * @since   2.5
	 */
	public function onGetIcons($context) {

		//Load helper methods
		require_once JPATH_ADMINISTRATOR.'/components/com_playjoomupdate/helpers/playjoomupdate.php';
		require_once JPATH_ADMINISTRATOR.'/components/com_playjoomupdate/helpers/pjversion.php';

		// Define the Joomla version if not already defined.
		if (!defined('PJVERSION')) {
			$jversion = new PJVersion;
			define('PJVERSION', $jversion->getShortVersion());
		}

		if(!class_exists('InstallerHelper')){
			require_once(JPATH_ADMINISTRATOR.'/components/com_installer/helpers/installer.php');
		}
		$this->UpdateConf = InstallerHelper::getConfig('Update',JPATH_ADMINISTRATOR.'/components/com_playjoomupdate/playjoomupdate.conf.php');
		$PJ_Extension_ID = $this->UpdateConf->get('PJUpdate_extension_id');

		if ($context != $this->params->get('context', 'mod_pj_quickicon') || !JFactory::getUser()->authorise('core.manage', 'com_installer')) {
			return;
		}

		JHtml::_('jquery.framework');

		$cur_template = JFactory::getApplication()->getTemplate();
		$url = JUri::base() . 'index.php?option=com_playjoomupdate';
		$ajax_url = JUri::base() . 'index.php?option=com_installer&view=update&task=update.ajax';
		$script = array();
		$script[] = 'var plg_quickicon_playjoomupdate_url = \'' . $url . '\';';
		$script[] = 'var plg_quickicon_playjoomupdate_ajax_url = \'' . $ajax_url . '\';';
		$script[] = 'var plg_quickicon_playjoomupdatecheck_pjversion = \''.PJVERSION.'\'';
		$script[] = 'var plg_quickicon_playjoomupdatecheck_pjextensionid = \''.$PJ_Extension_ID.'\'';
		$script[] = 'var plg_quickicon_playjoomupdate_text = {'
			. '"UPTODATE" : "' . JText::_('PLG_QUICKICON_JOOMLAUPDATE_UPTODATE', true) . '",'
			. '"UPDATEFOUND": "' . JText::_('PLG_QUICKICON_JOOMLAUPDATE_UPDATEFOUND', true) . '",'
			. '"UPDATEFOUND_MESSAGE": "' . JText::_('PLG_QUICKICON_JOOMLAUPDATE_UPDATEFOUND_MESSAGE', true) . '",'
			. '"UPDATEFOUND_BUTTON": "' . JText::_('PLG_QUICKICON_JOOMLAUPDATE_UPDATEFOUND_BUTTON', true) . '",'
			. '"ERROR": "' . JText::_('PLG_QUICKICON_JOOMLAUPDATE_ERROR', true) . '",'
			. '};';
		$script[] = 'var plg_quickicon_playjoomupdate_img = {'
			. '"UPTODATE" : "' . JUri::base(true) . '/templates/' . $cur_template . '/images/header/icon-48-jupdate-uptodate.png",'
			. '"UPDATEFOUND": "' . JUri::base(true) . '/templates/' . $cur_template . '/images/header/icon-48-jupdate-updatefound.png",'
			. '"ERROR": "' . JUri::base(true) . '/templates/' . $cur_template . '/images/header/icon-48-deny.png",'
			. '};';
		//load javascripts
		$document	= JFactory::getDocument();
		$document->addScriptDeclaration(implode("\n", $script));
		$document->addScript(JURI::root(true).'/plugins/quickicon/playjoomupdate/js/pj_update_checker.js','text/javascript',true,false);

		return array(
			array(
				'link' => 'index.php?option=com_playjoomupdate',
				'image' => 'fa fa-download',
				'icon' => 'header/icon-48-download.png',
				'text' => JText::_('PLG_QUICKICON_JOOMLAUPDATE_CHECKING'),
				'id' => 'plg_quickicon_joomlaupdate',
				'group' => 'MOD_QUICKICON_MAINTENANCE'
			)
		);
	}
}
