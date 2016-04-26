<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  mod_pj_lastplayed
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$app		= JFactory::getApplication();
$menuparams	= $app->getParams();

if (!$moduleclass_sfx) {
	$moduleclass = 'side-nav';
} else {
	$moduleclass = $moduleclass_sfx;
}

echo '<ul class="'.$moduleclass.'">';
	foreach ($list as $item) {
		if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file) && $item->accessinfo != '') {
			//Check for albumname as sampler
			if (PlayJoomHelper::checkForSampler($item->album, $item->artist)) {
				$artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
			} else {
				$artistname = $item->artist;
			}
			echo '<li>';
				echo $item->accessinfo.'<br /><a href="'.$item->link.'">'.$artistname.' - '.$item->title.'</a>';
			echo '</li>';

			echo '<li class="divider"></li>';
		}
	}
echo '</ul>';
