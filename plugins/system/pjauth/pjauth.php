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
 * @PlayJoom Plugin
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );

class plgSystempjauth extends JPlugin {
	
	
	function onBeforeRender() {
		
		if (!JFactory::getApplication()->isAdmin()) {
			
			$app             = JFactory::getApplication();
		    $params		     = $app->getParams('com_playjoom');
		    $pj_access_level = (int)$params->get('pj_accesslevel');
		
		    //Check for access
		    $user		= JFactory::getUser();
		
		    if ($user->get('id') == 0
		      && $pj_access_level >= 2) 
		    {
		    	$public = null;
		    }
		    else {
		    	$groups	= implode('|', $user->getAuthorisedViewLevels($user->get('id')));

			    if (preg_match("(".$groups. ")",$pj_access_level)
			     || JAccess::check($user->get('id'), 'core.admin') == 1
			     || $pj_access_level < 2) 
			    {
			    	$public = true;
			    }
			    else {
				    $public = null;
			    }
		    }
					
		    $template	= $app->getTemplate(true);
		    $file		= JRequest::getCmd('tmpl', 'index');
		
		   if (!is_dir(JPATH_THEMES . '/' . $template->template)) {
		   	   $file = 'index';
		   }

		   if (!$public) {
			   $file = 'nopublic';
			   JResponse::setHeader('Status', '503 Service Temporarily Unavailable', 'true');
		   }
		
		   if (!is_dir(JPATH_THEMES . '/' . $template->template)) {
			   $file = 'component';
		   }
		
		   $params = array(
				  'file'	  => $file.'.php',
				  'template'  => 'tmpl',
				  'directory' => JPATH_PLUGINS.'/system/pjauth/',
				  'params'	  => $template->params
		   );
		
		   $document = JFactory::getDocument();
		   $document->parse($params);
		
		   $caching = false;
		   if ($app->getCfg('caching') && $app->getCfg('caching', 2) == 2 && !$user->get('id')) {
			   $caching = true;
		   }
		   
		   // Render the document.
		   JResponse::setBody($document->render($caching, $params));
		
		}	
    }
}