<?php
/**
 * @package Joomla 1.6.x
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/album/tmpl/default_cover.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$item = (object) array('artist' => base64_decode(JRequest::getVar('artist')), 'album' => base64_decode(JRequest::getVar('album')));

if ($this->params->get(JRequest::getVar('view').'_show_cover', 1) == 1) {
	
	$Cover = new PlayJoomHelperCover();
		
	$CoverData = $Cover->getAlbumCover($item);
		
	if ($this->params->get('save_cover_tmp', 0) == 1) {
		
		$file = JApplication::stringURLSafe($item->artist.'-'.$item->album);
		$path = $Cover->getFilePath();
			
		if (!$Cover->checkFileExists($path, $file, $this->params)) {
			$Cover->createCoverfile($CoverData, $path, $file, $this->params);
		}
	}
			
	$coverthumb = $Cover->getCoverThumb($CoverData, $item, $this->params);
		
} else {
	$coverthumb = null;
}

//Output cover image
echo $coverthumb;