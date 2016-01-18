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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * PlayJoom Model
 */
class PlayJoomModelPlayJoom extends JModelItem
{
        /**
         * @var object item
         */
        protected $item;
 
        /**
         * Method to auto-populate the model state.
         *
         * This method should only be called once per instantiation and is designed
         * to be called on the first call to the getState() method unless the model
         * configuration flag to ignore the request is set.
         *
         * Note. Calling getState in this method will result in recursion.
         *
         * @return      void
         * @since       1.6
         */
        protected function populateState() 
        {
                $app = JFactory::getApplication();
                // Get the message id
                $id = JRequest::getInt('id');
                $section = JRequest::getInt('section');
                $this->setState('message.id', $id);
 
                // Load the parameters.
                $params = $app->getParams();
                $this->setState('params', $params);
                parent::populateState();
        }
 
        /**
         * Returns a reference to the a Table object, always creating it.
         *
         * @param       type    The table type to instantiate
         * @param       string  A prefix for the table class name. Optional.
         * @param       array   Configuration array for model. Optional.
         * @return      JTable  A database object
         * @since       1.6
         */
        public function getTable($type = 'PlayJoom', $prefix = 'PlayJoomTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
 
        /**
         * Get the message
         * @return object The message to be displayed to the user
         */
        public function getItem() 
        {
                if (!isset($this->item)) 
                {
                        $id = $this->getState('message.id');
                        $this->_db->setQuery($this->_db->getQuery(true)
                                ->from('#__jpaudiotracks as h')
                                ->leftJoin('#__categories as c ON h.catid=c.id')
                                ->select('h.title, h.params, c.title as category')
                                ->where('h.id=' . (int)$id));
                        if (!$this->item = $this->_db->loadObject()) 
                        {
                                $this->setError($this->_db->getError());
                        }
                        else
                        {
                                // Load the JSON string
                                $params = new JRegistry;
                                $params->loadJSON($this->item->params);
                                $this->item->params = $params;
 
                                // Merge global params with item params
                                $params = clone $this->getState('params');
                                $params->merge($this->item->params);
                                $this->item->params = $params;
                        }
                }
                return $this->item;
        }
        public function buildlist() 
        {
        	$action = "liste bauen";
          return "eins";       	
        }
}