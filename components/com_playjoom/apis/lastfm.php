<?php
/**
 * Contains API methods for PlayJoom lastFM.
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

// No direct access to this file
defined('_JEXEC') or die;

/**
 * Contains API methods for PlayJoom lastFM
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.apis
 */
abstract class PlayJoomLastfmHelper {

	public static function CheckLastfmContent($Type, $About) {

		$GetConf = PlayJoomHelper::getConfig('LastFM',JPATH_COMPONENT.'/apis/lastfm.conf.php');
		$Url = $GetConf->get('Url');
		$Methods = PlayJoomLastfmHelper::LanguageMethod($Type, $About);

		if ($Methods && $Url){
			$Xml = simplexml_load_file($Url.'?method='.$Methods, 'SimpleXMLElement',LIBXML_NOCDATA);
		}
		else{
			$Xml = null;
		}

        if (PlayJoomLastfmHelper::getXmlStructure($Type, $Xml) != '') {
        	return true;
        }
        else {
        	return false;
        }
	}

    public static function GetLastfmContent($Type, $About=array()) {

    	$GetConf = PlayJoomHelper::getConfig('LastFM',JPATH_COMPONENT.'/apis/lastfm.conf.php');
    	$Url = $GetConf->get('Url');
    	$Methods = PlayJoomLastfmHelper::LanguageMethod($Type, $About);
    	$Xml = simplexml_load_file($Url.'?method='.$Methods, 'SimpleXMLElement',LIBXML_NOCDATA);

    	return $Xml;

	}

    public static function getAPIMethod($MethodType) {

    	switch($MethodType) {
			case 'artist':
    			return 'artist.getInfo';
    	    break;
    	    case 'artistsearch':
    			return 'artist.search';
    	    break;
			case 'album':
			    return 'album.getinfo';
			break;
			case 'albumsearch':
    			return 'album.search';
    	    break;
		}
	}

	public static function getXmlStructure($MethodType, $Xml) {

    	if ($Xml && $MethodType) {
    		switch($MethodType) {
    			case 'artist':
    				return $Xml->artist->bio->content;
    	        break;
			    case 'album':
			        return $Xml->album->wiki->content;
			    break;
			    default:
				    return null;
			    break;
    		}
		}
	}

	public static function LanguageMethod($Type, $About=array()) {

		$dispatcher	= JDispatcher::getInstance();
		$XmlStructure = null;

		//Get language
		$lang = JFactory::getLanguage();
        $localset = $lang->getLocale();
        $dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Localset for language is '.$localset['4'].'.', 'priority' => JLog::INFO, 'section' => 'site')));

		//Get config for LastFM API
		$GetConf = PlayJoomHelper::getConfig('LastFM',JPATH_COMPONENT.'/playjoom.conf.php');
		$Url = $GetConf->get('lastFM_Url');

		//Set values
		$AboutAlbum = base64_decode($About['album']);
		$AboutArtist = base64_decode($About['artist']);

		$Methods = PlayJoomLastfmHelper::getAPIMethod($Type).
		           '&api_key='.$GetConf->get('lastFM_ApiKey').
		           '&artist='.urlencode($AboutArtist).
		           '&album='.urlencode($AboutAlbum).
		           '&lang='.$localset['4'];

		if ($Methods && $Url){

			$Xml = new DomDocument();
            $Xml->preserveWhiteSpace = false;
            $Xml->formatOutput   = true;

		    if (@$Xml->load($Url.'?method='.$Methods) === false) {
		    	$Xml = null;
		    	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No valid xml content for method '.$Methods.' $Xml=null', 'priority' => JLog::WARNING, 'section' => 'site')));
		    } else {
              	$Xml = simplexml_load_file($Url.'?method='.$Methods, 'SimpleXMLElement',LIBXML_NOCDATA);
               	$XmlStructure = PlayJoomLastfmHelper::getXmlStructure($Type, $Xml);
            }
		}
        else {
        	$XmlStructure = null;
        }

		if ($XmlStructure != '') {
        	return $Methods;
        }
        else {
        	$Methods = PlayJoomLastfmHelper::getAPIMethod($Type).
        	           '&api_key='.$GetConf->get('lastFM_ApiKey').
        	           '&artist='.urlencode($AboutArtist).
        	           '&album='.urlencode($AboutAlbum);

        	if ($Methods && $Url){

        		$Xml = new DomDocument();
                $Xml->preserveWhiteSpace = false;
                $Xml->formatOutput   = true;
                $Xml->validateOnParse = true;
                if (@$Xml->load($Url.'?method='.$Methods) === false) {
                	$Xml = null;
                }
                else {
                	$Xml = simplexml_load_file($Url.'?method='.$Methods, 'SimpleXMLElement',LIBXML_NOCDATA);
                	$XmlStructure = PlayJoomLastfmHelper::getXmlStructure($Type, $Xml);
                }

        	}
        	else {
        		$XmlStructure = null;
        	}

        	if ($XmlStructure) {
        		return $Methods;
        	}
        	else {
        		return false;
        	}
        }
	}
}