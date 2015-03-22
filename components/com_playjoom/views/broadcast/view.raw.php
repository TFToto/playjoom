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

// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.environment.browser');

/**
 * Contains the module Methods for the PlayJoom broadcast
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.views
 */
class PlayJoomViewBroadcast extends JViewLegacy {

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {

		set_error_handler('playjoom_error_handler');

		parent::__construct($config);
	}

	public function playjoom_error_handler($errno, $errstr, $errfile, $errline) {

		$ignores = array(
				// We know var is deprecated, shut up
				'var: Deprecated. Please use the public/private/protected modifiers',
				// getid3 spews errors, yay!
				'getimagesize() [',
				'Non-static method getid3',
				'Assigning the return value of new by reference is deprecated',
				// The XML-RPC lib is broken (kinda)
				'used as offset, casting to integer'
		);
	}
	/**
	 * Display the view
	 */
	public function display($tpl = null)	{

		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Call broadcast viewer for track id: '.JRequest::getVar('id'), 'priority' => JLog::INFO, 'section' => 'site')));

		//Get setting values from xml file
		$app		= JFactory::getApplication();
		$params		= $app->getParams();

		if (!JRequest::getVar('tlk')) {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Access denied! Missing tlk.', 'priority' => JLog::ERROR, 'section' => 'site')));
			JError::raiseError('Access denied');
			exit();
		}
		//Check for Trackcontrol
		if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false) {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Access denied! Track Control plugin is not available.', 'priority' => JLog::WARNING, 'section' => 'site')));
			JError::raiseError('Access denied');
			exit();
		} else {
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'JError! RaiseError: '.$errors, 'priority' => JLog::ERROR, 'section' => 'site')));
				JError::raiseError(500, implode('<br />', $errors));
				return false;
			}

			JLoader::import( 'helpers.broadcast', JPATH_SITE .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_playjoom');

			$broadcast = new BroadcastHelper();

			ob_end_clean();

			// Assign data to the view
			$this->item = $this->get('Item');
			// don't abort the script if user skips this song because we need to update now_playing
			ignore_user_abort(true);

			$media_name = $this->item->artist . " - " . $this->item->title . "." . JFile::getExt($this->item->file);

			header('Access-Control-Allow-Origin: *');

			// Prevent the script from timing out
			set_time_limit(0);

			$fp = fopen($this->item->pathatlocal.DIRECTORY_SEPARATOR.$this->item->file, 'rb');

			$stream_size = $this->item->filesize;

			if (!is_resource($fp)) {
				//debug_event('play', "Failed to open $media->file for streaming", 2);
				exit();
			}

			header('ETag: ' . $this->item->id);

			// Handle Content-Range

			$start = 0;
			$end = 0;
			sscanf($_SERVER['HTTP_RANGE'], "bytes=%d-%d", $start, $end);

			if ($start > 0 || $end > 0) {
				// Calculate stream size from byte range
				if (isset($end)) {
					$end = min($end, $this->item->filesize - 1);
					$stream_size = ($end - $start) + 1;
				} else {
					$stream_size = $this->item->filesize - $start;
				}

				if ($stream_size == null) {
					//debug_event('play', 'Content-Range header received, which we cannot fulfill due to unknown final length (transcoding?)', 2);
				} else {
					//debug_event('play', 'Content-Range header received, skipping ' . $start . ' bytes out of ' . $media->filesize, 5);
					fseek($fp, $start);

					$range = $start . '-' . $end . '/' . $this->item->filesize;
					header('HTTP/1.1 206 Partial Content');
					header('Content-Range: bytes ' . $range);
				}
			}

			$broadcast->downloadHeaders($media_name, $this->item->mediatype, true, $stream_size);

			$bytes_streamed = 0;
			$transcode = false;

			// Actually do the streaming
			do {
				$read_size = $transcode
				? 2048
				: min(2048, $stream_size - $bytes_streamed);
				$buf = fread($fp, $read_size);
				print($buf);
				ob_flush();
				$bytes_streamed += strlen($buf);
			} while (!feof($fp) && (connection_status() == 0) && ($transcode || $bytes_streamed < $stream_size));

			$real_bytes_streamed = $bytes_streamed;
			// Need to make sure enough bytes were sent.

			if ($bytes_streamed < $stream_size && (connection_status() == 0)) {
				print(str_repeat(' ', $stream_size - $bytes_streamed));
				$bytes_streamed = $stream_size;
			}

			//set current counter
			if (true === isset($this->item->id)) {
				PlayJoomHelper::countHit($this->item->id);
			}

			fclose($fp);
	    }
	}
}
