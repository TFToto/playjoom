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

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * PlayJoom View
 */
class PlayJoomViewAudioTrack extends JViewLegacy
{
        /**
         * display method of playjoom view
         * @return void
         */
	    protected $form;
	    protected $item;
	    protected $state;

        public function display($tpl = null)
        {
                //Get User Objects
                $user  = JFactory::getUser();
                $canDo = PlayJoomHelper::getActions();

                // get the Data
                $this->form		= $this->get('Form');
		        $this->item		= $this->get('Item');
                $this->script   = $script = $this->get('Script');

                // Check for errors.
                if (count($errors = $this->get('Errors')))
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }

                if ($canDo->get('core.edit')
                 || $canDo->get('core.create') && !JRequest::getVar('id')
                 || JAccess::check($user->get('id'), 'core.admin') == 1) {

                	// Set the toolbar
                    $this->addToolBar();

                    // Display the template
                    parent::display($tpl);
                }
                else {
                	JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                }

                // Check for tag type
                $this->checkTags = JHelperTags::getTypes('objectList', array('com_playjoom.track'), true);

                // Set the document
                $this->setDocument();
        }

        /**
         * Setting the toolbar
         */
        protected function addToolBar()
        {
                JRequest::setVar('hidemainmenu', true);
                $user = JFactory::getUser();
                $userId = $user->id;
                $isNew = $this->item->id == 0;
                $canDo = PlayJoomHelper::getActions($this->item->id);

                $document = JFactory::getDocument();
                $document->addStyleDeclaration('.icon-48-addtrack {background-image: url(components/com_playjoom/images/header/icon-48-addtrack.gif);}');

                JToolBarHelper::title($isNew ? JText::_('COM_PLAYJOOM_MANAGER_PLAYJOOM_NEW') : JText::_('COM_PLAYJOOM_MANAGER_PLAYJOOM_EDIT'), 'addtrack');
                // Built the actions for new and existing records.
                if ($isNew)
                {
                        // For new records, check the create permission.
                        if ($canDo->get('core.create'))
                        {
                                JToolBarHelper::apply('audiotrack.apply', 'JTOOLBAR_APPLY');
                                JToolBarHelper::save('audiotrack.save', 'JTOOLBAR_SAVE');
                                JToolBarHelper::custom('audiotrack.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
                        }
                        JToolBarHelper::cancel('audiotrack.cancel', 'JTOOLBAR_CANCEL');
                }
                else
                {
                        if ($canDo->get('core.edit'))
                        {
                                // We can save the new record
                                JToolBarHelper::apply('audiotrack.apply', 'JTOOLBAR_APPLY');
                                JToolBarHelper::save('audiotrack.save', 'JTOOLBAR_SAVE');

                                // We can save this record, but check the create permission to see if we can return to make a new one.
                                if ($canDo->get('core.create'))
                                {
                                        JToolBarHelper::custom('audiotrack.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);

                                }
                        }
                        if ($canDo->get('core.create'))
                        {
                                JToolBarHelper::custom('audiotrack.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
                        }
                        JToolBarHelper::cancel('audiotrack.cancel', 'JTOOLBAR_CLOSE');
                }
        }
        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument()
        {
                $isNew = $this->item->id == 0;
                $document = JFactory::getDocument();
                $document->setTitle($isNew ? JText::_('COM_PLAYJOOM_PLAYJOOM_CREATING') : JText::_('COM_PLAYJOOM_PLAYJOOM_EDITING_TRACK'));
                $document->addScript(JURI::root() . $this->script);
                $document->addScript(JURI::root() . "/administrator/components/com_playjoom/views/playjoom/submitbutton.js");
                JText::script('COM_PLAYJOOM_PLAYJOOM_ERROR_UNACCEPTABLE');
        }
}