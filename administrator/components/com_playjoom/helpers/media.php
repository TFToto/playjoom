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

defined('_JEXEC') or die;

/**
 * @package	 PlayJoom.Administrator
 * @subpackage	 Media
 */
abstract class PlayJoomMediaHelper
{
	/**
	 * @var   array   array containing information for loaded files
	 */
	protected static $loaded = array();
	
	/**
	 * Checks if the file is an image
	 * @param string The filename
	 * @return boolean
	 */
	public static function isImage($fileName)
	{
		static $imageTypes = 'xcf|odg|gif|jpg|png|bmp';
		return preg_match("/\.(?:$imageTypes)$/i", $fileName);
	}

	/**
	 * Checks if the file is an image
	 * @param string The filename
	 * @return boolean
	 */
	public static function getTypeIcon($fileName)
	{
		// Get file extension
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}

	/**
	 * Checks if the file can be uploaded
	 *
	 * @param array File information
	 * @param array $allowableExtensions for upload
	 * @param string An error message to be returned
	 * @return boolean
	 */
	public static function canUpload($file, &$err, $allowableExtensions)	{
		
		$params = JComponentHelper::getParams('com_playjoom');
		$dispatcher	= JDispatcher::getInstance();
		
		if (empty($file['name'])) {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Input file '.$file['name'].' is enmpy', 'priority' => JLog::ERROR, 'section' => 'admin')));
			$err = 'COM_PLAYJOOM_ERROR_UPLOAD_INPUT';
			return false;
		}

		/*
		jimport('joomla.filesystem.file');
		if ($file['name'] !== JFile::makesafe($file['name'])) {
			$err = 'COM_PLAYJOOM_ERROR_WARNFILENAME';
			return false;
		}
		*/

		$format = strtolower(JFile::getExt($file['name']));

		$allowable = explode(',', $allowableExtensions);
		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Allow file extensions for upload are: '.$allowableExtensions, 'priority' => JLog::INFO, 'section' => 'admin')));

		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Check file for upload.', 'priority' => JLog::ERROR, 'section' => 'admin')));
		
		if (!in_array($format, $allowable) && !in_array($format, $ignored))
		{
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'File for upload is not supported', 'priority' => JLog::ERROR, 'section' => 'admin')));
			$err = 'COM_PLAYJOOM_ERROR_WARNFILETYPE';
			return false;
		}

		$maxSize = (int) ($params->get('upload_maxsize', 100) * 1024 * 1024);
		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			$err = 'COM_PLAYJOOM_ERROR_WARNFILETOOLARGE';
			return false;
		}
		
		$xss_check =  JFile::read($file['tmp_name'], false, 256);
		$html_tags = array('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if (stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				$err = 'COM_PLAYJOOM_ERROR_WARNIEXSS';
				return false;
			}
		}
		return true;
	}

	public static function parseSize($size)
	{
		if ($size < 1024) {
			return JText::sprintf('COM_PLAYJOOM_FILESIZE_BYTES', $size);
		}
		elseif ($size < 1024 * 1024) {
			return JText::sprintf('COM_PLAYJOOM_FILESIZE_KILOBYTES', sprintf('%01.2f', $size / 1024.0));
		}
		else {
			return JText::sprintf('COM_PLAYJOOM_FILESIZE_MEGABYTES', sprintf('%01.2f', $size / (1024.0 * 1024)));
		}
	}

	public static function imageResize($width, $height, $target)
	{
		//takes the larger size of the width and height and applies the
		//formula accordingly...this is so this script will work
		//dynamically with any size image
		if ($width > $height) {
			$percentage = ($target / $width);
		} else {
			$percentage = ($target / $height);
		}

		//gets the new value and applies the percentage, then rounds the value
		$width = round($width * $percentage);
		$height = round($height * $percentage);

		return array($width, $height);
	}

	public static function countFiles($dir)
	{
		$total_file = 0;
		$total_dir = 0;

		if (is_dir($dir)) {
			$d = dir($dir);

			while (false !== ($entry = $d->read())) {
				if (substr($entry, 0, 1) != '.' && is_file($dir . DIRECTORY_SEPARATOR . $entry) && strpos($entry, '.html') === false && strpos($entry, '.php') === false) {
					$total_file++;
				}
				if (substr($entry, 0, 1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry)) {
					$total_dir++;
				}
			}

			$d->close();
		}

		return array ($total_file, $total_dir);
	}
	
	/**
	 * Method for create a cover image as html tag
	 *
	 * @param string image content
	 * @param array image meta data
	 * @return string html tag
	 */
	public function getCoverThumb($ImageContent, $ImageInfo) {

		//check for view type
		$app	= JFactory::getApplication();
		$style = $app->getUserStateFromRequest('media.list.layout', 'layout', 'details', 'word');
		
		switch ($style) {
			case "details" :				
				$ImageWidth = $ImageInfo->width_26;	
				$ImageHeight = $ImageInfo->height_26;
				break;
				
			case "thumbs" :
				$ImageWidth = $ImageInfo->width_60;
				$ImageHeight = $ImageInfo->height_60;
				break;
			
			default:
				$ImageWidth = $ImageInfo->width_26;
				$ImageHeight = $ImageInfo->height_26;
		}
		
		$coverblob = null;
	
		if ($ImageContent != ''
				&& $ImageContent != null
				&& extension_loaded('gd')) {
	
			//check for image type
			switch ($ImageInfo->mime) {
				case "image/jpeg" :
					$img_type = 'jpg';
					break;
				case "image/jpg" :
					$img_type = 'jpg';
					break;
				case "image/gif" :
					$img_type = 'gif';
					break;
				case "image/png" :
					$img_type = 'png';
					break;
				default:
					'MISSING COVER IMAGE TYPE';
					return null;
			}
	
			//Create Cover blob for thumbnail
			$src_img = @imagecreatefromstring($ImageContent);
			
			//Create the thumbnail cover
			$dest_img = imageCreateTrueColor($ImageWidth, $ImageHeight);
			
			//Resample the thumbnail cover
			imageCopyResampled($dest_img, $src_img, 0, 0, 0 ,0, $ImageWidth, $ImageHeight, $ImageInfo->width, $ImageInfo->height);
			
			ob_start();
	
			switch ($ImageInfo->mime) {
				case "image/jpeg" :
					ob_start();
					imagejpeg($dest_img);
					$tmp_img = ob_get_contents();
					$CoverThumb = 'data:image/jpg;base64,'.base64_encode($tmp_img);
					break;
				case "image/jpg" :
					ob_start();
					imagejpeg($dest_img);
					$tmp_img = ob_get_contents();
					$CoverThumb = 'data:image/jpg;base64,'.base64_encode($tmp_img);
					break;
				case "image/gif" :
					ob_start();
					imagejpeg($dest_img);
					$tmp_img = ob_get_contents();
					$CoverThumb = 'data:image/jpg;base64,'.base64_encode($tmp_img);
					break;
				case "image/png" :
					ob_start();
					imagejpeg($dest_img);
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
			return '<img src="'.$CoverThumb.'" width="'.$ImageWidth.'" height="'.$ImageHeight.'" alt="cover" class="cover"> ';
		}
	}
	
	/**
	 * Add unobtrusive javascript support for the advanced uploader.
	 *
	 * @param   string  $id            An index.
	 * @param   array   $params        An array of options for the uploader.
	 * @param   string  $upload_queue  The HTML id of the upload queue element (??).
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function AddUploaderScripts($id = 'file-upload', $params = array(), $upload_queue = 'upload-queue')
	{
		// Include MooTools framework
		self::framework();
		
		JHtml::_('script', 'administrator/components/com_playjoom/assets/js/swf.js', true, false);
		JHtml::_('script', 'administrator/components/com_playjoom/assets/js/progressbar.js', true, false);
		JHtml::_('script', 'administrator/components/com_playjoom/assets/js/uploader.js', true, false);
	
		$document = JFactory::getDocument();
	
		if (!isset(self::$loaded[__METHOD__]))
		{
			JText::script('COM_PLAYJOOM_UPLOADER_FILENAME');
			JText::script('COM_PLAYJOOM_UPLOADER_UPLOAD_COMPLETED');
			JText::script('COM_PLAYJOOM_UPLOADER_ERROR_OCCURRED');
			JText::script('COM_PLAYJOOM_UPLOADER_ALL_FILES');
			JText::script('COM_PLAYJOOM_UPLOADER_PROGRESS_OVERALL');
			JText::script('COM_PLAYJOOM_UPLOADER_CURRENT_TITLE');
			JText::script('COM_PLAYJOOM_UPLOADER_REMOVE');
			JText::script('COM_PLAYJOOM_UPLOADER_REMOVE_TITLE');
			JText::script('COM_PLAYJOOM_UPLOADER_CURRENT_FILE');
			JText::script('COM_PLAYJOOM_UPLOADER_CURRENT_PROGRESS');
			JText::script('COM_PLAYJOOM_UPLOADER_FILE_ERROR');
			JText::script('COM_PLAYJOOM_UPLOADER_FILE_SUCCESSFULLY_UPLOADED');
			JText::script('COM_PLAYJOOM_UPLOADER_VALIDATION_ERROR_DUPLICATE');
			JText::script('COM_PLAYJOOM_UPLOADER_VALIDATION_ERROR_SIZELIMITMIN');
			JText::script('COM_PLAYJOOM_UPLOADER_VALIDATION_ERROR_SIZELIMITMAX');
			JText::script('COM_PLAYJOOM_UPLOADER_VALIDATION_ERROR_FILELISTMAX');
			JText::script('COM_PLAYJOOM_UPLOADER_VALIDATION_ERROR_FILELISTSIZEMAX');
			JText::script('COM_PLAYJOOM_UPLOADER_ERROR_HTTPSTATUS');
			JText::script('COM_PLAYJOOM_UPLOADER_ERROR_SECURITYERROR');
			JText::script('COM_PLAYJOOM_UPLOADER_ERROR_IOERROR');
			JText::script('COM_PLAYJOOM_UPLOADER_ALL_FILES');
		}
	
		if (isset(self::$loaded[__METHOD__][$id]))
		{
			return;
		}
	
		$onFileSuccess = '\\function(file, response) {
			var json = new Hash(JSON.decode(response, true) || {});
	
			if (json.get(\'status\') == \'1\') {
				file.element.addClass(\'file-success\');
				file.info.set(\'html\', \'<strong>\' + Joomla.JText._(\'COM_PLAYJOOM_UPLOADER_FILE_SUCCESSFULLY_UPLOADED\') + \'</strong>\');
			} else {
				file.element.addClass(\'file-failed\');
				file.info.set(\'html\', \'<strong>\' +
					Joomla.JText._(\'COM_PLAYJOOM_UPLOADER_ERROR_OCCURRED\',
						\'An Error Occurred\').substitute({ error: json.get(\'error\') }) + \'</strong>\');
			}
		}';
	
		// Setup options object
		$opt['verbose']			= true;
		$opt['url']				= (isset($params['targetURL'])) ? $params['targetURL'] : null;
		$opt['path']			= (isset($params['swf'])) ? $params['swf'] : JURI::root(true) . '/administrator/components/com_playjoom/assets/swf/uploader.swf';
		$opt['height']			= (isset($params['height'])) && $params['height'] ? (int) $params['height'] : null;
		$opt['width']			= (isset($params['width'])) && $params['width'] ? (int) $params['width'] : null;
		$opt['multiple']		= (isset($params['multiple']) && !($params['multiple'])) ? false : true;
		$opt['queued']			= (isset($params['queued']) && !($params['queued'])) ? (int) $params['queued'] : null;
		$opt['target']			= (isset($params['target'])) ? $params['target'] : '\\document.id(\'upload-browse\')';
		$opt['instantStart']	= (isset($params['instantStart']) && ($params['instantStart'])) ? true : false;
		$opt['allowDuplicates']	= (isset($params['allowDuplicates']) && !($params['allowDuplicates'])) ? false : true;
		// limitSize is the old parameter name.  Remove in 1.7
		$opt['fileSizeMax']		= (isset($params['limitSize']) && ($params['limitSize'])) ? (int) $params['limitSize'] : null;
		// fileSizeMax is the new name.  If supplied, it will override the old value specified for limitSize
		$opt['fileSizeMax']		= (isset($params['fileSizeMax']) && ($params['fileSizeMax'])) ? (int) $params['fileSizeMax'] : $opt['fileSizeMax'];
		$opt['fileSizeMin']		= (isset($params['fileSizeMin']) && ($params['fileSizeMin'])) ? (int) $params['fileSizeMin'] : null;
		// limitFiles is the old parameter name.  Remove in 1.7
		$opt['fileListMax']		= (isset($params['limitFiles']) && ($params['limitFiles'])) ? (int) $params['limitFiles'] : null;
		// fileListMax is the new name.  If supplied, it will override the old value specified for limitFiles
		$opt['fileListMax']		= (isset($params['fileListMax']) && ($params['fileListMax'])) ? (int) $params['fileListMax'] : $opt['fileListMax'];
		$opt['fileListSizeMax'] = (isset($params['fileListSizeMax']) && ($params['fileListSizeMax'])) ? (int) $params['fileListSizeMax'] : null;
		// types is the old parameter name.  Remove in 1.7
		$opt['typeFilter']		= (isset($params['types'])) ? '\\' . $params['types']
		: '\\{Joomla.JText._(\'JLIB_HTML_BEHAVIOR_UPLOADER_ALL_FILES\'): \'*.*\'}';
		$opt['typeFilter']		= (isset($params['typeFilter'])) ? '\\' . $params['typeFilter'] : $opt['typeFilter'];
	
		// Optional functions
		$opt['createReplacement']	= (isset($params['createReplacement'])) ? '\\' . $params['createReplacement'] : null;
		$opt['onFileComplete']		= (isset($params['onFileComplete'])) ? '\\' . $params['onFileComplete'] : null;
		$opt['onBeforeStart']		= (isset($params['onBeforeStart'])) ? '\\' . $params['onBeforeStart'] : null;
		$opt['onStart']				= (isset($params['onStart'])) ? '\\' . $params['onStart'] : null;
		$opt['onComplete']			= (isset($params['onComplete'])) ? '\\' . $params['onComplete'] : null;
		$opt['onFileSuccess']		= (isset($params['onFileSuccess'])) ? '\\' . $params['onFileSuccess'] : $onFileSuccess;
	
		if (!isset($params['startButton']))
		{
			$params['startButton'] = 'upload-start';
		}
	
		if (!isset($params['clearButton']))
		{
			$params['clearButton'] = 'upload-clear';
		}
	
		$opt['onLoad'] = '\\function() {
				document.id(\'' . $id
					. '\').removeClass(\'hide\'); // we show the actual UI
				document.id(\'upload-noflash\').destroy(); // ... and hide the plain form
	
				// We relay the interactions with the overlayed flash to the link
				this.target.addEvents({
					click: function() {
						return false;
					},
					mouseenter: function() {
						this.addClass(\'hover\');
					},
					mouseleave: function() {
						this.removeClass(\'hover\');
						this.blur();
					},
					mousedown: function() {
						this.focus();
					}
				});
	
				// Interactions for the 2 other buttons
	
				document.id(\'' . $params['clearButton']
					. '\').addEvent(\'click\', function() {
					Uploader.remove(); // remove all files
					return false;
				});
	
				document.id(\'' . $params['startButton']
					. '\').addEvent(\'click\', function() {
					Uploader.start(); // start upload
					return false;
				});
			}';
	
		$options = PlayJoomMediaHelper::getJSObject($opt);
		//$options = JHtml::getJSObject($opt);
	
		// Attach tooltips to document
		$uploaderInit = 'window.addEvent(\'domready\', function(){
				var Uploader = new FancyUpload2(document.id(\'' . $id . '\'), document.id(\'' . $upload_queue . '\'), ' . $options . ' );
				});';
		$document->addScriptDeclaration($uploaderInit);
	
		// Set static array
		self::$loaded[__METHOD__][$id] = true;
	
		return;
	}
	
	/**
	 * Method to load the MooTools framework into the document head
	 *
	 * If debugging mode is on an uncompressed version of MooTools is included for easier debugging.
	 *
	 * @param   string   $extras  MooTools file to load
	 * @param   boolean  $debug   Is debugging mode on? [optional]
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function framework($extras = false, $debug = null) {
		$type = $extras ? 'more' : 'core';
	
		// Only load once
		if (!empty(self::$loaded[__METHOD__][$type]))
		{
			return;
		}
	
		// If no debugging value is set, use the configuration setting
		if ($debug === null)
		{
			$config = JFactory::getConfig();
			$debug = $config->get('debug');
		}
	
		if ($type != 'core' && empty(self::$loaded[__METHOD__]['core']))
		{
			self::framework(false, $debug);
		}
	
		JHtml::_('script', 'system/mootools-' . $type . '.js', false, true, false, false, $debug);
		JHtml::_('script', 'system/core.js', false, true);
		self::$loaded[__METHOD__][$type] = true;
	
		return;
	}
	/**
	 * Internal method to get a JavaScript object notation string from an array
	 *
	 * @param   array  $array  The array to convert to JavaScript object notation
	 *
	 * @return  string  JavaScript object notation representation of the array
	 *
	 * @since   12.2
	 */
	public static function getJSObject(array $array = array())
	{
		$object = '{';
	
		// Iterate over array to build objects
		foreach ((array) $array as $k => $v)
		{
			if (is_null($v))
			{
				continue;
			}
	
			if (is_bool($v))
			{
				$object .= ' ' . $k . ': ';
				$object .= ($v) ? 'true' : 'false';
				$object .= ',';
			}
			elseif (!is_array($v) && !is_object($v))
			{
				$object .= ' ' . $k . ': ';
				$object .= (is_numeric($v) || strpos($v, '\\') === 0) ? (is_numeric($v)) ? $v : substr($v, 1) : "'" . str_replace("'", "\\'", trim($v, "'")) . "'";
				$object .= ',';
			}
			else
			{
				$object .= ' ' . $k . ': ' . self::getJSObject($v) . ',';
			}
		}
	
		if (substr($object, -1) == ',')
		{
			$object = substr($object, 0, -1);
		}
	
		$object .= '}';
	
		return $object;
	}
}
