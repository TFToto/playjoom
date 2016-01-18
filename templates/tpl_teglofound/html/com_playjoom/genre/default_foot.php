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
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2013-09-10 19:13:25 +0200 (Di, 10 Sep 2013) $
 * @revision $Revision: 842 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/genre/tmpl/default_foot.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

echo '<tr>';
if ($this->params->get('numbers_of_titles_genre', 8) == 0 
 || $this->params->get('numbers_of_titles_genre', 8) == '')
{
    echo '<td class="sectiontablefooter" colspan="4">'.$this->pagination->getListFooter().'</td>';
}
else 
{
	echo '<td colspan="5">'.$this->pagination->getListFooter().'</td>';
}
echo '</tr>';