<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Media Component List Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @since 1.5
 */
class PlayJoomModelList extends JModelLegacy
{
	function getState($property = null, $default = null)
	{
		static $set;

		if (!$set) {
			$queryfolder = JRequest::getVar('folder');
			$folder = base64_decode($queryfolder); 
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}

		return parent::getState($property, $default);
	}

	function getImages()
	{
		$list = $this->getList();

		return $list['images'];
	}

	function getFolders()
	{
		$list = $this->getList();

		return $list['folders'];
	}

	function getDocuments()
	{
		$list = $this->getList();

		return $list['docs'];
	}

	/**
	 * Build imagelist
	 *
	 * @param string $listFolder The image directory to display
	 * @since 1.5
	 */
	function getList()
	{
		static $list;

		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'dW5kZWZpbmVk') {
			$current = '';
		}
		
		// Initialise variables.
		if (strlen($current) > 0) {
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$basePath = str_replace('\/', '\\', $current);
			} else {
				$basePath = PLAYJOOM_BASE_PATH.DIRECTORY_SEPARATOR.$current;
			}
		}
		else {
			$basePath = PLAYJOOM_BASE_PATH;
		}

		$mediaBase = str_replace(DIRECTORY_SEPARATOR, '/', PLAYJOOM_BASE_PATH.'/');

		$images		= array ();
		$folders	= array ();
		$docs		= array ();

		$fileList = false;
		$folderList = false;
		if (file_exists($basePath))
		{
			// Get the list of files and folders from the given folder
			$fileList	= JFolder::files($basePath);
			$folderList = JFolder::folders($basePath);
		}

		// Iterate over the files if they exist
		if ($fileList !== false) {
			foreach ($fileList as $file)
			{
				if (is_file($basePath.'/'.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
					$tmp = new JObject();
					$tmp->name = $file;
					$tmp->title = $file;
					$tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($basePath . '/' . $file));
					$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
					$tmp->size = filesize($tmp->path);

					$ext = strtolower(JFile::getExt($file));
					switch ($ext)
					{
						// Image
						case 'jpg':
						case 'png':
						case 'gif':
						case 'xcf':
						case 'odg':
						case 'bmp':
						case 'jpeg':
						case 'ico':
							$info = @getimagesize($tmp->path);
							$tmp->width		= @$info[0];
							$tmp->height	= @$info[1];
							$tmp->type		= @$info[2];
							$tmp->mime		= @$info['mime'];

							if (($info[0] > 60) || ($info[1] > 60)) {
								$dimensions = PlayJoomMediaHelper::imageResize($info[0], $info[1], 60);
								$tmp->width_60 = $dimensions[0];
								$tmp->height_60 = $dimensions[1];
							}
							else {
								$tmp->width_60 = $tmp->width;
								$tmp->height_60 = $tmp->height;
							}

							if (($info[0] > 26) || ($info[1] > 26)) {
								$dimensions = PlayJoomMediaHelper::imageResize($info[0], $info[1], 26);
								$tmp->width_26 = $dimensions[0];
								$tmp->height_26 = $dimensions[1];
							}
							else {
								$tmp->width_26 = $tmp->width;
								$tmp->height_26 = $tmp->height;
							}

							$images[] = $tmp;
							break;

						// Non-image document
						default:
							$tmp->icon_32 = "administrator/components/com_playjoom/images/mime-icon-32/".$ext.".png";
							$tmp->icon_16 = "administrator/components/com_playjoom/images/mime-icon-16/".$ext.".png";
							$docs[] = $tmp;
							break;
					}
				}
			}
		}

		// Iterate over the folders if they exist
		if ($folderList !== false) {
			foreach ($folderList as $folder)
			{
				$tmp = new JObject();
				$tmp->name = basename($folder);
				$tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($basePath . '/' . $folder));
				$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
				$count = PlayJoomMediaHelper::countFiles($tmp->path);
				$tmp->files = $count[0];
				$tmp->folders = $count[1];

				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders, 'docs' => $docs, 'images' => $images);

		return $list;
	}
}
