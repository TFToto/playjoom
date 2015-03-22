<?php 
/**
 * Contains the Viewer method for to collect all necessary data and assign it to the template output.
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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=savetracks').'" method="post" name="adminForm" id="adminForm">';
    
    echo '<input type="hidden" name="task" value="" />';
    echo '<input type="hidden" name="task_save" value="add_tracks" />';
    echo '<input type="text" name="selectedfolder" class="hiddenform" id="selectedfolder" value="selectedfolder" />';
    echo '<input type="hidden" name="boxchecked" value="0" />';
    echo JHtml::_('form.token');

echo '</form>';
?>

