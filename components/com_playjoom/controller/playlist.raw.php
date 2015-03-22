<?php
/**
 * Contains the controller methods for the PlayJoom playlist.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die;

/**
 * Contains the controller methods for the PlayJoom playlist.
 *  
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.controller
 */
class PlayJoomControllerPlaylist extends JControllerLegacy {
/**
	 * @var		string	The context for persistent state.
	 * @since   1.6
	 */
	protected $context = 'com_playjoom.playlist';

	/**
	 * Display method for the raw playlist data.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   1.5
	 * @todo	This should be done as a view, not here!
	 */
	public function display($cachable = false, $urlparams = false) {
		
		// Get the document object.
		$document	= JFactory::getDocument();
		$vName		= 'playlist';
		$vFormat	= 'raw';

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
	
			// Push document object into the view.
			$view->document = $document;

			$view->display();
		}
	}	
}