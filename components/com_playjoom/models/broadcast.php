<?php
/**
 * Contains the module Methods for the PlayJoom broadcast
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2015 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla modelitem library
jimport('joomla.application.component.modellist');

/**
 * Contains the module Methods for the PlayJoom broadcast
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.models
 */
class PlayJoomModelBroadcast extends JModelItem {

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

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('article.id', $pk);

        parent::populateState();
     }

     /**
      * Method for to get track data for broadcats request
      *
      * @param $pk ID of the track
      *
      * @return object The message to be displayed to the user
      */
     public function getItem($pk = null) {

		$dispatcher	= JDispatcher::getInstance();

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('article.id');

		if ($this->_item === null) 	{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {

			try {
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				$query->from('#__jpaudiotracks as h');
				$query->leftJoin('#__categories as c ON h.catid=c.id');
				$query->select('h.id, h.add_by, h.pathatlocal, h.file, h.filesize, h.alias, h.title, h.artist, h.mediatype, h.bit_rate, h.params, c.title as category');
				$query->where('h.id=' . (int) $pk);

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

        	if (self::checkValidSession($data->add_by, $pk)) {
        		$this->_item[$pk] = $data;
        		return $this->_item[$pk];
        	} else {
        		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Access denied! No valid session found for this request. Request goes exit.', 'priority' => JLog::WARNING, 'section' => 'site')));
        		JError::raiseError('Access denied');
        		exit();
        	}
         }
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