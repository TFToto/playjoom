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
defined('_JEXEC') or die('Restricted Access');

abstract class JHtmlPlayJoomSliders {
	
	protected static $loaded = array();

	/**
	 * Method to load the Bootstrap JavaScript framework into the document head
	 *
	 * If debugging mode is on an uncompressed version of Bootstrap is included for easier debugging.
	 *
	 * @param   mixed  $debug  Is debugging mode on? [optional]
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function framework($debug = null)	{
		// Only load once
		if (!empty(static::$loaded[__METHOD__]))
		{
			return;
		}
	
		// Load jQuery
		JHtml::_('jquery.framework');
	
		// If no debugging value is set, use the configuration setting
		if ($debug === null)
		{
			$config = JFactory::getConfig();
			$debug = (boolean) $config->get('debug');
		}
	
		JHtml::_('script', 'jui/bootstrap.min.js', false, true, false, false, $debug);
		static::$loaded[__METHOD__] = true;
	
		return;
	}
	/**
	 * Add javascript support for Bootstrap accordians and insert the accordian
	 *
	 * @param   string  $selector  The ID selector for the tooltip.
	 * @param   array   $params    An array of options for the tooltip.
	 *                             Options for the tooltip can be:
	 *                             - parent  selector  If selector then all collapsible elements under the specified parent will be closed when this
	 *                                                 collapsible item is shown. (similar to traditional accordion behavior)
	 *                             - toggle  boolean   Toggles the collapsible element on invocation
	 *                             - active  string    Sets the active slide during load
	 *
	 * @return  string  HTML for the accordian
	 *
	 * @since   3.0
	 */
	public static function startAccordion($selector = 'myAccordian', $params = array())	{
		
		$sig = md5(serialize(array($selector, $params)));
	
		if (!isset(static::$loaded[__METHOD__][$sig]))	{
			// Include Bootstrap framework
			static::framework();
	
			// Setup options object
			$opt['parent'] = isset($params['parent']) ? (boolean) $params['parent'] : false;
			$opt['toggle'] = isset($params['toggle']) ? (boolean) $params['toggle'] : true;
			$opt['active'] = isset($params['active']) ? (string) $params['active'] : '';
	
			$options = JHtml::getJSObject($opt);
	
			// Attach accordion to document
			JFactory::getDocument()->addScriptDeclaration(
			"(function($){
			$('#$selector').collapse($options);
		})(jQuery);"
			);
	
			// Set static array
			static::$loaded[__METHOD__][$sig] = true;
			static::$loaded[__METHOD__]['active'] = $opt['active'];
		}
	
			return '<div id="' . $selector . '" class="accordion">';
	}
	
   /**
	* Close the current accordion
	*
	* @return  string  HTML to close the accordian
	*
	* @since   3.0
	*/
	public static function endAccordion() {
		
		return '</div>';
	}
	
   /**
	* Begins the display of a new accordion slide.
	*
	* @param   string  $selector  Identifier of the accordion group.
	* @param   string  $text      Text to display.
	* @param   string  $id        Identifier of the slide.
	* @param   string  $class     Class of the accordion group.
	*
	* @return  string  HTML to add the slide
	*
	* @since   3.0
	*/
	public static function addSlide($selector, $track_text, $more_link, $id, $class = '') {
					 	
		$in = (static::$loaded['JHtmlPlayJoomSliders::startAccordion']['active'] == $id) ? ' in' : '';
		$class = (!empty($class)) ? ' ' . $class : '';
	
		$html = '<div class="accordion-group' . $class . '">'
			. '<div class="accordion-heading">'
			. $track_text		
			. '<strong><a href="#' . $id . '" class="moretxt-right" data-parent="#' . $selector . '" data-toggle="collapse" class="accordion-toggle">'
			. $more_link
			. '</a></strong>'
				. '</div>'
				. '<div class="accordion-body collapse' . $in . '" id="' . $id . '">'
					 . '<div class="accordion-inner">';
	
		return $html;
	}
	
	/**
	* Close the current slide
	*
	* @return  string  HTML to close the slide
	*
	* @since   3.0
	*/
	public static function endSlide() {
			return '</div></div></div>';
		}
}