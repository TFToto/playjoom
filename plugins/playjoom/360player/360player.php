<?php
/**
 * @package Joomla 3.0.x
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Plugin
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
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
 * 360Player plugin.
 *
 * @package		PlayJoom
 * @subpackage	plg_360player
 */
class plgPlayjoom360Player extends JPlugin
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

		if($app->isAdmin() && $this->params->get('plg_playjoom_barplayer_params_basic_view') == 'site'
		  || $app->isSite() && $this->params->get('plg_playjoom_barplayer_params_basic_view') == 'admin') {
			return null;
		} else {

			// Include the plugin HTML helpers.
			JHtml::addIncludePath(JPATH_LIBRARIES . '/playjoom/cms/html');
			JHtml::_('SoundManager.sm_framework');
			JHtml::_('SoundManager.threehundredsixtyplayer');

			//load javascripts
			$document	= JFactory::getDocument();

			$swf  = '<script type="text/javascript">';
			$swf .= "soundManager.setup({
						url: '".JURI::root(false)."media/lib_playjoom/soundmanager/swf/'
					});

					threeSixtyPlayer.config.scaleFont = (navigator.userAgent.match(/msie/i)?false:true);
					threeSixtyPlayer.config.showHMSTime = true;

					// enable some spectrum stuffs

					threeSixtyPlayer.config.useWaveformData = true;
					threeSixtyPlayer.config.useEQData = true;

					// enable this in SM2 as well, as needed

					if (threeSixtyPlayer.config.useWaveformData) {
						soundManager.flash9Options.useWaveformData = true;
					}
					if (threeSixtyPlayer.config.useEQData) {
						soundManager.flash9Options.useEQData = true;
					}
					if (threeSixtyPlayer.config.usePeakData) {
						soundManager.flash9Options.usePeakData = true;
					}

					if (threeSixtyPlayer.config.useWaveformData || threeSixtyPlayer.flash9Options.useEQData || threeSixtyPlayer.flash9Options.usePeakData) {
					// even if HTML5 supports MP3, prefer flash so the visualization features can be used.
						soundManager.preferFlash = true;
					}

					// favicon is expensive CPU-wise, but can be used.
					if (window.location.href.match(/hifi/i)) {
						threeSixtyPlayer.config.useFavIcon = true;
					}

					if (window.location.href.match(/html5/i)) {
						// for testing IE 9, etc.
						soundManager.useHTML5Audio = true;
					}
			";


	        $swf .= '</script>';
	        $document->addCustomTag($swf);
		}
	}

	public function onAfterTrackLink(&$item, $params, $TitleName=null) {

		$session = JFactory::getSession();
		$app = JFactory::getApplication();

		if($app->isAdmin() && $this->params->get('plg_playjoom_barplayer_params_basic_view') == 'site'
				|| $app->isSite() && $this->params->get('plg_playjoom_barplayer_params_basic_view') == 'admin') {
			return null;
		} else {

			$rootURL = rtrim(JURI::base(),'/');
			$subpathURL = JURI::base(true);
			if(!empty($subpathURL) && ($subpathURL != '/')) {
				$rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
			}

			if (isset($item->id) && isset($item->file)) {

				$html = '';

				if ($app->isAdmin()) {
					$link = $rootURL.JRoute::_('index.php?option=com_playjoom&view=broadcast&format=raw&tlk='.hash('sha256',$session->getId().'+'.PlayJoomHelper::getUserIP().'+'.$item->id).'&id='.$item->id).'&track.mp3';
					$track_link = str_replace('/administrator', '', $link);
				} else {
					$track_link = $rootURL.JRoute::_('index.php?option=com_playjoom&view=broadcast&format=raw&tlk='.hash('sha256',$session->getId().'+'.PlayJoomHelper::getUserIP().'+'.$item->id).'&id='.$item->id).'&track.mp3';
				}
				//$html .= '<div class="ui360" style="margin-top:-0.55em;"><a href="'.JURI::root(false).'index.php?option=com_playjoom&view=broadcast&id='.$item->id.'&'.urlencode($item->file).'"></a></div>';
				$html .= '<div class="ui360" style="margin-top:-0.55em;"><a href="'.$track_link.'"></a></div>';

			} else {
				return null;
			}

			return $html;
		}
	}
}
