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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Trackvote plugin.
 *
 * @package		PlayJoom
 * @subpackage	plg_tracktimeinfo
 */
class plgPlayjoomTracktimeinfo extends JPlugin {

	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $params)
	{
		parent::__construct($subject, $params);
		$this->loadLanguage();
		
		// Load the PlayJoom libaray language file.
		$lang = JFactory::getLanguage();
		$lang->load('lib_playjoom', JPATH_SITE);
		
		// Load PlayJoom Library for datetime function
		JLoader::register('PJDatetime', JPATH_LIBRARIES.'/playjoom/datetime/datetime.php', true);
	}

	public function onInTrackbox($trackitems, $params) {

		if ($trackitems->add_datetime != ''
		 && $trackitems->add_datetime != "0000-00-00 00:00:00") {
		    $DisplayAdd = JText::_( 'PLG_PLAYJOOM_ADD_TRACK' ).' '.lcfirst (PJDatetime::getDateTimeInterval($trackitems->add_datetime)).'<br />';
		} else {
		    $DisplayAdd = null;
		}

		if ($trackitems->mod_datetime != ''
		 && $trackitems->mod_datetime != "0000-00-00 00:00:00") {
		    $DisplayMod = JText::_( 'PLG_PLAYJOOM_MOD_TRACK' ).' '.lcfirst (PJDatetime::getDateTimeInterval($trackitems->mod_datetime)).'<br />';
		} else {
		    $DisplayMod = null;
		}

		if ($trackitems->access_datetime != ''
		 && $trackitems->access_datetime != "0000-00-00 00:00:00") {
		    $DisplayAccess = JText::_( 'PLG_PLAYJOOM_ACCESS_TRACK' ).' '.lcfirst (PJDatetime::getDateTimeInterval($trackitems->access_datetime)).'<br />';
		} else {
		    $DisplayAccess = null;
		}
		$html = '';

		if ($DisplayAdd != ''
		 || $DisplayMod != ''
		 || $DisplayAccess != '') {

		    //Output plugin content
		    $html .= '<div class="details_middle">';
			$html .= '<h4 class="trackdetails_title">Track Time Info</h4>';

			$html .='<ul class="trackplugin_list">';
			    $html .= $DisplayAdd;
			    $html .= $DisplayMod;
			    $html .= $DisplayAccess;
			$html .='</ul>';
		    $html .='</div>';
		} else {
		    $html = null;
		}

		return $html;
	}
}