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
 * Trackcontrol plugin.
 *
 * @package		PlayJoom
 * @subpackage	plg_trackcontrol
 */
class plgPlayjoomTrackcontrol extends JPlugin
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

		$this->rootURL = rtrim(JURI::base(),'/');
		$subpathURL = JURI::base(true);
		if(!empty($subpathURL) && ($subpathURL != '/')) {
			$this->rootURL = substr($this->rootURL, 0, -1 * strlen($subpathURL));
		}

	}

    public function onPrepareTrackLink(&$item, $params=null, $TitleName=null, $Request='site')	{

		$html = null;
		$session = JFactory::getSession();

		//If the event call comes from the albumviewer, then the $TitleName haven´t null
        if ($TitleName != null) {
        	$TrackTitle = $TitleName;
        } else {
        	if (isset($item->title)) {
        		$TrackTitle = $item->title;
        	} else {
        		return null;
        	}
        }
        if ($Request == 'admin') {
        	$html .= '<a href="'.$this->rootURL.JRoute::_('index.php?option=com_playjoom&task=audiotrack.edit&id='.$item->id).'">'.$TrackTitle.'</a>';
        } else {
        	//$html .= '<a href="'.$this->rootURL.JRoute::_('/components/com_playjoom/broadcasthandler/index.php?id='.$item->id,true).'&track.mp3" title="'.JText::_( 'PLG_PLAYJOOM_TRACKCONTROL_PLAYTITLE' ).'" target="_blank" class="direct_link">'.$TrackTitle.'</a>';
        	$html .= '<a href="'.$this->rootURL.JRoute::_('index.php?option=com_playjoom&view=broadcast&format=raw&tlk='.hash('sha256',$session->getId().'+'.PlayJoomHelper::getUserIP().'+'.$item->id).'&id='.$item->id,true).'&track.mp3" title="'.JText::_( 'PLG_PLAYJOOM_TRACKCONTROL_PLAYTITLE' ).'" target="_blank" class="direct_link">'.$TrackTitle.'</a>';
        }
		return $html;
	}

    public function onBeforeTrackLink(&$item, $params) {

    	$html = null;

		return $html;
	}

    public function onAfterTrackLink(&$item, $params) {

		$html = null;
		//Create downlaod button
		if ($params->get('download_active', 0) == 1) {

			$html .= '&nbsp;|&nbsp;<a href="'.JURI::root(false).'index.php?option=com_playjoom&view=broadcast&id='.$item->id.'&disposition=attachment" title="'.JText::_( 'PLG_PLAYJOOM_TRACKCONTROL_DOWNLOAD' ).'" target="_blank" class="direct_link"><img src="'.JURI::root(true).'/plugins/playjoom/trackcontrol/images/disk.png" alt="'.JText::_( 'PLG_PLAYJOOM_TRACKCONTROL_DOWNLOAD' ).'" /></a>';
		}

		return $html;
	}
}
