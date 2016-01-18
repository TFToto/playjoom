<?php
/**
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom
 *
 * @copyright Copyright (C) 2010-2016 by www.playjoom.org
 * @license http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo '<div class="albumsview">';
	echo '<h4>'.JText::_('COM_PLAYJOOM_HOMEPAGE_NEWALBUMS').'</h4>';
	echo '<ul class="list_of_albums"></ul>';
echo '</div>';