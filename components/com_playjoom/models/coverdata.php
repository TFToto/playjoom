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

use Intervention\Image\ImageManagerStatic as Image;

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

				$query->select('cb.data');
				$query->from('#__jpcoverblobs as cb');
				if ((int) $pk >=1) {
					$query->where('cb.id = '.(int) $pk);
				} else {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Missing cover id. Try with '.$this->artistname.' - '.$this->albumname, 'priority' => JLog::WARNING, 'section' => 'site')));
					$this->_CoverIDSubsequentlyMaintain($this->albumname,$this->artistname,$this->SamplerCheck);
					
					if (!$this->SamplerCheck) {
						//Request for no Sampler
						$query->where('cb.album = '.$db->quote($this->albumname).' AND cb.artist = '.$db->quote($this->artistname));
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
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Database problem: '.$e->getMessage(), 'priority' => JLog::ERROR, 'section' => 'site')));
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

			if(!isset($data)) {

				//check for default cover
				$default_cover = JPATH_BASE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$this->params->get('StandardCoverImage');
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No cover available! Path for default cover is: '.$default_cover, 'priority' => JLog::WARNING, 'section' => 'site')));
				
				if (file_exists($default_cover) && $this->params->get('StandardCoverImage') != '') {
				
					$cover = Image::make($default_cover);
					
				} else {
					$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Can not open file for default cover: ', 'priority' => JLog::ERROR, 'section' => 'site')));
					return null;
				}
			} else {
				$cover = Image::make($data->data);
			}
			
			$cover->resize($this->getImgSize(), null, function ($constraint) {
				$constraint->aspectRatio();
			});
			
			$output = $cover->stream('png', 90);
			$cover->destroy();
			
			return $output;
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
	private function _CoverIDSubsequentlyMaintain($album, $artist, $SamplerCheck=false) {
	
		$db = JFactory::getDBO();
		$dispatcher	= JDispatcher::getInstance();
	
		$query = $db->getQuery(true);
	
		$query->select('cb.id AS cover_id');
		$query->from('#__jpcoverblobs as cb');
	
		if (!$SamplerCheck) {
			//Request for no Sampler
			$query->where('(cb.album = '.$db->quote($album).' AND cb.artist = '.$db->quote($artist).')');
		} else {
			//Request for Sampler
			$query->where('cb.album = '.$db->quote($album));
		}
	
		$db->setQuery($query);
		$cover = $db->loadObject();
	
		if ($cover) {
	
			//Get list tracks of an album
			$query->select('t.id');
			$query->from('#__jpaudiotracks as t');
	
			//Check for albumname as sampler
			if ($SamplerCheck) {
				$query->where('(t.album = '.$db->quote($album). ' AND t.artist = '.$db->quote($artist). ')');
			} else {
				$query->where('t.album = '.$db->quote($album));
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
	
		}
		
		return true;
	}
	/**
	 * Method for to get the maximum width for current view by covers
	 *
	 * @param array $coverdata Datas for creating the cover
	 * @param number $standart_img_width Width configuration for covers
	 *
	 * @return resource resampled image data
	 */
	public function getImgSize() {
	
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

		
		return (int)$cover_width;
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