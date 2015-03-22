<?php
/**
 * Contains Config settings for PlayJoom.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date: 2013-05-13 21:31:31 +0200 (Mo, 13 Mai 2013) $
 * @revision $Revision: 780 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/helpers/playjoom.php $
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * Contains Config settings for PlayJoom
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.root
 */
class PJConfigUpdate {
	public $PJUpdate_extension_id = 10001;
	public $PJUpdate_component_id = 10000;
	public $PJ_Update_timeout = 3600;
	public $PJ_Update_updateURL_beta = 'http://files.teglo.info/playjoom/beta_release_list.xml';
	public $PJ_Update_updateURL_stable = 'http://files.teglo.info/playjoom/stable_release_list.xml';
	public $PJ_Update_updateURL_testing = 'http://files.teglo.info/playjoom/testing_release_list.xml';
}