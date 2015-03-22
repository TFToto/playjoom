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
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * PlayJoom Table class
 */   
class PlayJoomTableAudioTracks extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
                parent::__construct('#__jpaudiotracks', 'id', $db);
        }
/**
         * Overloaded bind function
         *
         * @param       array           named array
         * @return      null|string     null is operation was satisfactory, otherwise returns an error
         * @see JTable:bind
         * @since 1.5
         */
        public function bind($array, $ignore = '') 
        {
                if (isset($array['params']) && is_array($array['params'])) 
                {
                        // Convert the params field to a string.
                        $parameter = new JRegistry;
                        $parameter->loadArray($array['params']);
                        $array['params'] = (string)$parameter;
                }
                return parent::bind($array, $ignore);
        }
 
        /**
         * Overloaded load function
         *
         * @param       int $pk primary key
         * @param       boolean $reset reset data
         * @return      boolean
         * @see JTable:load
         */
        public function load($pk = null, $reset = true) 
        {
                if (parent::load($pk, $reset)) 
                {
                        // Convert the params field to a registry.
                        $params = new JRegistry;
                        $params->loadJSON($this->params);
                        $this->params = $params;
                        return true;
                }
                else
                {
                        return false;
                }
        }
        /**
         * Method to compute the default name of the asset.
         * The default name is in the form `table_name.id`
         * where id is the value of the primary key of the table.
         *
         * @return      string
         * @since       1.6
         */
        protected function _getAssetName()
        {
                $k = $this->_tbl_key;
                return 'com_playjoom.track.'.(int) $this->$k;
        }
 
        /**
         * Method to return the title to use for the asset table.
         *
         * @return      string
         * @since       1.6
         */
        protected function _getAssetTitle()
        {
                return $this->name;
        }
 
        /**
         * Get the parent asset id for the record
         *
         * @return      int
         * @since       1.6
         */
        protected function _getAssetParentId()
        {
        	$id = null;
                $asset = JTable::getInstance('Asset');
                $asset->loadByName('com_playjoom');
                return $asset->id;
        }
        
}
