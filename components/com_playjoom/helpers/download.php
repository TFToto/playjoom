<?php
/**
 * Contains the helper methods for the PlayJoom Cover helper.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * http://www.zend.com/codex.php?id=535&single=1
 *	By Eric Mueller <eric@themepark.com>
 *
 *	http://www.zend.com/codex.php?id=470&single=1
 *	by Denis125 <webmaster@atlant.ru>
 *
 *	A patch from Peter Listiak <mlady@users.sourceforge.net> for last modified
 *	date and time of the compressed file
 *
 *	Official ZIP file format: http://www.pkware.com/appnote.txt
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
 * Contains the Helper Methods for the PlayJoom Downloader
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.helpers
 */
class PlayJoomHelperDownload {

	var $datasec = array();
	var $ctrl_dir = array();
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $old_offset = 0;

	function unix2DosTime($unixtime = 0) {
		$timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);
		if ($timearray['year'] < 1980) {
			$timearray['year']= 1980;
			$timearray['mon'] = 1;
			$timearray['mday']= 1;
			$timearray['hours']	= 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		}
		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	}


	function addFile($data, $name, $time = 0) {

		ini_set("memory_limit","520M");

		$name = str_replace('\\', '/', $name);

		$dtime= dechex($this->unix2DosTime($time));
		$hexdtime = '\x' . $dtime[6] . $dtime[7] . '\x' . $dtime[4] . $dtime[5] . '\x' . $dtime[2] . $dtime[3] . '\x' . $dtime[0] . $dtime[1];
		eval('$hexdtime = "' . $hexdtime . '";');

		$fr = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00" . $hexdtime;

		$unc_len = strlen($data);
		$crc = crc32($data);
		$zdata = gzcompress($data);
		$zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		$c_len	= strlen($zdata);
		$fr .= pack('V', $crc) . pack('V', $c_len) . pack('V', $unc_len) . pack('v', strlen($name)) . pack('v', 0) . $name . $zdata . pack('V', $crc) . pack('V', $c_len) . pack('V', $unc_len);

		$this -> datasec[] = $fr;
		$new_offset = strlen(implode('', $this->datasec));

		$cdrec = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00" . $hexdtime . pack('V', $crc) . pack('V', $c_len) . pack('V', $unc_len) . pack('v', strlen($name)) . pack('v', 0) . pack('v', 0) . pack('v', 0) . pack('v', 0) . pack('V', 32) . pack('V', $this -> old_offset );
		$this -> old_offset = $new_offset;
		$cdrec .= $name;
		$this -> ctrl_dir[] = $cdrec;
	}

	function file() {

		ini_set('memory_limit', '-1');

		$data = implode('', $this -> datasec);
		$ctrldir = implode('', $this -> ctrl_dir);
		return $data . $ctrldir . $this -> eof_ctrl_dir . pack('v', sizeof($this -> ctrl_dir)) .  pack('v', sizeof($this -> ctrl_dir)) .  pack('V', strlen($ctrldir)) . pack('V', strlen($data)) . "\x00\x00";
	}

	/**
	 * Method for sending the page header
	 *
	 * @param string $playlistFileName Name of the playlist file
	 * @param string $playlistType     Mimetype of the playlist
	 * @param int    $playlistSize     Data size of the created playlist
	 *
	 * @return void
	 */
	public function setHeader($filename,$archiveType,$archiveSize) {

		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Set Header for download filename: '.$filename.', archive type: '.$archiveType.', archive size: '.$archiveSize, 'priority' => JLog::ERROR, 'section' => 'site')));

		//Setup web server
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			$sf = $_SERVER['SERVER_SOFTWARE'];
		} else {
			$sf = getenv('SERVER_SOFTWARE');
		}

		if (!strpos($sf, 'IIS')) {
			@apache_setenv('no-gzip', 1);
		}
		@ini_set('zlib.output_compression', 0);

		ignore_user_abort(false);

		JResponse::clearHeaders();
		header("HTTP/1.1 200 OK");

		JResponse::setHeader('Accept-Range','bytes', true);
		JResponse::setHeader('X-Pad','avoid browser bug');
		JResponse::setHeader('Content-Type', $archiveType, true);
		JResponse::setHeader('Content-Disposition', 'inline; filename='.$filename.';', true);
		JResponse::setHeader('Content-Range','bytes 0-'.($archiveSize -1).'/'.$archiveSize, true);
		JResponse::setHeader('Content-Length',(string)$archiveSize,true);
		JResponse::setHeader('Content-Transfer-Encoding','binary', true);
		JResponse::setHeader('Cache-Control','no-cache, must-revalidate', true);
		JResponse::setHeader('Pragma','no-cache', true);
		JResponse::setHeader('Connection','close', true);

		JResponse::sendHeaders();
	}
	/**
	 * Method for to send the created zip archive
	 *
	 * @param unknown $data
	 */
	public function send($data) {

		print $data;
    	exit;
	}
}