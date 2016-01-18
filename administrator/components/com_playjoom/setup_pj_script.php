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
 * @copyright Copyright (C) 2010-2015 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of PlayJoom component
 */
jimport('joomla.installer.installer');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

$installer = new JInstaller();
//Install the modules
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_newcontents');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_populartracks');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_alphabeticalbar');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_statistics');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_lastplayed');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_footer');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_quickicon');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_menu');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_search');
$installer->install($this->parent->getPath('source').'/extensions/mod_pj_tags_popular');
//Install the plugins
$installer->install($this->parent->getPath('source').'/extensions/playjoom/trackcontrol');
$installer->install($this->parent->getPath('source').'/extensions/playjoom/trackvote');
$installer->install($this->parent->getPath('source').'/extensions/playjoom/playbutton');
$installer->install($this->parent->getPath('source').'/extensions/playjoom/360player');
$installer->install($this->parent->getPath('source').'/extensions/playjoom/barplayer');
$installer->install($this->parent->getPath('source').'/extensions/playjoom/tracktimeinfo');
$installer->install($this->parent->getPath('source').'/extensions/playjoom/download');
$installer->install($this->parent->getPath('source').'/extensions/playjoom/playlist');
$installer->install($this->parent->getPath('source').'/extensions/system/pjtemplatetoggle');
$installer->install($this->parent->getPath('source').'/extensions/system/pjauth');
$installer->install($this->parent->getPath('source').'/extensions/system/pjlogger');
$installer->install($this->parent->getPath('source').'/extensions/search/pj_tracks');
$installer->install($this->parent->getPath('source').'/extensions/search/pj_artists');
$installer->install($this->parent->getPath('source').'/extensions/search/pj_albums');
//Install the templates
$installer->install($this->parent->getPath('source').'/extensions/templates/tegloadmin');
$installer->install($this->parent->getPath('source').'/extensions/templates/teglofound');
//Install the modules
$installer->install($this->parent->getPath('source').'/extensions/lib_playjoom');

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