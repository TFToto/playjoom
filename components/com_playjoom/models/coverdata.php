<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license https://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * Contains the module Methods for the PlayJoom album
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.models
 */
class PlayJoomModelCoverData extends JModelItem {

	/**
     * @var object item
     */
    protected $item;

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return      void
     * @since       1.6
     */
     protected function populateState() {

     	$app = JFactory::getApplication('site');

     	//Get PlayJoom main config parameters
     	//$this->params = JComponentHelper::getParams('com_playjoom');
     	//Get setting values from xml file
     	$this->params		= $app->getParams();

		// Load state from the request.
		$pk = $app->input->getInt('coverid');
		$this->setState('cover.id', $pk);

		if($app->input->get('coverview') && $app->input->get('coverview') != 0) {
			$this->coverview = $app->input->get('coverview');
		} elseif($app->input->getInt('coverview') == 0) {
			$this->coverview = null;
		}
		$this->coverview = $app->input->get('coverview');

		$this->albumname = base64_decode($app->input->get('album'));
		$this->artistname = base64_decode($app->input->get('artist'));

		$this->SamplerCheck = PlayJoomHelper::checkForSampler($this->albumname, $this->artistname);

        parent::populateState();
     }

     /**
      * Method for to get cover data for broadcat request
      *
      * @param $pk ID of the track
      *
      * @return object The message to be displayed to the user
      */
     public function getItem($pk = null) {

		$dispatcher	= JDispatcher::getInstance();

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('cover.id');

		if ($this->_item === null) 	{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {

			try {
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select('cb.id, cb.album, cb.artist, cb.width, cb.height, cb.mime, cb.data');
				$query->from('#__jpcoverblobs as cb');
				if ((int) $pk >=1) {
					$query->where('cb.id = '.(int) $pk);
				} else {
					if (!$this->SamplerCheck) {
						//Request for no Sampler
						$query->where('(cb.album = '.$db->quote($this->albumname).' AND cb.artist = '.$db->quote($this->artistname));
					} else {
						//Request for Sampler
						$query->where('cb.album = '.$db->quote($this->albumname));
					}
				}

				// Join over the add_by id user.
				//$query->select('a.add_by');
				//$query->join('LEFT', '#__jpaudiotracks AS a ON a.coverid = cb.id');

				$db->setQuery($query);
				$data = $db->loadObject();

			} catch (Exception $e) {
				if ($e->getCode() == 404) {
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				} else {
					$this->setError($e);
					$this->_item[$pk] = false;
				}
        	}

        	//TODO session check for to get image data
        	/*
        	if (self::checkValidSession($data->add_by, $pk)) {
        		$this->_item[$pk] = $data;
        		return $this->_item[$pk];
        	} else {
        		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Access denied! No valid session found for this request. Request goes exit.', 'priority' => JLog::WARNING, 'section' => 'site')));
        		JError::raiseError('Access denied');
        		exit();
        	}
        	*/

			if(!$data) {
				//check for default cover
				$default_cover = JPATH_BASE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$this->params->get('StandardCoverImage');
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No cover available! Path for default cover is: '.$default_cover, 'priority' => JLog::WARNING, 'section' => 'site')));
				
				if (file_exists($default_cover) && $this->params->get('StandardCoverImage') != '') {
				
					$handle = fopen($default_cover, "rb");
					$contents = stream_get_contents($handle);
					fclose($handle);
					echo $contents;
				} else {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Can not open file for default cover: ', 'priority' => JLog::ERROR, 'section' => 'site')));
					$this->_item[$pk] = null;
				}
			} else {
				$this->_item[$pk] = $data;
			}
			return self::ResampleImage($this->_item[$pk]);
		}
	}
	/**
	 * Method for to make a image resampling
	 *
	 * @param array $coverdata Datas for creating the cover
	 * @param number $standart_img_width Width configuration for covers
	 *
	 * @return resource resampled image data
	 */
	public function ResampleImage($coverdata) {

		$dispatcher	= JDispatcher::getInstance();		
		$app = JFactory::getApplication();
		$view = $app->input->get('coverview');
		
		//If link comes of a module, then get also the params of this module
		if ($moduletype = base64_decode($app->input->get('moduletype')) 
		    && $moduletitle = base64_decode($app->input->get('moduletitle'))) {

		    $module = JModuleHelper::getModule($moduletype,$moduletitle);
		    $module_params = new JRegistry($module->params);
		} else {
		    $module_params = null;
		}
		
		if ($module_params) {
			$cover_width = $module_params->get($view.'_cover_size',100);
		} else {
		    if (isset($this->params)) {
		    $cover_width = $this->params->get($view.'_cover_size',100);
		} else {
			$cover_width = 100;
		    }
		}

		if(!$coverdata->width || !$coverdata->height) {
			$short_img_height = 100;
		} else {
			//Calculate the smaller cover values
			if ($coverdata->width > $coverdata->height) {
				$ratio = $coverdata->width / $coverdata->height;
				$short_img_height = $cover_width / $ratio;
			} else {
				$ratio = $coverdata->height / $coverdata->width;
				$short_img_height = $cover_width / $ratio;
			}
		}

		//Create Cover blob for thumbnail
		$src_img = @imagecreatefromstring($coverdata->data);

		//Create the thumbnail cover
		$dest_img = imageCreateTrueColor($cover_width, round($short_img_height));

		//Resample the thumbnail cover
		if ($coverdata->height && $coverdata->width) {
			imageCopyResampled($dest_img, $src_img, 0, 0, 0 ,0, $cover_width, round($short_img_height), $coverdata->width, $coverdata->height);
		}

		ob_start();

		switch ($coverdata->mime) {
			case "image/jpeg" :
				ob_start();
				imagejpeg($dest_img);
				$imgdata = ob_get_contents();
				break;
			case "image/jpg" :
				ob_start();
				imagejpeg($dest_img);
				$imgdata = ob_get_contents();
				break;
			case "image/gif" :
				ob_start();
				imagegif($dest_img);
				$imgdata = ob_get_contents();
				break;
			case "image/png" :
				ob_start();
				imagepng($dest_img);
				$imgdata = ob_get_contents();
				break;
			default:
				//'MISSING COVER IMAGE TYPE';
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Missing or unknow mime type for cover img. mime: '.$coverdata->mime, 'priority' => JLog::ERROR, 'section' => 'site')));
				//return null;
				ob_start();
				imagegif($dest_img);
				$imgdata = ob_get_contents();
		}
		ob_get_clean();

		// Clean up temp images
		imagedestroy($src_img);
		imagedestroy($tmp_img);
		imagedestroy($dest_img);
		ob_end_clean();

		$covers_items = array(
				"data" => $imgdata,
				"width" => $cover_width,
				"height" => round($short_img_height),
				"mime" => $coverdata->mime
		);
		
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Done with resample image data.', 'priority' => JLog::INFO, 'section' => 'site')));
				
		return (object) $covers_items;
	}
	/**
	 * Method for to check if the requested track has a valid open session
	 *
	 * @param string $tlk hash of current session ID, track ID and user IP address.
	 *
	 * @return boolean
	 */
	public function checkValidSession($user_id, $pk) {

		$dispatcher	= JDispatcher::getInstance();

		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->from('#__session as s');
		$query->select('s.session_id, s.userid');
		$query->where('s.userid=' . (int) $user_id);

		$db->setQuery($query);
		$Valid_Sessions = $db->loadObjectList();

		if (count($Valid_Sessions) >= 1 ){

			foreach ($Valid_Sessions as $Valid_Session) {

				$session_form_db = hash('sha256',$Valid_Session->session_id.'+'.PlayJoomHelper::getUserIP().'+'.$pk);
				$session_form_post = JFactory::getApplication()->input->get('tlk');

				if ($session_form_db != $session_form_post) {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Access denied! No valid session found for this request.', 'priority' => JLog::WARNING, 'section' => 'site')));
					$valid_session =false;
				} else {
					return true;
				}
			}
		} else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Access denied! No valid session found for this request.', 'priority' => JLog::WARNING, 'section' => 'site')));
			$valid_session = false;
		}
		return $valid_session;
	}
}