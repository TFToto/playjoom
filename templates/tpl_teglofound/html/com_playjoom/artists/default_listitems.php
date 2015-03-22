<?php
/**
 * Contains the default template for the artist output.
 * 
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details. 
 * 
 * @package PlayJoom.Site
 * @subpackage views.artists.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2012-04-08 14:07:01 +0200 (So, 08. Apr 2012) $
 * @revision $Revision: 455 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/helpers/playjoom.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

$counter = null;

foreach($this->items as $i => $item) {
	
	$artiststing = base64_encode($item->artist);
	$artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
	
	$genresting = base64_encode($item->category_title);
	$genrelink = PlayJoomHelperRoute::getPJlink('genres','&cat='.$genresting.'&catid='.$item->catid);

	$counter = $counter + 1;
	
	echo '<li>'.sprintf ("%02d",$counter) .' <a href="'. $artistlink .'">'. $item->artist .'</a> (<a href="'. $genrelink .'">'. $item->category_title .'</a>)</li>';
	echo '<li class="divider"></li>';
}
echo '<p class="counter">';
	echo $this->pagination->getPagesCounter();
echo '</p>';
echo $this->pagination->getListFooter();