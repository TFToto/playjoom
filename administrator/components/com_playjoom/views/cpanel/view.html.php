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
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
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
 
class PlayJoomViewCPanel extends JViewLegacy
{
        /**
         * PlayJoom view display method
         * @return void
         */
        //function display($tpl = null) 
       // {
                
        protected $modules = null;

	public function display($tpl = null)
	{
		
		/*
		 * Set the template - this will display cpanel.php
		 * from the selected admin template.
		 */
		$cparams = JComponentHelper::getParams('com_playjoom');
		
		//JRequest::setVar('tmpl', 'cpanel');

		// Display the cpanel modules
		$this->modules = JModuleHelper::getModules('cpanel');
		
		// Set the toolbar
        $this->addToolBar();
        
        //load params data
        $this->assignRef('cparams', $cparams);

		parent::display($tpl);
	}
       /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $canDo = PlayJoomHelper::getActions();
                JToolBarHelper::title(JText::_('COM_PLAYJOOM_MANAGER_PLAYJOOMS'), 'playjoom');
                
                if ($canDo->get('core.admin')) {
                  // place holder
                }
        }
        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('COM_PLAYJOOM_ADMINISTRATION'));
        }
}
