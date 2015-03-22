<?php
/**
 * Contains the default folder template for the media output.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage views.media.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2013-07-31 22:08:51 +0200 (Mi, 31 Jul 2013) $
 * @revision $Revision: 797 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/administrator/components/com_playjoom/views/savetracks/tmpl/default.php $
 */

// No direct access.
defined('_JEXEC') or die;

echo '<h2>'.JText::_('COM_PLAYJOOMUPDATE_INSTALLING_EXTENSIONS_IN_PROGRESS').'</h2>';

echo '<div id="progressbar"></div>';
echo '<div id="message">'.JText::_('COM_PLAYJOOMUPDATE_INSTALLING_EXTENSIONS_LOADING').'</div>';
echo '<div id="message_path"></div>';