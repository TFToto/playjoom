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
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2014-01-26 10:26:58 +0100 (So, 26 Jan 2014) $
 * @revision $Revision: 893 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/administrator/components/com_playjoom/setupscript.php $
 */


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of PlayJoom component
 */
jimport('joomla.installer.installer');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
$options['text_file'] = 'playjoom_update.php';
JLog::addLogger($options, JLog::INFO, array('Update', 'databasequery', 'jerror'));

$install_source = str_replace('components/com_playjoom', '', $this->parent->getPath('source'));
JLog::add('Install source path: '.$install_source.'/components/com_playjoom', JLog::INFO, 'Update');

$installer = new JInstaller();
$installer->install($install_source.'/components/com_playjoom');
//Install the modules
/*
$installer->install($install_source.'modules/mod_pj_newcontents');
$installer->install($install_source.'modules/mod_pj_populartracks');
$installer->install($install_source.'modules/mod_pj_alphabeticalbar');
$installer->install($install_source.'modules/mod_pj_statistics');
$installer->install($install_source.'modules/mod_pj_lastplayed');
$installer->install($install_source.'modules/mod_pj_footer');
$installer->install($install_source.'modules/mod_pj_quickicon');
$installer->install($install_source.'modules/mod_pj_menu');
$installer->install($install_source.'modules/mod_pj_search');
$installer->install($install_source.'modules/mod_pj_tags_popular');
//Install the plugins
$installer->install($install_source.'plugins/playjoom/trackcontrol');
$installer->install($install_source.'plugins/playjoom/trackvote');
$installer->install($install_source.'plugins/playjoom/playbutton');
$installer->install($install_source.'plugins/playjoom/360player');
$installer->install($install_source.'plugins/playjoom/tracktimeinfo');
$installer->install($install_source.'plugins/playjoom/download');
$installer->install($install_source.'plugins/playjoom/playlist');
$installer->install($install_source.'plugins/system/pjtemplatetoggle');
$installer->install($install_source.'plugins/system/pjauth');
$installer->install($install_source.'plugins/system/pjlogger');
$installer->install($install_source.'plugins/search/pj_tracks');
$installer->install($install_source.'plugins/search/pj_artists');
$installer->install($install_source.'plugins/search/pj_albums');
//Install the templates
$installer->install($install_source.'templates/tegloadmin');
$installer->install($install_source.'templates/teglofound');
*/
class com_playjoomInstallerScript {

	/**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {

    	$this->enablePlugins();

        echo '<p>' . JText::_('COM_PLAYJOOM_INSTALL_TEXT') . '</p>';
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
    	// $parent is the class calling this method
        echo '<p>' . JText::_('COM_PLAYJOOM_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {

    	echo '<p>' . JText::_('COM_PLAYJOOM_UPDATE_TEXT') . '</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {

    	// $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {

    }

    function enablePlugins() {

    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('extension_id');
    	$query->from('#__extensions');
    	$query->where('type = "plugin"');
    	$query->where('element = "trackcontrol"');

    	$db->setQuery($query);
    	$row = $db->loadObject();

    	// Check for a database error.
    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$query = "UPDATE #__extensions SET enabled ='1' WHERE extension_id='".$row->extension_id."'";
    	$db->setQuery( $query );
    	$db->query();

    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('extension_id');
    	$query->from('#__extensions');
    	$query->where('type = "plugin"');
    	$query->where('element = "trackvote"');

    	$db->setQuery($query);
    	$row = $db->loadObject();

    	// Check for a database error.
    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$query = "UPDATE #__extensions SET enabled ='1' WHERE extension_id='".$row->extension_id."'";
    	$db->setQuery( $query );
    	$db->query();

    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('extension_id');
    	$query->from('#__extensions');
    	$query->where('type = "plugin"');
    	$query->where('element = "pj_tracks"');

    	$db->setQuery($query);
    	$row = $db->loadObject();

    	// Check for a database error.
    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$query = "UPDATE #__extensions SET enabled ='1' WHERE extension_id='".$row->extension_id."'";
    	$db->setQuery( $query );
    	$db->query();

    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('extension_id');
    	$query->from('#__extensions');
    	$query->where('type = "plugin"');
    	$query->where('element = "pj_albums"');

    	$db->setQuery($query);
    	$row = $db->loadObject();

    	// Check for a database error.
    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$query = "UPDATE #__extensions SET enabled ='1' WHERE extension_id='".$row->extension_id."'";
    	$db->setQuery( $query );
    	$db->query();

    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query->select('extension_id');
    	$query->from('#__extensions');
    	$query->where('type = "plugin"');
    	$query->where('element = "pj_artists"');

    	$db->setQuery($query);
    	$row = $db->loadObject();

    	// Check for a database error.
    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}

    	$query = "UPDATE #__extensions SET enabled ='1' WHERE extension_id='".$row->extension_id."'";
    	$db->setQuery( $query );
    	$db->query();

    	if ($db->getErrorNum())
    	{
    		JError::raiseWarning(500, $db->getErrorMsg());
    		return false;
    	}
    }
 }
