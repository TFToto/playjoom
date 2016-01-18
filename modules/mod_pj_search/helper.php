<?php
/**
 * Contains the helper methods for the PlayJoom Search module.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Site
 * @subpackage com_pj_search
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2014 by playjoom.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_search
 *
 *
 * @package     PlayJoom.Modules
 * @subpackage  mod_pj_search
 */
class ModPJSearchHelper
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public static function createScripts(&$params, &$params_com_search) {

		//load javascripts
		JHtml::_('jquery.framework');

		/*
		 * 	Priority order is:
		 *  1. config setup of com_search
		 *  2. config setup of mod_search
		 *  3. item of language localise file
		 */
		if ($params_com_search->get('lower_limit_searchword') != '' && $params_com_search->get('lower_limit_searchword') >=1 ) {
			$lower_limit = $params_com_search->get('lower_limit_searchword');
		} else {
			if ($params->get('min_length') != '' && $params->get('min_length') ) {
				$lower_limit = $params->get('min_length');
			} else {
				$lang        = JFactory::getLanguage();
				$lower_limit = $lang->getLowerLimitSearchWord();
			}
		}

		$document	= JFactory::getDocument();

		$script = '
			<script type="text/javascript">
				 jQuery(function() {
					jQuery( "#mod-search-searchword" ).catcomplete({
						minLength: '.$lower_limit.',
						delay: '.$params->get('delay', '0').',
						source: "'.JURI::base(true).'/index.php?option=com_playjoom&view=suggestionsearch&format=raw",
						autoFocus: '.$params->get('autofocus', 'true').',
						focus: function( event, ui ) {
							jQuery( "#mod-search-searchword" ).val( ui.item.label );
							return false;
						},
						select: function( event, ui ) {
							jQuery( "#mod-search-searchword" ).val( ui.item.label );
							jQuery(\'<input>\').attr({
								type: \'hidden\',
								id: \'mod-search-area\',
								name: \'areas[]\',
								value: ui.item.area
								}).appendTo(\'form\');
							jQuery(\'<input>\').attr({
								type: \'hidden\',
								id: \'mod-search-phrase\',
								name: \'searchphrase\',
								value: \'exact\'
								}).appendTo(\'form\');
							return false;
						}
					})

				});
			</script>
			<script type="text/javascript">
				jQuery.widget( "custom.catcomplete", jQuery.ui.autocomplete, {
					_renderMenu: function( ul, items ) {
						var that = this,
						currentCategory = "";
						jQuery.each( items, function( index, item ) {
							if ( item.category != currentCategory ) {
								ul.append( "<li class=\'ui-autocomplete-category\'>" + item.category + "</li>" );
								currentCategory = item.category;
							}
							that._renderItemData( ul, item );
						});
					}
				});
			</script>
		';

		$spinner_style  = '<style>';
		$spinner_style .= ' .ui-autocomplete-loading {
								background: #ffffff url("'.JURI::base(false).'modules/mod_pj_search/assets/css/images/spinner.gif") right center no-repeat;
							}
						  ';
		$spinner_style .= '</style>';

		//load external scripts
		$document->addCustomTag($script);
		$document->addCustomTag($spinner_style);
		$document->addStyleSheet(JURI::base(true).'/modules/mod_pj_search/assets/css/jquery-ui-1.9.2.custom.min.css');
		$document->addStyleSheet(JURI::base(true).'/modules/mod_pj_search/assets/css/custom.css');
		$document->addScript(JURI::base(true).'/modules/mod_pj_search/assets/js/jquery-ui-1.9.2.custom.min.js');
	}
	/**
	 * Display the search button as an image.
	 *
	 * @param   string	$button_text	The alt text for the button.
	 *
	 * @return  string	The HTML for the image.
	 * @since   1.5
	 */
	public static function getSearchImage($button_text)
	{
		$img = JHtml::_('image', 'searchButton.gif', $button_text, null, true, true);
		return $img;
	}
}
