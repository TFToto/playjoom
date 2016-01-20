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
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of PlayJoom component
 * 
 * @package     PlayJoom.Administrator
 * @subpackage  com_playjoom
 * @since       1.6
 */
class PlayJoomController extends JControllerLegacy {
        
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'cpanel';

	/**
	  * Method to display a view.
	  *
	  * @param	boolean				If true, the view output will be cached
	  * @param	array				An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	  *
	  * @return	JControllerLegacy	This object to support chaining.
	  * @since	1.5
	  */
    function display($cachable = false, $urlparams = false) {
        	
        	JPluginHelper::importPlugin('content');
        	$vName = JRequest::getCmd('view', 'cpanel');
        	
        	$view   = $this->input->get('view', 'tracks');
        	$layout = $this->input->get('layout', 'tracks');
        	$id     = $this->input->getInt('id');
        	
        	$document = JFactory::getDocument();
        	$vType		= $document->getType();
        	
        	switch ($vName) {        		
        	
        		case 'mediaList':
        			$app	= JFactory::getApplication();
        			$mName = 'list';
        			$vLayout = $app->getUserStateFromRequest('media.list.layout', 'layout', 'details', 'word');
        			
        			// Get/Create the view
        			$view = $this->getView($vName, $vType);
        			 
        			// Get/Create the model
        			if ($model = $this->getModel($mName)) {
        				// Push the model into the view (as default)
        				$view->setModel($model, true);
        			}
        			 
        			// Set the layout
        			$view->setLayout($vLayout);
        			 
        			// Display the view
        			$view->display();
        			 
        			return $this;
        	
        			break;
        	
        		case 'media':
        			
        			$vLayout = JRequest::getCmd('layout', 'default');
        			
        			// Get/Create the view
        			$view = $this->getView($vName, $vType);
        			
        			// Get/Create the model
        			if ($model = $this->getModel('manager')) {
        				// Push the model into the view (as default)
        				$view->setModel($model, true);
        			}
        			// Set the layout
        			$view->setLayout($vLayout);
        			
        			// Display the view
        			$view->display();
        			
        			return $this;
        			
        		break;
        		default:
        			// set default view if not set
                    //JRequest::setVar('view', JRequest::getCmd('view', 'CPanel'));
 
                    // call parent behavior
                    //parent::display($cachable);
                    parent::display();
                    
                    return $this;
                    // Set the submenu                
                    //PlayJoomHelper::addSubmenu(JRequest::getWord('view', 'audiotracks'));
        	}
        }
}