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
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2012-05-06 13:09:02 +0200 (So, 06 Mai 2012) $
 * @revision $Revision: 522 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/administrator/components/com_playjoom/views/cpanel/tmpl/default.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

require_once dirname(__FILE__).'/helper.php';

$buttons = QuickIconHelper::getButtons();

echo '<div class="row-striped">';
echo '<div id="cpanel">';
foreach ($buttons as $button) {
	echo '<div class="row-fluid">';
            echo '<div class="span12">';
                echo '<a href="'.$button['link'].'" '.$button['params'].'>';
                echo '<img src="'.JURI::root(true).$button['imagePath'].$button['image'].'" alt="' .$button['image']. '"/>';
                echo '<span>'.$button['text'].'</span></a>';
            echo '</div>';
    echo '</div>';
  }
echo '</div>';
echo '</div>';