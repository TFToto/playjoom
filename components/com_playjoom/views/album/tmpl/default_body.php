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
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$app	    = JFactory::getApplication();

JPluginHelper::importPlugin('playjoom');
$dispatcher	= JDispatcher::getInstance();

//Load JavaScripts for light box
JHtml::_('behavior.modal');

//Load PlayJoom Slider
JLoader::import( 'helpers.pjslider', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

// add style sheet
if ($this->params->get('css_type', 'pj_css') == 'pj_css') {
	$document	= JFactory::getDocument();
    $document->addStyleSheet(JURI::base(true).'/components/com_playjoom/assets/css/slider.css');
}
$modal_add2playlist_config = "{handler: 'iframe', size: {x: 550, y: 180}}";
echo '<div class="row">';
echo JHtml::_('PlayJoomSliders.startAccordion', 'moduleOptions', array('active' => null));

  foreach($this->items as $i => $item) {

	switch($item->hits) {
		case 0 :
			$played = JText::_("COM_PLAYJOOM_ALBUM_ALBUM_PLAYED_NEVER");
			break;
		case 1 :
			$played = JText::_("COM_PLAYJOOM_ALBUM_ALBUM_PLAYED_ONCE");
			break;
		default :
			$played = $item->hits.' '.JText::_("COM_PLAYJOOM_ALBUM_ALBUM_PLAYED");
			break;
	}
	//Content for details box
	$content_left =  '<div class="details_left">'.
                        '<h4 class="subheader">'.JText::_("COM_PLAYJOOM_ALBUM_TRACKDETAILS").'</h4>'.
                        '<ul class="trackdetails_list">'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_ARTIST").'<br />'.$item->artist.'</li>'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_ALBUM").'<br />'.$item->album.'</li>'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_YEAR").' '.$item->year.'</li>'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_GENRE").' '.$item->category.'</li>'.
	                       '<li>'.$played.'</li>'.
                        '</ul>'.
                      '</div>';
	$content_right = '<div class="details_right">'.
                        '<h4 class="subheader">'.JText::_("COM_PLAYJOOM_ALBUM_FILEDETAILS").'</h4>'.
                        '<ul class="trackdetails_list">'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_SIZE").' '.PlayJoomHelper::ByteValue($item->filesize).' '.PlayJoomHelper::UnitValue($item->filesize).'Byte</li>'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_MEDIATYPE").' '.$item->mediatype.'</li>'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_BITRATE").' '.$item->bit_rate / 1000 .' KBit/s</li>'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_SAMPLERATE").' '.$item->sample_rate / 1000 .' KHz</li>'.
                           '<li>'.JText::_("COM_PLAYJOOM_ALBUM_CHANNELMODE").' '.ucfirst($item->channelmode).'</li>'.
                           '<li>'.$item->channels . ' '.JText::_("COM_PLAYJOOM_ALBUM_CHANNELS").'</li>'.
                        '</ul>'.
                      '</div>';

	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

		//create artist string
        $TitleLenght = strlen($item->title);

        if ($TitleLenght > 40) {
           	$TitleName = substr($item->title,0, 37).'...';
        } else {
           	$TitleName = $item->title;
        }

        //Plugins integration
	    $this->events = new stdClass();

	    $results = $dispatcher->trigger('onInTrackbox', array($item, $this->params));
	    $this->events->InTrackbox = trim(implode("\n", $results));

        $results = $dispatcher->trigger('onPrepareTrackLink', array(&$item, $this->params, $item->title));
	    $this->events->PrepareTrackLink = trim(implode("\n", $results));

	    $results = $dispatcher->trigger('onBeforeTrackLink', array(&$item, $this->params));
	    $this->events->BeforeTrackLink = trim(implode("\n", $results));

	    $results = $dispatcher->trigger('onAfterTrackLink', array(&$item, $this->params));
	    $this->events->AfterTrackLink = trim(implode("\n", $results));

		//Check for Trackcontrol
		if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false) {
	    	$TitleLink = $item->title;
	    } else {
	    	$TitleLink = $this->events->PrepareTrackLink;
	    }

	    $track_text = '<span class="trackno">'.sprintf ("%02d", $item->tracknumber).'</span> - '.$this->events->BeforeTrackLink.'<span class="tracktitle">'.$TitleLink.'</span>&nbsp;<span class="trackminutes">['.PlayJoomHelper::Playtime($item->length).' '.JText::_('COM_PLAYJOOM_ALBUM_MINUTES_SHORT').']</span>'.$this->events->AfterTrackLink.' <span class="add2playlist"><a href="index.php?option=com_playjoom&amp;view=addtoplaylist&amp;layout=modal&amp;tmpl=component&amp;id='.$item->id.'" class="modal" style="margin-left: 45px;" rel="'.$modal_add2playlist_config.'">'.JText::_('COM_PLAYJOOM_ALBUM_ADD2PLAYLIST').'</span></a>';

	             echo '<div class="title">';
	                  echo JHtml::_('PlayJoomSliders.addSlide', 'moduleOptions', $track_text, JText::_('COM_PLAYJOOM_ALBUM_MORE_INFO'), 'collapse' . $i++, '');
	            echo '</div>';
	             echo '<div class="accordion-content">';
	                  echo $content_left;
	                  echo $content_right;
	                  echo $this->events->InTrackbox;
	             echo '</div>';

        echo JHtml::_('PlayJoomSliders.endSlide');
	}
}
echo JHtml::_('PlayJoomSliders.endAccordion');
echo '</div>';