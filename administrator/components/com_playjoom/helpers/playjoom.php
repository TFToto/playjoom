<?php
/**
 * @package Joomla 1.6.x
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
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
defined('_JEXEC') or die;

/**
 * PlayJoom component helper.
 */
class PlayJoomHelper {

	public static $extension = 'com_playjoom';

	public static function getOptions($value) {

        	//Get User Objects
        	$user	= JFactory::getUser();

        	$db		= JFactory::getDbo();
		    $query	= $db->getQuery(true);

            switch($value) {
		                case 'artist' :
		                               $query->select('artist As value, artist As text');
		                               $query->order('a.artist');
		                               $query->group('a.artist');
		                break;
		                case 'album' :
		                               $query->select('album As value, album As text');
		                               $query->order('a.album');
		                               $query->group('a.album');
		                break;
		                case 'year' :
		                               $query->select('year As value, year As text');
		                               $query->order('a.year DESC');
		                               $query->group('a.year DESC');
		                break;
		                case 'genre' :
		                	$query->select('catid As value');
		                	$query->group('a.catid');

		                	// Join over the categories.
		                	$query->select('c.title AS text');
		                	$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		                break;

		                default :
				            return JText::_( 'TCE_PLG_ERROR_NO_VALUE_FOR_getOptions_FUNCTION' );
		               }

		               // Filter by User.
		               if (JAccess::check($user->get('id'), 'core.admin') != 1) {

		               	  $query->where('a.add_by = '.$user->get('id'));
		               }

		    $query->from('#__jpaudiotracks AS a');

		    // Get the options.
		    $db->setQuery($query);

		    $options = $db->loadObjectList();

		    // Check for a database error.
		    if ($db->getErrorNum()) {
			    JError::raiseWarning(500, $db->getErrorMsg());
		    }

		    return $options;
        }


        public static function getOptionsForAlbumsView($value)
        {

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
        switch($value) {
		                case 'artist' :
		                               $query->select('artist As value, artist As text');
		                               $query->order('a.artist');
		                               $query->group('a.artist');
		                break;
		                default :
		                	     return JText::_( 'TCE_PLG_ERROR_NO_VALUE_FOR_getOptions_FUNCTION' );
		               }

		$query->from('#__jpalbums AS a');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
        }

        public static function Playtime ($playtimeseconds)
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

        public static function ByteValue($size)
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

        public static function UnitValue($size) {

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
        /**
         * Configure the Linkbar.
         */
        public static function addSubmenu($vName)
        {
                JHtmlSidebar::addEntry(
                    JText::_('COM_PLAYJOOM_SUBMENU_CPANEL'),
                    'index.php?option=com_playjoom&view=cpanel',
                    $vName == 'cpanel'
                );

                JHtmlSidebar::addEntry(
                    JText::_('COM_PLAYJOOM_SUBMENU_AUDIOTRACKS'),
                    'index.php?option=com_playjoom&view=audiotracks',
                    $vName == 'audiotracks'
                );

                JHtmlSidebar::addEntry(
                    JText::_('COM_PLAYJOOM_SUBMENU_ARTISTS'),
                    'index.php?option=com_playjoom&view=artists',
                    $vName == 'artists'
                );

                JHtmlSidebar::addEntry(
                    JText::_('COM_PLAYJOOM_SUBMENU_ALBUMS'),
                    'index.php?option=com_playjoom&view=albums',
                    $vName == 'albums'
                );

                JHtmlSidebar::addEntry(
                    JText::_('COM_PLAYJOOM_SUBMENU_COVERS'),
                    'index.php?option=com_playjoom&view=covers',
                    $vName == 'covers'
                );

                JHtmlSidebar::addEntry(
                    JText::_('COM_PLAYJOOM_SUBMENU_CATEGORIES'),
                    'index.php?option=com_categories&view=categories&extension=com_playjoom',
                    $vName == 'categories'
                );

                $extension = JRequest::getString('extension');
                JHtmlSidebar::addEntry(
                	JText::_('COM_PLAYJOOM_SUBMENU_PLAYLIST_CATEGORIES'),
                	'index.php?option=com_categories&view=categories&extension=com_playjoom.playlist',
                	$vName== 'playlistcategories' || $extension == 'com_playjoom.playlist'
                );

                $extension = JRequest::getString('extension');
                JHtmlSidebar::addEntry(
                JText::_('COM_PLAYJOOM_SUBMENU_TRACKFILTER_CATEGORIES'),
                'index.php?option=com_categories&view=categories&extension=com_playjoom.trackfilter',
                $vName== 'trackfiltercategories' || $extension == 'com_playjoom.trackfilter'
                		);

                // set some global property
                $document = JFactory::getDocument();

                if ($vName == 'audiotracks')
                {
                        $document->addStyleDeclaration('.icon-48-audiotracks {background-image: url(components/com_playjoom/images/header/icon-48-tracks-managment.gif);}');
                }
                if ($vName == 'artists')
                {
                        $document->addStyleDeclaration('.icon-48-artists {background-image: url(components/com_playjoom/images/header/icon-48-artists-managment.gif);}');
                }
                if ($vName == 'albums')
                {
                        $document->addStyleDeclaration('.icon-48-albums {background-image: url(components/com_playjoom/images/header/icon-48-albums-managment.gif);}');
                }
                if ($vName == 'covers')
                {
                        $document->addStyleDeclaration('.icon-48-covers {background-image: url(components/com_playjoom/images/header/icon-48-cover-managment.gif);}');
                }
                if ($vName == 'categories')
                {
                        $document->setTitle(JText::_('COM_PLAYJOOM_ADMINISTRATION_CATEGORIES'));
                }
                if ($vName == 'playlistcategories')
                {
                	$document->setTitle(JText::_('COM_PLAYJOOM_ADMINISTRATION_PLAYLIST_CATEGORIES'));
                }
                else {
                	$document->addStyleDeclaration('.icon-48-playjoom {background-image: url(components/com_playjoom/images/header/icon-48-playjoom-logo-a.gif);}');
                }
        }
        /**
         * Get the actions
         */
        public static function getActions($messageId = null)
        {
                $user  = JFactory::getUser();
                $result        = new JObject;

                $assetName = 'com_playjoom';

                $actions = array(
                        'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
                );

                foreach ($actions as $action) {
                        $result->set($action,        $user->authorise($action, $assetName));
                }

                return $result;
        }

        public static function getCoverThumb($item, $ImageSRC, $standart_img_width=100) {

        	$coverblob = null;
        	$dispatcher	= JDispatcher::getInstance();

            if ($item->data != ''
             && $item->data != null
             && extension_loaded('gd')) {

               if(!$item->width || !$item->height) {
            		$short_img_height = 100;
            	} else {
            		//Calculate the smaller cover values
                    if ($item->width > $item->height) {
                    	$ratio = $item->width / $item->height;
                        $short_img_height = $standart_img_width / $ratio;
                    } else {
                    	$ratio = $item->height / $item->width;
                        $short_img_height = $standart_img_width / $ratio;
                    }
            	}

                //Create the cover file
                $coverblob = $item->data;

	            //Create Cover blob for thumbnail
		        $src_img = @imagecreatefromstring($coverblob);

		        ob_start();

                switch ($item->mime) {
                	case "image/jpeg" :
                		ob_start();
    		    		imagejpeg($src_img);
    		    		$tmp_img = ob_get_contents();
    				    $CoverThumb = 'data:image/jpg;base64,'.base64_encode($tmp_img);
		            break;
		            case "image/jpg" :
		    		    ob_start();
    		    		imagejpeg($src_img);
    		    		$tmp_img = ob_get_contents();
    				    $CoverThumb = 'data:image/jpg;base64,'.base64_encode($tmp_img);
		            break;
		            case "image/gif" :
		                ob_start();
    		    		imagejpeg($src_img);
    		    		$tmp_img = ob_get_contents();
    				    $CoverThumb = 'data:image/jpg;base64,'.base64_encode($tmp_img);
		            break;
		            case "image/png" :
			            ob_start();
    		    		imagejpeg($src_img);
    		    		$tmp_img = ob_get_contents();
    				    $CoverThumb = 'data:image/jpg;base64,'.base64_encode($tmp_img);
		            break;
                    default:
		                'MISSING COVER IMAGE TYPE';
		                return null;
	            }
	            ob_get_clean();

		        // Clean up temp images
    		    imagedestroy($src_img);
    		    imagedestroy($tmp_img);
    		    imagedestroy($dest_img);
    		    ob_end_clean();

                //Output the cover thumb
                return '<img src="'.$CoverThumb.'" width="'.$standart_img_width.'" height="'.round($short_img_height).'" alt="cover" class="cover"> ';
            }
            else {
            	return null;
            }
	}

        public static function getAlbumCover($coverid) {

        	$db = JFactory::getDBO();

    	    $query = $db->getQuery(true);
            $query->select('album,width,height,mime,data');
            $query->from('#__jpcoverblobs');
            $query->where('id = "'.$coverid. '"');

            $db->setQuery($query);

            return $db->loadObject();
        }

	    public static function getSessionID() {

		$session =& JFactory::getSession(); //Will require it for session support
        $currentSession = JSession::getInstance('none',array()); //get currently session ID
        return $currentSession->getId();
	    }

	    public static function CheckForCoverentrie($albumname) {
	    	$db = JFactory::getDBO();
            $query = $db->getQuery(true);
    	    $query->select('COUNT(*) as counter');
            $query->from('#__jpcoverblobs');
            $query->where('album = "'.$albumname. '"');

            $db->setQuery($query);
            $result = $db->loadObject();

            return $result->counter;
	    }

	    public static function GetInstallInfo ($xml_value, $xml_source) {
	    	//Function to get the component info part from the xml file
	    	jimport( 'joomla.utilities.simplexml' );
	    	$xmlfile = PLAYJOOM_ADMINPATH.DIRECTORY_SEPARATOR.$xml_source;

	    	if (file_exists($xmlfile))
	    	{
	    		if ($data = JApplicationHelper::parseXMLInstallFile($xmlfile)) {

	    			foreach($data as $key => $value) {
	    				if (!isset($row)) {
							$row = new stdClass();
						}
						$row->$key = $value;
					}
	    		}
	    	}
	    	return $row->$xml_value;
	    }

	    public static function GetAvailableVersion () {
	     	//get info about release version
	        $install_version_nr = str_replace('.', '', PlayJoomHelper::GetInstallInfo("version","playjoom.xml"));

            $get_versionfile = 'http://files.teglo.info/playjoom/release_pj_ver.txt';
            $open_versionfile = @fopen($get_versionfile,'r');
            $release_version = @fread($open_versionfile,16);
            @fclose($open_versionfile);
            $release_version_nr = str_replace('.', '', $release_version);

            if ($release_version_nr / $install_version_nr < 1
             && $release_version_nr / $install_version_nr > 0)
            {
            	return '<span style="color:#0000FF">'.$release_version.'<br><img src="components/com_playjoom/images/icon-32-check_blue.png" width="32" height="32" alt="check button blue" border="0">&nbsp;'.JText::_('COM_PLAYJOOM_VERSION_BETA').'</span>';
            }
            elseif ($release_version_nr / $install_version_nr > 1)
            {
            	return '<span style="color:#FF0000">'.$release_version.'<br><img src="components/com_playjoom/images/icon-32-check_red.png" width="32" height="32" alt="check button red" border="0">&nbsp;'.JText::_('COM_PLAYJOOM_VERSION_NOTE').'</span>';
            }
            elseif ($release_version_nr / $install_version_nr == 1)
            {
            	return '<span style="color:#00AA00">'.$release_version.'<br><img src="components/com_playjoom/images/icon-32-check_green.png" width="32" height="32" alt="check button green" border="0">'.JText::_('COM_PLAYJOOM_VERSION_CURR').'</span>';
            }
            //If version check not online or available
            else {
            	return JText::_('COM_PLAYJOOM_VERSION_ERR');
            }
	    }

        public static function filterText($text) {
		// Filter settings
		jimport('joomla.application.component.helper');
		$config		= JComponentHelper::getParams('com_content');
		$user		= JFactory::getUser();
		$userGroups	= JAccess::getGroupsByUser($user->get('id'));

		$filters = $config->get('filters');

		$blackListTags			= array();
		$blackListAttributes	= array();

		$whiteListTags			= array();
		$whiteListAttributes	= array();

		$noHtml		= false;
		$whiteList	= false;
		$blackList	= false;
		$unfiltered	= false;

		// Cycle through each of the user groups the user is in.
		// Remember they are include in the Public group as well.
		foreach ($userGroups AS $groupId)
		{
			// May have added a group by not saved the filters.
			if (!isset($filters->$groupId)) {
				continue;
			}

			// Each group the user is in could have different filtering properties.
			$filterData = $filters->$groupId;
			$filterType	= strtoupper($filterData->filter_type);

			if ($filterType == 'NH') {
				// Maximum HTML filtering.
				$noHtml = true;
			}
			else if ($filterType == 'NONE') {
				// No HTML filtering.
				$unfiltered = true;
			}
			else {
				// Black or white list.
				// Preprocess the tags and attributes.
				$tags			= explode(',', $filterData->filter_tags);
				$attributes		= explode(',', $filterData->filter_attributes);
				$tempTags		= array();
				$tempAttributes	= array();

				foreach ($tags AS $tag)
				{
					$tag = trim($tag);

					if ($tag) {
						$tempTags[] = $tag;
					}
				}

				foreach ($attributes AS $attribute)
				{
					$attribute = trim($attribute);

					if ($attribute) {
						$tempAttributes[] = $attribute;
					}
				}

				// Collect the black or white list tags and attributes.
				// Each list is cummulative.
				if ($filterType == 'BL') {
					$blackList				= true;
					$blackListTags			= array_merge($blackListTags, $tempTags);
					$blackListAttributes	= array_merge($blackListAttributes, $tempAttributes);
				}
				else if ($filterType == 'WL') {
					$whiteList				= true;
					$whiteListTags			= array_merge($whiteListTags, $tempTags);
					$whiteListAttributes	= array_merge($whiteListAttributes, $tempAttributes);
				}
			}
		}

		// Remove duplicates before processing (because the black list uses both sets of arrays).
		$blackListTags			= array_unique($blackListTags);
		$blackListAttributes	= array_unique($blackListAttributes);
		$whiteListTags			= array_unique($whiteListTags);
		$whiteListAttributes	= array_unique($whiteListAttributes);

		// Unfiltered assumes first priority.
		if ($unfiltered) {
			$filter = JFilterInput::getInstance(array(), array(), 1, 1, 0);
		}
		// Black lists take second precedence.
		else if ($blackList) {
			// Remove the white-listed attributes from the black-list.
			$filter = JFilterInput::getInstance(
				array_diff($blackListTags, $whiteListTags), 			// blacklisted tags
				array_diff($blackListAttributes, $whiteListAttributes), // blacklisted attributes
				1,														// blacklist tags
				1														// blacklist attributes
			);
		}
		// White lists take third precedence.
		else if ($whiteList) {
			$filter	= JFilterInput::getInstance($whiteListTags, $whiteListAttributes, 0, 0, 0);  // turn off xss auto clean
		}
		// No HTML takes last place.
		else {
			$filter = JFilterInput::getInstance();
		}

		$text = $filter->clean($text, 'html');

		return $text;
	    }

	    /**
	     * Method for to check if a Configured folder is avaible and writeable
	     *
	     * This Method is for to check wheather the configured folder in the PalyJoom seetings is avaible and writeable.
	     * Otherwise it will returns the standard folder path of the PalyJoom installation.
	     *
	     * @param string $path
	     * @return string $path
	     */
        public static function checkFolderPath($path) {

        	$dispatcher	= JDispatcher::getInstance();

        	if (!is_dir($path)) {
        		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Configured path ('.$path.') is not available. Set on standard path: '.JPATH_SITE, 'priority' => JLog::WARNING, 'section' => 'admin')));
        		return JPATH_SITE;
        	} else {
        		if (!is_writable($path)) {
        			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Configured path ('.$path.') is not writeable. Set on standard path: '.JPATH_SITE, 'priority' => JLog::WARNING, 'section' => 'admin')));
        			return JPATH_SITE;
        		} else {
        			return $path;
        		}
        	}
        }

        /**
         * Method for create a path for PlayJoom functions
         *
         * @return string absolute path definition for PlayJoom.
         */
	    public static function getPJPath() {

	    	$params     = JComponentHelper::getParams('com_playjoom');
	    	$dispatcher	= JDispatcher::getInstance();

	    	// Get the user objects
	    	$user = JFactory::getUser();

	    	$path = $params->get('file_path', JPATH_SITE);
	    	//$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Set path '.$path, 'priority' => JLog::INFO, 'section' => 'admin')));

	    	// Set the path definitions
	    	if ($params->get('user_folder', 0) == 1 && !JAccess::check($user->get('id'), 'core.admin') == 1) {

	    		$userbase = PlayJoomHelper::checkFolderPath($path);

	    		//Get name for user folder
	    		//Filter folder name for not allowed characters
	    		$filterArray = Array("/%/","/'/","/$/","/</","/>/","/\"/","/\*/","/&/","/=/");
	    		$replaceArray = Array(null,null,null,null,null,null,null,null,null);
	    		$UserFolder = preg_replace($filterArray , $replaceArray , $user->get('username'));

	    		//Check if user folder exists
	    		if (is_dir($userbase.DIRECTORY_SEPARATOR.$UserFolder)) {
	    			$PJpath = $params->get('file_path', JPATH_SITE).DIRECTORY_SEPARATOR.$UserFolder;
	    		} else {
	    			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'User folder does not exists for path: '.$path.DIRECTORY_SEPARATOR.$UserFolder, 'priority' => JLog::WARNING, 'section' => 'admin')));

	    			jimport('joomla.filesystem.file');
	    			jimport('joomla.filesystem.folder');

	    			JFolder::create($userbase.DIRECTORY_SEPARATOR.$UserFolder);
	    			$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
	    			JFile::write($userbase.DIRECTORY_SEPARATOR.$UserFolder . "/index.html", $data);

	    			if (!is_dir($userbase.DIRECTORY_SEPARATOR.$UserFolder)) {
	    				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'User folder can not create with path: '.$path.DIRECTORY_SEPARATOR.$UserFolder, 'priority' => JLog::WARNING, 'section' => 'admin')));
	    				$PJpath = null;
	    			} else {
	    				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'User folder create compete', 'priority' => JLog::INFO, 'section' => 'admin')));
	    				$PJpath = $userbase.DIRECTORY_SEPARATOR.$UserFolder;
	    			}
	    		}

	    	} else {
	    		$PJpath = PlayJoomHelper::checkFolderPath($path);
	    	}

	        return $PJpath;
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