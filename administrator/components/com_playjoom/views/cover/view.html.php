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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.helper' );
 
/**
 * PlayJoom View
 */
class PlayJoomViewCover extends JViewLegacy {
	
	/**
     * display method of playjoom view
     * @return void
     */
    public function display($tpl = null) {
    	
    	$dispatcher	= JDispatcher::getInstance();
    	
    	//Get User Objects
        $user  = JFactory::getUser();
        $canDo = PlayJoomHelper::getActions();

        // get the Data
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');            
        $this->OptionsNewCover = $this->get('OptionsNewCover');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
        	
        	JError::raiseError(500, implode('<br />', $errors));
        	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Problem with database query. Error500: '.implode('<br />', $errors), 'priority' => JLog::ERROR, 'section' => 'admin')));
            return false;
        }
 
        if ($canDo->get('core.edit')
          || $canDo->get('core.create') && !JRequest::getVar('id')
          || JAccess::check($user->get('id'), 'core.admin') == 1) {
                 	
            // Set the toolbar
            $this->addToolBar();
 
            // Display the template
            $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Load template for cover viewer.', 'priority' => JLog::INFO, 'section' => 'admin')));
            parent::display($tpl);
        } else {
        	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Can not displaying cover viewer. '.JText::_('JERROR_ALERTNOAUTHOR'), 'priority' => JLog::WARNING, 'section' => 'admin')));
        	JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

            // Set the document
            $this->setDocument();
    }
 
    /**
     * Method for to set the toolbar items
     * 
     * @return void 
     */
    protected function addToolBar() {
    	
    	$config = JComponentHelper::getParams('com_playjoom');
    	
    	$session	= JFactory::getSession();
    	
    	$this->assignRef('session', $session);
    	$this->assignRef('config', $config);
    	
        JFactory::getApplication()->input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		
		$canDo = PlayJoomHelper::getActions();

		if (isset($this->item->id) && is_numeric($this->item->id)) {
			JToolbarHelper::title(JText::_('COM_PLAYJOOM_MANAGER_PLAYJOOM_EDIT_COVER'));
		} else {
			JToolbarHelper::title(JText::_('COM_PLAYJOOM_MANAGER_PLAYJOOM_NEW_COVER'));
		}
		if ($canDo->get('core.edit')||$canDo->get('core.create')) {
			if (isset($this->item->id) && is_numeric($this->item->id)) {
				JToolbarHelper::apply('cover.apply');
				JToolbarHelper::save('cover.save');
			} else {
				JToolbarHelper::apply('addcover.apply');
			}
		}
		
		if (empty($this->item->id))  {
			JToolbarHelper::cancel('cover.cancel');
		} else {
			JToolbarHelper::cancel('cover.cancel', 'JTOOLBAR_CLOSE');
		}
	}
        
       /**
        * Method to set up the document properties
        *
        * @return void
        */
        protected function setDocument() {
                $isNew = $this->item->id == 0;
                $document = JFactory::getDocument();
                $document->setTitle($isNew ? JText::_('COM_PLAYJOOM_PLAYJOOM_CREATING') : JText::_('COM_PLAYJOOM_PLAYJOOM_EDITING'));
                $document->addScript(JURI::root() . "/administrator/components/com_playjoom/views/playjoom/submitbutton.js");
                JText::script('COM_PLAYJOOM_PLAYJOOM_ERROR_UNACCEPTABLE');
        }
}