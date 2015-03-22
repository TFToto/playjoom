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
 * @copyright Copyright (C) 2010-2015 by www.teglo.info
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
 * playlist plugin.
 *
 * @package		PlayJoom
 * @subpackage	plg_playlist
 */
class plgPlayjoomBarplayer extends JPlugin
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

		// Include the plugin HTML helpers.
		JHtml::addIncludePath(JPATH_LIBRARIES . '/playjoom/cms/html');
		JHtml::_('SoundManager.sm_framework');
		JHtml::_('SoundManager.bar_ui');

		//load javascripts
		$document	= JFactory::getDocument();

		$swf  = '<script type="text/javascript">';
		$swf .= "soundManager.setup({
					url: '".JURI::root(false)."media/lib_playjoom/soundmanager/swf/',
					flashVersion: 8,
					useHTML5Audio: true,
					preferFlash: false,
					noSWFCache: false,
					html5PollingInterval: 80,
					useHighPerformance: true
        });";

		$swf .= '</script>';
		$document->addCustomTag($swf);

		$css  = '<style>';
		$css .= "
				.sm2-bar-ui {
					font-size: ".$this->params->get('plg_playjoom_barplayer_params_basic_size', '17')."px;
				}
				.sm2-bar-ui .sm2-main-controls,
				.sm2-bar-ui .sm2-playlist-drawer {
 					background-color: ".$this->params->get('plg_playjoom_barplayer_params_basic_background_color', '#ababab')."

				}
				";

		$css .= '</style>';
		$document->addCustomTag($css);
	}

	public function onBeforePJContent(&$item, $params, $TitleName=null ) {

	}
	public function onAfterPJContent(&$item, $params, $source=null, $TitleName=null) {

		$session = JFactory::getSession();

		$rootURL = rtrim(JURI::base(),'/');
		$subpathURL = JURI::base(true);
		if(!empty($subpathURL) && ($subpathURL != '/')) {
			$rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
		}

		//Check for Trackcontrol
		if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false) {

			$html = null;
		} else {

		$html = '';
		$html .= '<div class="sm2-bar-ui full-width fixed flat">
					<div class="bd sm2-main-controls">
						<div class="sm2-inline-texture"></div>
						<div class="sm2-inline-gradient"></div>
						<div class="sm2-inline-element sm2-button-element">
							<div class="sm2-button-bd">
								<a href="#play" class="sm2-inline-button play-pause">Play / pause</a>
							</div>
						</div>

						<div class="sm2-inline-element sm2-inline-status">
							<div class="sm2-playlist">
								<div class="sm2-playlist-target">
									<noscript><p>JavaScript is required.</p></noscript>
								</div>
							</div>

							<div class="sm2-progress">
								<div class="sm2-row">
									<div class="sm2-inline-time">0:00</div>
									<div class="sm2-progress-bd">
										<div class="sm2-progress-track">
											<div class="sm2-progress-bar"></div>
											<div class="sm2-progress-ball">
												<div class="icon-overlay">
											</div>
										</div>
									</div>
								</div>
								<div class="sm2-inline-duration">0:00</div>
							</div>
						</div>
					</div>
					<div class="sm2-inline-element sm2-button-element sm2-volume">
						<div class="sm2-button-bd">
							<span class="sm2-inline-button sm2-volume-control volume-shade"></span>
							<a href="#volume" class="sm2-inline-button sm2-volume-control">volume</a>
						</div>
					</div>

					<div class="sm2-inline-element sm2-button-element">
						<div class="sm2-button-bd">
							<a href="#prev" title="Previous" class="sm2-inline-button previous">&lt; previous</a>
						</div>
					</div>

					<div class="sm2-inline-element sm2-button-element">
						<div class="sm2-button-bd">
							<a href="#next" title="Next" class="sm2-inline-button next">&gt; next</a>
						</div>
					</div>


				</div>

				<div class="bd sm2-playlist-drawer sm2-element">
					<div class="sm2-inline-texture">
						<div class="sm2-box-shadow"></div>
					</div>
				<div class="sm2-playlist-wrapper">
					<ul class="sm2-playlist-bd">
				';

		foreach($item as $i => $playlist_item) {
			if (JFile::exists($playlist_item->pathatlocal.DIRECTORY_SEPARATOR.$playlist_item->file)) {
				//$html .= '<li><a href="'.$rootURL.JRoute::_('/components/com_playjoom/broadcasthandler/index.php?id='.$playlist_item->id).'&track.mp3"><b>'.$playlist_item->artist.'</b> - '.$playlist_item->title.'</a></li>';
				$html .= '<li><a href="'.$rootURL.JRoute::_('index.php?option=com_playjoom&view=broadcast&format=raw&tlk='.hash('sha256',$session->getId().'+'.PlayJoomHelper::getUserIP().'+'.$playlist_item->id).'&id='.$playlist_item->id).'&track.mp3"><b>'.$playlist_item->artist.'</b> - '.$playlist_item->title.'</a></li>';
			}
		}

     $html .= '</ul>
  </div>

 </div>

</div>';

		return $html;
	}
	}
}