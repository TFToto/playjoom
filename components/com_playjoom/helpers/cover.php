<?php
/**
 * Contains the helper methods for the PlayJoom Cover helper.
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
 * Contains the Helper Methods for the PlayJoom Cover
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.helpers
 */
class PlayJoomHelperCover {

	/**
	 * Helper constructor method
	 */
	public function __construct() {
		$this->params = JComponentHelper::getParams('com_playjoom');
	}
	/**
	 * Method for to get the file extension
	 *
	 * @param string $mediatype like audio/mpeg
	 * @return string File extension
	 */
	private function getFileExtension($mediatype) {

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
	/**
	 * Method for to get a file path for cover files
	 *
	 * @param bool $url flag whether the returned url should be a string as url
	 *
	 * @return string path value
	 */
	private function getFilePath($url=false) {

		$dispatcher	= JDispatcher::getInstance();

		$app    = JFactory::getApplication();
		$config = JFactory::getConfig();

		$config_image_path = $this->params->get('path_cover_files_tmp', $config->get('tmp_path'));

		//Check for cover directory
		if (!is_dir($config_image_path) || !is_writable($config_image_path)) {

			if ($this->params->get('user_folder', 0) == 1) {
				$user = JFactory::getUser();
				$PJcoverpath = $config->get('tmp_path').DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.$user->get('username').DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.JRequest::getVar('view');
			} else {
				$PJcoverpath = $config->get('tmp_path').DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.JRequest::getVar('view');
			}
		} else {
			if ($this->params->get('user_folder', 0) == 1) {
				$user = JFactory::getUser();
				$PJcoverpath = $config_image_path.DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.$user->get('username').DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.JRequest::getVar('view');
			} else {
				$PJcoverpath = $config_image_path.DIRECTORY_SEPARATOR.'com_playjoom'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.JRequest::getVar('view');
			}
		}

		$folders_arr = explode(DIRECTORY_SEPARATOR, $PJcoverpath);

		$folderpath = null;
		$folder_level = 0;

		foreach ($folders_arr as &$folder) {

			$folderpath .= DIRECTORY_SEPARATOR.$folder;

			//Delete the first directory separator if the host is a winodw system
			if ($folder_level == 0 && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
				$folderpath = substr($folderpath, 1);
			}
			
			if (!is_dir($folderpath)){

				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Create folder with path: '.$folderpath, 'priority' => JLog::INFO, 'section' => 'site')));

				mkdir($folderpath, 0700);
				$fp = fopen($folderpath.'/index.html', 'a');
				fwrite($fp, '<html><body bgcolor="#FFFFFF"></body></html>');
				fclose($fp);
			}
			
			$folder_level ++;
		}
		$path = $PJcoverpath;

		if ($url) {
			$pathlengh = strripos($path, JURI::base(true));
			$hostlengh = strlen(JURI::base(true));

			return JURI::base(false).substr($path, $pathlengh + $hostlengh);;
		} else {
			return $path;
		}
	}
	/**
	 * Method for to check whether the cover file exists
	 *
	 * @param string $path files path for covers
	 * @param string $coverfile  artistname+albumname as base64 decoded string.
	 * @param array  $params     PlayJoom configuration
	 *
	 * @return  boolean  Result of operation
	 */
	private function checkFileExists($path, $coverfile) {

    	$dispatcher	= JDispatcher::getInstance();

    	$LegalExtensions = explode(',', $this->params->get('upload_cover_extensions', 'jpg,jpeg,png,gif'));

    	foreach ($LegalExtensions as &$Extension) {
    		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Check file for cover: '.JApplication::stringURLSafe($coverfile).' with extension: '.$Extension, 'priority' => JLog::INFO, 'section' => 'site')));
    		if (file_exists($path.DIRECTORY_SEPARATOR.$coverfile.'.'.$Extension)) {
    			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Found file for cover: '.JApplication::stringURLSafe($coverfile).' with extension: '.$Extension, 'priority' => JLog::INFO, 'section' => 'site')));

    			//Check for right image size
    			if (extension_loaded('gd')) {
    				$ImageSize = getimagesize($path.DIRECTORY_SEPARATOR.$coverfile.'.'.$Extension);
    				if ($ImageSize[0] <> $this->params->get(JRequest::getVar('view').'_cover_size',100)) {
    					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Cover file exist but have the wrong size. File will be delete.', 'priority' => JLog::WARNING, 'section' => 'site')));
    					unlink($path.DIRECTORY_SEPARATOR.$coverfile.'.'.$Extension);
    					return false;
    				} else {
    					return $Extension;
    				}
    			}

    			return $Extension;
    		}
    	}
    	$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'File was not found for cover: '.JApplication::stringURLSafe($coverfile), 'priority' => JLog::WARNING, 'section' => 'site')));
    	return false;
    }
	/**
	 * Method for to make a image resampling
	 *
	 * @param array $coverdata Datas for creating the cover
	 * @param number $standart_img_width Width configuration for covers
	 *
	 * @return resource resampled image data
	 */
	private function ResampleImage($coverdata) {

		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Starting resample cover with '.$this->params->get(JRequest::getVar('view').'_cover_size',100).'px.', 'priority' => JLog::INFO, 'section' => 'site')));

		if(!$coverdata->width || !$coverdata->height) {
			$short_img_height = 100;
		} else {
			//Calculate the smaller cover values
			if ($coverdata->width > $coverdata->height) {
				$ratio = $coverdata->width / $coverdata->height;
				$short_img_height = $this->params->get(JRequest::getVar('view').'_cover_size',100) / $ratio;
			} else {
				$ratio = $coverdata->height / $coverdata->width;
				$short_img_height = $this->params->get(JRequest::getVar('view').'_cover_size',100) / $ratio;
			}
		}

		//Create Cover blob for thumbnail
		$src_img = @imagecreatefromstring($coverdata->data);

		//Create the thumbnail cover
		$dest_img = imageCreateTrueColor($this->params->get(JRequest::getVar('view').'_cover_size',100), round($short_img_height));

		//Resample the thumbnail cover
		if ($coverdata->height && $coverdata->width) {
			imageCopyResampled($dest_img, $src_img, 0, 0, 0 ,0, $this->params->get(JRequest::getVar('view').'_cover_size',100), round($short_img_height), $coverdata->width, $coverdata->height);
		}

		ob_start();

		switch ($coverdata->mime) {
			case "image/jpeg" :
				ob_start();
				imagejpeg($dest_img);
				$tmp_img = ob_get_contents();
				if ($this->params->get('save_cover_tmp', 0) == 0) {
					$imgdata = 'data:image/jpg;base64,'.base64_encode($tmp_img);
				} else {
					$imgdata = $tmp_img;
				}
				break;
			case "image/jpg" :
				ob_start();
				imagejpeg($dest_img);
				$tmp_img = ob_get_contents();
		        if ($this->params->get('save_cover_tmp', 0) == 0) {
					$imgdata = 'data:image/jpg;base64,'.base64_encode($tmp_img);
				} else {
					$imgdata = $tmp_img;
				}
				break;
			case "image/gif" :
				ob_start();
				imagejpeg($dest_img);
				$tmp_img = ob_get_contents();
		        if ($this->params->get('save_cover_tmp', 0) == 0) {
					$imgdata = 'data:image/gif;base64,'.base64_encode($tmp_img);
				} else {
					$imgdata = $tmp_img;
				}
				break;
			case "image/png" :
				ob_start();
				imagejpeg($dest_img);
				$tmp_img = ob_get_contents();
		        if ($this->params->get('save_cover_tmp', 0) == 0) {
					$imgdata = 'data:image/png;base64,'.base64_encode($tmp_img);
				} else {
					$imgdata = $tmp_img;
				}
				break;
			default:
				'MISSING COVER IMAGE TYPE';
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Missing or unknow mime type for cover img. mime: '.$coverdata->mime, 'priority' => JLog::ERROR, 'section' => 'site')));
				return null;
		}
		ob_get_clean();

		// Clean up temp images
		imagedestroy($src_img);
		imagedestroy($tmp_img);
		imagedestroy($dest_img);
		ob_end_clean();

		return $imgdata;
	}
	/**
	 * Method for to create a temporary cover file for PlayJoom viewers
	 *
	 * @param array  $coverdata          Datas for creating the cover
	 * @param string $path               The temporary path to the cover file
	 * @param string $filename           Name of the cover file
	 * @param number $standart_img_width Width configuration for covers
	 *
	 * @return void
	 */
	private function createCoverfile($coverdata, $path, $filename) {

		$dispatcher	= JDispatcher::getInstance();

		//Get file extension
		if (isset($coverdata->mime)) {
			$FileExtension = PlayJoomHelperCover::getFileExtension($coverdata->mime);
		}

		if($coverdata) {

			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Valid cover data found.', 'priority' => JLog::INFO, 'section' => 'site')));

			//write the cover file
			if ($filename && $path) {
				if ($tempimagefile = @fopen($path.DIRECTORY_SEPARATOR.$filename.'.'.$FileExtension, 'wb')) {

					if (extension_loaded('gd')) {

						$tmp_img = PlayJoomHelperCover::ResampleImage($coverdata);

						//Output the cover thumb
						fwrite($tempimagefile, $tmp_img);
						fclose($tempimagefile);

					} else {
						fwrite($tempimagefile, $coverdata->data);
						fclose($tempimagefile);
					}
				}
			}
		}
	}
	/**
	 * Method for to get an database object of cover data as array
	 *
	 * @param array $item Items about Name of the album, Name of the artist, etc.
	 * @param boolean If album a sampler then value is true.
	 *
	 * @return array database object
	 */
	private function getAlbumCoverDatas($item, $SamplerCheck=false) {

		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Get cover for id: '.print_r($item,true), 'priority' => JLog::WARNING, 'section' => 'site')));

		if (isset($item->cover_id) && $item->cover_id >= 1 && $item->cover_id != '') {

			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Ready to get Cover Datas.', 'priority' => JLog::INFO, 'section' => 'site')));

			$db = JFactory::getDBO();

			$query = $db->getQuery(true);

			$query->select('cb.album, cb.artist, cb.width, cb.height, cb.mime, cb.data');
			$query->from('#__jpcoverblobs as cb');

			$query->where('cb.id = '.$item->cover_id);

			$db->setQuery($query);
			return $db->loadObject();
		} else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No cover id.', 'priority' => JLog::WARNING, 'section' => 'site')));
			return null;
		}
	}
	/**
	* Method for to get an database object of cover data as array
	*
	* @param array $item Items about Name of the album, Name of the artist, etc.
	* @param boolean If album a sampler then value is true.
	*
	* @return array database object
	*/
	private function getAlbumCover($item, $SamplerCheck=false) {

		$dispatcher	= JDispatcher::getInstance();

		$coverdatas = self::getAlbumCoverDatas($item, $SamplerCheck=false);
		//$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Cover datas content: '.print_r($coverdatas,true), 'priority' => JLog::WARNING, 'section' => 'site')));

		if ($coverdatas != null) {
			return $coverdatas;
		} else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Cover datas, go to subsequently maintain.', 'priority' => JLog::WARNING, 'section' => 'site')));
			$new_cover_id = self::CoverIDSubsequentlyMaintain($item, $SamplerCheck=false);

			if ($new_cover_id) {
				//add missing cover id into $item array
				$new_cover_array = (array_merge((array)$item,(array)$new_cover_id));
				$new_cover_object = (object)$new_cover_array;
				$coverdatas = self::getAlbumCoverDatas($new_cover_object, $SamplerCheck=false);
			} else {
				return null;
			}

			if ($coverdatas) {
				return $coverdatas;
			} else {
				return null;
			}
		}
	}
	/**
	 * Method for calculate the image height with width as basis value
	 *
	 * @param integre $width
	 * @param integre $height
	 *
	 * @return integer calculated height value with correct image radio
	 */
	private function calcImageSize ($width, $height) {

		//Calculate the smaller cover values
		if ($width && $height) {
			if ($width > $height) {
				$ratio = $width / $height;
				return $this->params->get(JRequest::getVar('view').'_cover_size',100) / $ratio;
			} else {
				$ratio = $height / $width;
				return $this->params->get(JRequest::getVar('view').'_cover_size',100) / $ratio;
			}
		} else {
			return $this->params->get(JRequest::getVar('view').'_cover_size',100);
		}
	}
	/**
	 * Method for to subsequently maintain the missing cover checksum integer
	 *
	 * @param array $item album items like artist and albumname
	 * @param boolean $SamplerCheck
	 *
	 * @return boolean
	 */
	private function CoverIDSubsequentlyMaintain($item, $SamplerCheck=false) {

		$db = JFactory::getDBO();
		$dispatcher	= JDispatcher::getInstance();

		$query = $db->getQuery(true);

		$query->select('cb.id AS cover_id');
		$query->from('#__jpcoverblobs as cb');

		if (!$SamplerCheck) {
			//Request for no Sampler
			$query->where('(cb.album = "'.$item->album. '" AND cb.artist = "'.$item->artist. '")');
		} else {
			//Request for Sampler
			$query->where('cb.album = "'.$item->album. '"');
		}

		$db->setQuery($query);
		$cover = $db->loadObject();

		if ($cover) {

			//Get list tracks of an album
			$query->select('t.id');
			$query->from('#__jpaudiotracks as t');

			//Check for albumname as sampler
			if ($SamplerCheck) {
				$query->where('(t.album = "'.$item->album. '" AND t.artist = "'.$item->artist. '")');
			} else {
				$query->where('t.album = "'.$item->album. '"');
			}

			$db->setQuery($query);
			$tracklist = $db->loadObjectList();

			foreach($tracklist as $i => $albumitem) {
				$obj = new stdClass();
				$obj->id = $albumitem->id;
				$obj->coverid = $cover->cover_id;

				$db->updateObject('#__jpaudiotracks', $obj, 'id', true);
			}

			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Update missing cover IDs: '.$cover->cover_id, 'priority' => JLog::INFO, 'section' => 'site')));

			return $cover;
		} else {
			return null;
		}
	}
	/**
	 * Method for to get width and height values for a cover image
	 *
	 * @param string $path
	 * @param string $coverfile
	 *
	 * @return array
	 */
	private function getTmpCoverSize($cover_state, $path, $coverfile) {

		$dispatcher	= JDispatcher::getInstance();

		if (extension_loaded('gd') && $cover_state != null) {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Create image html tag for: '.$path.DIRECTORY_SEPARATOR.$coverfile.'.'.$cover_state, 'priority' => JLog::INFO, 'section' => 'site')));
			return getimagesize($path.DIRECTORY_SEPARATOR.$coverfile.'.'.$cover_state);
		} else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'no $cover_state value or disable GD libery!', 'priority' => JLog::WARNING, 'section' => 'site')));
			return array($this->params->get(JRequest::getVar('view').'_cover_size',100), $this->params->get(JRequest::getVar('view').'_cover_size',100));
		}

	}
	/**
	 * Method for to create a html image tag for temporary album covers.
	 *
	 * @param string $file
	 * @param string $title
	 * @param string $alt
	 * @param number $width
	 * @param number $height
	 *
	 * @return string html string for display cover
	 */
	private function createTmpCoverTag($file, $title='cover', $alt='cover', $width=100, $height=100) {
		return '<img src="'.self::getFilePath(true).$file.'" width="'.$width.'" height="'.$height.'" alt="cover '.$alt.'" title="'.$title.'" class="pj_cover">';
	}
	/**
	 * Method for to create a html image tag for DB album covers.
	 *
	 * @param string $$cover_data
	 * @param string $title
	 * @param string $alt
	 * @param number $width
	 * @param number $height
	 *
	 * @return string html string for display cover
	 */
	private function createDBCoverTag($cover_base64, $title='cover', $alt='cover', $width=100, $height=100) {
		return '<img src="'.$cover_base64.'" width="'.$width.'" height="'.round($height).'" alt="cover '.$alt.'" title="'.$title.'" class="pj_cover">';
	}
	/**
	 * Method for to get the html tag for cover images
	 *
	 * @param array $albenitem albenitem Contents of a album
	 * @param boolean If album a sampler then value is true.
	 */
	public function getCoverHTMLTag ($albenitem, $SamplerCheck) {

		$file = JApplication::stringURLSafe($albenitem->artist.'-'.$albenitem->album);
		$path = self::getFilePath();

		if ($this->params->get('save_cover_tmp', 0) == 1) {

			if (!self::checkFileExists($path, $file)) {
				$cover_data = self::getAlbumCover($albenitem,$SamplerCheck);
				if ($cover_data) {
					self::createCoverfile($cover_data, $path, $file);
				} else {
					//If no cover data available, then create a standard cover tag
					return self::createStdCoverHTMLTag($albenitem);
				}
			}

			$cover_state = self::checkFileExists($path, $file);
			$cover_size = self::getTmpCoverSize($cover_state, $path, $file);

			return self::createTmpCoverTag(DIRECTORY_SEPARATOR.$file.'.'.$cover_state, $title='Cover for album: '.$albenitem->album.'('.$albenitem->artist.')', $alt='Cover for album: '.$albenitem->album.'('.$albenitem->artist.')', $cover_size[0], $cover_size[1]);

		} else {

			// Get a reference to the global cache object.
			$cache = JFactory::getCache('com_playjoom', '');

			// Check the cached results.
			if (!($cache->get($path.DIRECTORY_SEPARATOR.$file))) {
				$cover_data = self::getAlbumCover($albenitem,$SamplerCheck);

				if ($cover_data) {
					$cover_base64 = PlayJoomHelperCover::ResampleImage($cover_data);
					// Store the data in cache.
					$cache->store(
						self::createDBCoverTag($cover_base64, $title='Cover for album: '.$albenitem->album.'('.$albenitem->artist.')', $alt='Cover for album: '.$albenitem->album.'('.$albenitem->artist.')', $this->params->get(JRequest::getVar('view').'_cover_size',100), self::calcImageSize($cover_data->width, $cover_data->height)),
						$path.DIRECTORY_SEPARATOR.$file
					);

					return self::createDBCoverTag($cover_base64, $title='Cover for album: '.$albenitem->album.'('.$albenitem->artist.')', $alt='Cover for album: '.$albenitem->album.'('.$albenitem->artist.')', $this->params->get(JRequest::getVar('view').'_cover_size',100), self::calcImageSize($cover_data->width, $cover_data->height));
				} else {
					//If no cover data available, then create a standard cover tag
					return self::createStdCoverHTMLTag($albenitem);
				}
			} else {
				return $cache->get($path.DIRECTORY_SEPARATOR.$file);
			}
		}
	}
	/**
	 *Method for to create a hmtl tag for a standard cover, if in database is no cover data available
	 *
	 * @param array $albenitem albenitem Contents of a album
	 *
	 * @return string html tag for displaying a standard cover file
	 *
	 */
	private function createStdCoverHTMLTag($albenitem) {

		$StandardImagePath = JPATH_BASE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$this->params->get('StandardCoverImage');

		if (file_exists($StandardImagePath) && $this->params->get('StandardCoverImage') != '') {

			//Get image values
			$list = getimagesize($StandardImagePath);

			//Calculate the smaller cover values
			$short_img_height = self::calcImageSize($list[0], $list[1]);

			return '<img src="'.JURI::base(true).'/images/'.$this->params->get('StandardCoverImage').'" data-src="http://'.$_SERVER['SERVER_NAME'].JURI::base(true).'/images/'.$this->params->get('StandardCoverImage').'" width="'.$this->params->get(JRequest::getVar('view').'_cover_size',100).'" height="'.round($short_img_height).'" alt="cover '.$albenitem->album.'" title="'.$albenitem->album.'" class="pj_cover">';
		} else {
			return null;
		}
	}
}