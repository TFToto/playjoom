<?php
/**
 * Contains the helper methods for the PlayJoom Broadcast helper.
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
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.filesystem.file');

/**
 * Contains the Helper Methods for the PlayJoom Broadcast
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.helpers
 */
class BroadcastHelper {

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
	public function sendData($item, $params, $resamplestat=null) {

		ob_end_clean();

		// don't abort the script if user skips this song because we need to update now_playing
		ignore_user_abort(true);

		// Format the song name
		$media_name = $item->artist . " - " . $item->title . "." . JFile::getExt($item->file);

		header('Access-Control-Allow-Origin: *');

		// Prevent the script from timing out
		set_time_limit(0);

		$fp = fopen($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file, 'rb');

		$stream_size = $item->filesize;
		$fp = fopen($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file, 'rb');

		$stream_size = $item->filesize;

		if (!is_resource($fp)) {
			//debug_event('play', "Failed to open $media->file for streaming", 2);
			exit();
		}

		header('ETag: ' . $item->id);

		// Handle Content-Range

		$start = 0;
		$end = 0;
		//sscanf($_SERVER['HTTP_RANGE'], "bytes=%d-%d", $start, $end);

		if ($start > 0 || $end > 0) {
			// Calculate stream size from byte range
			if (isset($end)) {
				$end = min($end, $item->filesize - 1);
				$stream_size = ($end - $start) + 1;
			} else {
				$stream_size = $item->filesize - $start;
			}

			if ($stream_size == null) {
				//debug_event('play', 'Content-Range header received, which we cannot fulfill due to unknown final length (transcoding?)', 2);
			} else {
				//debug_event('play', 'Content-Range header received, skipping ' . $start . ' bytes out of ' . $media->filesize, 5);
				fseek($fp, $start);

				$range = $start . '-' . $end . '/' . $media->filesize;
				header('HTTP/1.1 206 Partial Content');
				header('Content-Range: bytes ' . $range);
			}
		}
		$bytes_streamed = 0;
		$transcode = null;
		// Actually do the streaming
		do {
			$read_size = $transcode
			? 2048
			: min(2048, $stream_size - $bytes_streamed);
			$buf = fread($fp, $read_size);
			print($buf);
			ob_flush(); //Important for vlc player!!
			flush();
			$bytes_streamed += strlen($buf);
		} while (!feof($fp) && (connection_status() == 0) && ($transcode || $bytes_streamed < $stream_size));

		$real_bytes_streamed = $bytes_streamed;
		// Need to make sure enough bytes were sent.

		if ($bytes_streamed < $stream_size && (connection_status() == 0)) {
			print(str_repeat(' ', $stream_size - $bytes_streamed));
			$bytes_streamed = $stream_size;
		}

		fclose($fp);
	}
	/**
	 * Returns the headers for a browser download.
	 *
	 * @param string $filename  The filename of the download.
	 * @param string $cType     The content-type description of the file.
	 * @param boolean $inline   True if inline, false if attachment.
	 * @param string $cLength   The content-length of this file.
	 */
	public function downloadHeaders($filename = 'unknown', $cType = null, $inline = false, $cLength = null) {

		/* Remove linebreaks from file names. */
		$filename = str_replace(array("\r\n", "\r", "\n"), ' ', $filename);

		/* Some browsers don't like spaces in the filename. */
		if ($this->hasQuirk('no_filename_spaces')) {
			$filename = strtr($filename, ' ', '_');
		}

		/* MSIE doesn't like multiple periods in the file name. Convert all
		 * periods (except the last one) to underscores. */
		/*
		 if ($this->isBrowser('msie')) {
			if (($pos = strrpos($filename, '.'))) {
				$filename = strtr(substr($filename, 0, $pos), '.', '_') . substr($filename, $pos);
			}

7
			$filename = rawurlencode($filename);
		}
		*/

		/* Content-Type/Content-Disposition Header. */
		if ($inline) {
			if (!is_null($cType)) {
				header('Content-Type: ' . trim($cType));
			} else {
				header('Content-Type: application/octet-stream');
			}
			header('Content-Disposition: inline; filename="' . $filename . '"');
		} else {
			if (!is_null($cType)) {
				header('Content-Type: ' . trim($cType));
			} else {
				header('Content-Type: application/octet-stream');
			}

			if ($this->hasQuirk('break_disposition_header')) {
				header('Content-Disposition: filename="' . $filename . '"');
			} else {
				header('Content-Disposition: attachment; filename="' . $filename . '"');
			}
		}

		/* Content-Length Header. Only send if we are not compressing
		 * output. */
		if (!is_null($cLength) &&
		!in_array('ob_gzhandler', ob_list_handlers())) {
			header('Content-Length: ' . $cLength);
		}

		/* Overwrite Pragma: and other caching headers for IE. */
		if ($this->hasQuirk('cache_ssl_downloads')) {
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
		}
	}
	/**
	 * Checks unique behavior for the current browser.
	 *
	 * @param string $quirk  The behavior to check.
	 *
	 * @return boolean  Does the browser have the behavior set?
	 */
	public function hasQuirk($quirk) {
		return !empty($this->_quirks[$quirk]);
	}
}