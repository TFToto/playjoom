<?php
/**
 * Contains the general helper methods for the PlayJoom.
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
 * Contains the general helper methods for the PlayJoom.
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.helpers
 */
abstract class PlayJoomHelper {

	/**
	 * PlayJoom config value
	 *
	 * @var integer
	 */
	public static $PJconfig = null;

	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config) {

		parent::__construct($subject, $config);
	}

	public static function GetInstallInfo ($xml_value, $xml_source) {
		//Function to get the component info part from the xml file
		jimport( 'joomla.utilities.simplexml' );
		$xmlfile = JPATH_SITE .DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.$xml_source;

		if (file_exists($xmlfile))
		{
			if ($data = JApplicationHelper::parseXMLInstallFile($xmlfile))
			{
				foreach($data as $key => $value)
				{
					if (!isset($row)) {
						$row = new stdClass();
					}
					$row->$key = $value;
				}
			}
		}
		return $row->$xml_value;
	}

    public static function getConfig($namespace = null, $file = null) {

		if ($file === null || $namespace == null) {

			//Set standard config file
			$file = null;
		}
        self::$PJconfig = self::_createPJConfig($file, $namespace);

		return self::$PJconfig;
	}

	protected static function _createPJConfig($file, $namespace)
	{
		jimport('joomla.registry.registry');

		if (is_file($file)) {
			include_once $file;
		}

		// Create the registry with a default namespace of config
		$registry = new JRegistry();

		// Build the config name.
		$name = 'PJConfig'.$namespace;

		// Handle the PHP configuration type.
		if (class_exists($name)) {
			// Create the JConfig object
			$config = new $name();

			// Load the configuration values into the registry
			$registry->loadObject($config);
		}

		return $registry;
	}
	/**
	 * Method for to get the file extension
	 *
	 * @param string $mediatype like audio/mpeg
	 * @return string
	 */
	public static function getFileExtension($mediatype) {

		$dispatcher	= JDispatcher::getInstance();

		switch($mediatype) {
			case 'audio/mpeg':
				return 'mp3';
			break;
			case "image/jpeg" :
				return 'jpg';
			break;
			case "image/jpg" :
				return 'jpg';
			break;
			case "image/gif" :
				return 'gif';
			break;
			case "image/png" :
				return 'png';
			break;
			default:
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Missing or unknow mime type: '.$mediatype, 'priority' => JLog::ERROR, 'section' => 'site')));
				return null;
		}

	}


	public static function Playtime($playtimeseconds)
    {
	     $sign = (($playtimeseconds < 0) ? '-' : '');
         $playtimeseconds = abs($playtimeseconds);
	     $contentseconds = round((($playtimeseconds / 60) - floor($playtimeseconds / 60)) * 60);
	     $contentminutes = floor($playtimeseconds / 60);
	     if ($contentseconds >= 60)
	     {
		      $contentseconds -= 60;
		      $contentminutes++;
         }

     return $sign.intval($contentminutes).':'.str_pad($contentseconds, 2, 0, STR_PAD_LEFT);
    }

    static function ByteValue($size)
    {
    	switch (TRUE)
    	{
    		case ($size < 1024):
    			return number_format($size, 2, ',', '.');
    	    break;
    		case ($size >= 1024 and $size < 1048576):
    			return number_format($size / 1024, 2, ',', '.');
    	    break;
    		case ($size >= 1048576 and $size < 1073741824):
    			return number_format($size / 1024 / 1024, 2, ',', '.');
    		break;
    		default:
    			return number_format($size / 1024 / 1024 / 1024, 2, ',', '.');

    	}
    }

    static function UnitValue($size)
    {
    	switch (TRUE)
    	{
    		case ($size < 1024):
    			return null;
    	    break;
    		case ($size >= 1024 and $size < 1048576):
    			return 'K';
    	    break;
    		case ($size >= 1048576 and $size < 1073741824):
    			return 'M';
    		break;
    		default:
    			return 'G';

    	}
    }

    public static function getPlaylistEntries($playlist_id) {

    	$db = JFactory::getDBO();
        $query = $db->getQuery(true);
    	$query->select('COUNT(*) as counter');
        $query->from('#__jpplaylist_content AS c');
        $query->where('list_id = "'.$playlist_id.'"');

        $db->setQuery($query);
        $result = $db->loadObject();

        return $result->counter;
    }

    public static function getTrackInfo($track_id) {

    	$dispatcher	= JDispatcher::getInstance();

    	$db = JFactory::getDBO();

    	$query = $db->getQuery(true);
        $query->select('title,album,artist,pathatlocal,file');
        $query->from('#__jpaudiotracks');
        $query->where('id = "'.$track_id. '"');

        $db->setQuery($query);

        if($db->loadObject()){
        	return $db->loadObject();
        }
        else {
        	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No item exists for track id: '.$track_id.'!', 'priority' => JLog::WARNING, 'section' => 'site')));
        	return null;
        }

    }

    public static function getInfoButton($Type, $AboutInfo, $modal_config, $params) {

    	/*
         * - Check for additional artist infos
         * - and whether the button is active
         */

    	$AdditionalInfo = null;

        if ($params->get('info_source', 'owndb') == 'wiki'
          ||$params->get('info_source', 'owndb') == 'lastfm') {
    		$AdditionalInfo = PlayJoomHelper::checkInfoAbout($Type, $AboutInfo, $params);
    	}

    	if ($AdditionalInfo['album'] == true
          ||$AdditionalInfo['artist'] == true
          ||$AdditionalInfo['genre'] == true) {
        	return '<a href="index.php?option=com_playjoom&amp;view=infoabout&amp;layout=default&amp;tmpl=component&amp;infoabout&amp;type='.$Type.'&album='.base64_encode($AdditionalInfo['album']).'&artist='.base64_encode($AdditionalInfo['artist']).'&genre='.base64_encode($AdditionalInfo['genre']).'&source='.$AdditionalInfo['source'].'"
	                   class="modal"
	                   rel="'.$modal_config.'">
	                     <img src="'.JURI::base(true).'/components/com_playjoom/images/icons/information.png"
	                          class="info_icon"
	                          alt="Info about"
	                          title="'.JText::_('COM_PLAYJOOM_ABLUM_MOREINFO_ABOUT_'.$Type).'">
	                </a>';
        }
        else {
        	return null;
        }
    }

    public static function checkInfoAbout($type, $AboutInfo, $params) {

    	if ($params->get('info_source', 'owndb') == 'wiki') {
    		require_once JPATH_COMPONENT.'/apis/wiki.php';
    	}
        if ($params->get('info_source', 'owndb') == 'lastfm') {
    		require_once JPATH_COMPONENT.'/apis/lastfm.php';
    	}

    	$db = JFactory::getDBO();

    	$query = $db->getQuery(true);

        switch ($type)
    	{
    		case "album" :

    			 $About = base64_decode($AboutInfo['album']);
    			 $query->select('title,album_release,label,production,infotxt');
                 $query->from('#__jpalbums');
                 $query->where('title = "'.$About. '"');

                 $db->setQuery($query);
                 $checkResult = $db->loadObject();

                 if($db->loadObject() && $checkResult->infotxt != '')
                 {
                 	return array('source' => 'db', 'album' => $About, 'artist' => null, 'genre' => null);
                 }
                 else {

                 	/*
                 	 * wiki source
                 	 */
                 	if ($params->get('info_source', 'owndb') == 'wiki') {
                 		$PageID = PlayJoomWikiHelper::CheckWikiContent($About,'de');

                        if ($PageID['pageid'] != '') {
                        	return array('source' => 'wiki', 'album' => $PageID['pageid'], 'artist' => null, 'genre' => null);
                    	}
                        else {
                        	return array('source' => null, 'artist' => null, 'album' => null, 'genre' => null);
                        }
                    }
                    /*
                     * lastfm source
                     */
                    else if ($params->get('info_source', 'owndb') == 'lastfm') {
                        $ContentExist = PlayJoomLastfmHelper::CheckLastfmContent($type, $AboutInfo);

                        if ($ContentExist == true) {
                        	return array('source' => 'lastfm', 'album' => $About, 'artist' => base64_decode($AboutInfo['artist']), 'genre' => null);
                    	}
                        else {
                        	return array('source' => null, 'artist' => null, 'album' => null, 'genre' => null);
                        }
                    }
                 }
    	    break;

    	    case "artist" :

    	    	 $About = base64_decode($AboutInfo['artist']);

    			 $query->select('name,formation,members,infotxt');
                 $query->from('#__jpartists');
                 $query->where('name = "'.$About. '"');

                 $db->setQuery($query);
                 $checkResult = $db->loadObject();

                 if($db->loadObject() && $checkResult->infotxt != '')
                 {
                 	return array('source' => 'db', 'artist' => $About, 'album' => null, 'genre' => null);
                 }
                 else {
                 	/*
                 	 * wiki source
                 	 */
                 	if ($params->get('info_source', 'owndb') == 'wiki') {
                 		$PageID = PlayJoomWikiHelper::CheckWikiContent($About,'de');

                        if ($PageID['pageid'] != '') {
                        	return array('source' => 'wiki', 'artist' => $PageID['pageid'], 'album' => null, 'genre' => null);
                    	}
                        else {
                        	return array('source' => null, 'artist' => null, 'album' => null, 'genre' => null);
                        }
                 	}
                    /*
                     * lastfm source
                     */
                    else if ($params->get('info_source', 'owndb') == 'lastfm') {
                        $ContentExist = PlayJoomLastfmHelper::CheckLastfmContent($type, $AboutInfo);

                        if ($ContentExist == true) {
                        	return array('source' => 'lastfm', 'album' => $About, 'artist' => base64_decode($AboutInfo['artist']), 'genre' => null);
                    	}
                        else {
                        	return array('source' => null, 'artist' => null, 'album' => null, 'genre' => null);
                        }
                    }
                 }
    	    break;

    	    case "genre" :
    	    	 $About = base64_decode($AboutInfo['genre']);

    	    	 $query->select('title,description');
                 $query->from('#__categories');
                 $query->where('extension = "com_playjoom"');
                 $query->where('title = "'.$About. '"');

                 $db->setQuery($query);
                 $checkResult = $db->loadObject();

                 if($db->loadObject() && $checkResult->description != '') {
                 	 return array('source' => 'db', 'album' => null, 'artist' => null, 'genre' => $About);
                 }
                 else {
                 	/*
                 	 * wiki source
                 	 */
                 	if ($params->get('info_source', 'owndb') == 'wiki') {
                 		$PageID = PlayJoomWikiHelper::CheckWikiContent($About,'de');

                        if ($PageID['pageid'] != '') {
                        	return array('source' => 'wiki', 'value' => $PageID['pageid']);
                        }
                        else {
                        	return array('source' => null, 'artist' => null, 'album' => null, 'genre' => null);
                        }
                 	}
                    /*
                     * lastfm source
                     */
                    else if ($params->get('info_source', 'owndb') == 'lastfm') {

                    }
                 }
    	    break;

    	    case "track" :
    			 $query->select('title,description');
                 $query->from('#__jpaudiotracks');
                 $query->where('id = "'.$about. '"');

    	         $db->setQuery($query);
                 $checkResult = $db->loadObject();

                 if($db->loadObject() && $checkResult->description != '')
                 {
                 	return array('source' => 'db', 'value' => 'true');
                 }
                 else
                 {
                 	return null;
                 }
    	    break;

    		default:
    			return null;

    	}
    }

    public static function getAlbumCover($album, $artist=null)
    {
    	$dispatcher	= JDispatcher::getInstance();

    	$db = JFactory::getDBO();

    	$query = $db->getQuery(true);
        $query->select('cb.album, cb.artist, cb.width, cb.height, cb.mime, cb.data');
        $query->from('#__jpcoverblobs as cb');

        if (!PlayJoomHelper::checkForSampler($album, $artist)) {
        	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get cover data from database for values -> album: '.$album.', artist: '.$artist, 'priority' => JLog::INFO, 'section' => 'site')));
        	$query->where('(cb.album = "'.$album. '" AND cb.artist = "'.$artist. '")');
	    }
	    else {
	    	$query->where('cb.album = "'.$album. '"');
        }

        $db->setQuery($query);

        return $db->loadObject();
    }

    function getSessionID(){

		$session =& JFactory::getSession(); //Will require it for session support
        $currentSession = JSession::getInstance('none',array()); //get currently session ID
        return $currentSession->getId();
	}

    function countHit($track_id) {

    	$dispatcher	= JDispatcher::getInstance();

    	// Get UTC datetime for now.
		$dNow = new JDate;
		$DateTime = clone $dNow;

    	$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('hits');
		$query->from('#__jpaudiotracks');
		$query->where('id = ' . (int) $track_id);

		$db->setQuery($query);
		$result = $db->loadObject();

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Hits for track id: '.$result->hits, 'priority' => JLog::INFO, 'section' => 'site')));

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return false;
		}
		$newhits = $result->hits +1;

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Writing '.$newhits.' hits for track id: '.$track_id, 'priority' => JLog::INFO, 'section' => 'site')));
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Writing access datetime '.$DateTime.' for track id: '.$track_id, 'priority' => JLog::INFO, 'section' => 'site')));

        $obj = new stdClass();
        $obj->id = $track_id;
        $obj->hits = $newhits;
        $obj->access_datetime = $DateTime->format('Y-m-d H:i:s');

		$db->updateObject('#__jpaudiotracks', $obj, 'id', true);
    }


    /**
     * Method for checking a track which is of a sampler album or not.
     *
     * @param string $albumname
     * @param string $artistname
     * @return boolean
     */
    public static function checkForSampler($albumname, $artistname) {

		// Get a reference to the global cache object.
		$cache = JFactory::getCache('com_playjoom', '');
		$cache_sampler = base64_encode($albumname.$artistname);

		// Check the cached results.
		if (!($cache->get($cache_sampler))) {

			$db = JFactory::getDBO();

			$query = $db->getQuery(true);

			$query->select('SUM(s.album = "'.$albumname. '" AND s.artist = "'.$artistname. '") AS counterBoth, SUM(s.album = "'.$albumname. '") AS counterJustAlbum');
			$query->from('#__jpaudiotracks AS s');

			$db->setQuery($query);
			$result = $db->loadObject();

			if ($result->counterBoth <> $result->counterJustAlbum && $result->counterBoth <=1) {

				// Store the data in cache.
				$cache->store(true, $cache_sampler);

				return true;
			} else {

				// Store the data in cache.
				$cache->store(false, $cache_sampler);

				return false;
			}
		} else {
			return $cache->get($cache_sampler);
		}
	}

    public static function getUserOS()
    {
    	if (strstr($_SERVER['HTTP_USER_AGENT'],'Windows'))
    	{
    		$checkOS = 'windows';
        }

        elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Linux'))
        {
        	$checkOS = 'linux';
        }

        elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Mac'))
        {
        	if (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone'))
        	{
        		$checkOS = 'ios';
        	}
            elseif (strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))
            {
        	    $checkOS = 'ios';
            }
            elseif (strstr($_SERVER['HTTP_USER_AGENT'],'iPod'))
            {
        	    $checkOS = 'ios';
            }
        	else
        	{
        		$checkOS = 'osx';
        	}
        }

        elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Android'))
        {
        	$checkOS = 'android';
        }
        else
        {
        	$checkOS = $_SERVER['HTTP_USER_AGENT'];
        }

        return $checkOS;
    }

    public static function getUserBrowser() {

    	if (strstr($_SERVER['HTTP_USER_AGENT'],'Opera'))
    	{
    		$brows=ereg_replace(".+\(.+\) (Opera |v){0,1}([0-9,\.]+)[^0-9]*","Opera \\2",$_SERVER['HTTP_USER_AGENT']);
            if(ereg('^Opera/.*',$_SERVER['HTTP_USER_AGENT']))
            {
            	$brows=ereg_replace("Opera/([0-9,\.]+).*","Opera \\1",$_SERVER['HTTP_USER_AGENT']);
            }
    	}

    	elseif (strstr($_SERVER['HTTP_USER_AGENT'],'MSIE'))
        {
        	$brows=ereg_replace(".+\(.+MSIE ([0-9,\.]+).+","Internet Explorer \\1",$_SERVER['HTTP_USER_AGENT']);
        }

        elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Firefox'))
        {
        	$brows=ereg_replace(".+\(.+rv:.+\).+Firefox/(.*)","Firefox \\1",$_SERVER['HTTP_USER_AGENT']);
        }

        elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Mozilla'))
        {
        	$brows=ereg_replace(".+\(.+rv:([0-9,\.]+).+","Mozilla \\1",$_SERVER['HTTP_USER_AGENT']);
        }

        else
        {
        	$brows=$_SERVER['HTTP_USER_AGENT'];
        }

        return $brows;
    }

    public static function getUserIP() {

    	// Check for proxies as well.
        if (isset($_SERVER['REMOTE_ADDR'])) {
        	return $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        	return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
    	    return $_SERVER['HTTP_CLIENT_IP'];
        }
    }
}