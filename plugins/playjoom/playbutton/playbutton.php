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
 * @subpackage	plg_playbutton
 */
class plgPlayjoomPlaybutton extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $params) {

		parent::__construct($subject, $params);
		$this->loadLanguage();

		$app = JFactory::getApplication();

		if($app->isAdmin() && $this->params->get('plg_playjoom_barplayer_params_basic_view','both') == 'site'
		  || $app->isSite() && $this->params->get('plg_playjoom_barplayer_params_basic_view','both') == 'admin') {
			return null;
		} else {

			// Include the plugin HTML helpers.
			JHtml::addIncludePath(JPATH_LIBRARIES . '/playjoom/cms/html');
			JHtml::_('SoundManager.sm_framework');
			JHtml::_('SoundManager.playbutton');

			//load javascripts
			$document	= JFactory::getDocument();

			$swf  = '<script type="text/javascript">';
			$swf .= "soundManager.setup({
						url: '".JURI::root(false)."media/lib_playjoom/soundmanager/swf/'
					});";

	        $swf .= '</script>';
	        $document->addCustomTag($swf);

	        $css  = '<style>';
	        $css .= "
					a.sm2_button {
						background-color: ".$this->params->get('plg_playjoom_playbutton_params_basic_button_background_color', '#ababab').";
					}
					a.sm2_button {
						background-color: ".$this->params->get('plg_playjoom_playbutton_params_basic_button_hover_background_color', '#ababab').";
					}
					a.sm2_button.sm2_playing {
						background-color: ".$this->params->get('plg_playjoom_playbutton_params_basic_playing_background_color', '#cc3333').";
					}
					a.sm2_button.sm2_playing:hover {
						background-color: ".$this->params->get('plg_playjoom_playbutton_params_basic_playing_hover_background_color', '#cc3333').";
					}
					a.sm2_button.sm2_paused {
						background-color: ".$this->params->get('plg_playjoom_playbutton_params_basic_pause_background_color', '#666').";
					}
					a.sm2_button.sm2_paused:hover {
						background-color: ".$this->params->get('plg_playjoom_playbutton_params_basic_pause_hover_background_color', '#666').";
					}

				";

	        $css .= '</style>';
	        $document->addCustomTag($css);
		}
	}
    public function onAfterTrackLink(&$item, $params, $TitleName=null) {

		$session = JFactory::getSession();
		$app = JFactory::getApplication();

		if($app->isAdmin() && $this->params->get('plg_playjoom_playbutton_params_basic_view','both') == 'site'
		  || $app->isSite() && $this->params->get('plg_playjoom_playbutton_params_basic_view','both') == 'admin') {
			return null;
		} else {

			$html = '';

			$rootURL = rtrim(JURI::base(),'/');
			$subpathURL = JURI::base(true);
			if(!empty($subpathURL) && ($subpathURL != '/')) {
				$rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
			}

			if ($app->isAdmin()) {
				$link = $rootURL.JRoute::_('index.php?option=com_playjoom&view=broadcast&format=raw&tlk='.hash('sha256',$session->getId().'+'.PlayJoomHelper::getUserIP().'+'.$item->id).'&id='.$item->id).'&track.mp3';
				$track_link = str_replace('/administrator', '', $link);
			} else {
				$track_link = $rootURL.JRoute::_('index.php?option=com_playjoom&view=broadcast&format=raw&tlk='.hash('sha256',$session->getId().'+'.PlayJoomHelper::getUserIP().'+'.$item->id).'&id='.$item->id).'&track.mp3';
			}
			$html .= '<a href="'.$track_link.'" class="sm2_button" style="margin-left:15px;"></a>';

			return $html;
		}
	}
}
