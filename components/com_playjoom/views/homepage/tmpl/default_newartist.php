<?php
/**
 * @package Joomla 3.0
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo '<h4>'.JText::_('COM_PLAYJOOM_HOMEPAGE_NEWARTISTS').'</h4>';

echo '<ul class="circle">';
foreach($this->ArtistItems as $i => $item) {
	
	$artiststing = base64_encode($item->artist);
	$artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
	echo '<li><a href="'.$artistlink.'">'.$item->artist.'</a></li>';
}
echo '<ul>';