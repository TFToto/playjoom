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
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * PlayJoom Form Field class for the HelloWorld component
 */
class JFormFieldHelloWorld extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'HelloWorld';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = new JDatabaseQuery;
                $query->select('#__helloworld.id as id,greeting,#__categories.title as category,catid');
                $query->from('#__helloworld');
                $query->leftJoin('#__categories on catid=#__categories.id');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                $options = array();
                if ($messages)
                {
                        foreach($messages as $message) 
                        {
                                $options[] = JHtml::_('select.option', $message->id, $message->greeting . ($message->catid ? ' (' . $message->category . ')' : ''));
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}