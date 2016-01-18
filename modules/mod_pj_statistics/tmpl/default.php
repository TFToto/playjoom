<?php
/**
 * @package Joomla 3.0.x
 * @copyright	Copyright (C) 2005 - 2015 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Module
 * @copyright Copyright (C) 2010-2015 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// no direct access
defined('_JEXEC') or die;

$NumbersOfToal = PlayJoomModelStatistics::getCounts();

if (!$moduleclass_sfx) {
	$moduleclass = 'side-nav';
} else {
	$moduleclass = $moduleclass_sfx;
}
echo '<ul class="'.$moduleclass.'">';
    foreach ($list as $item)
    {
        $NumbersOfItem = PlayJoomModelStatistics::getCounts($item->id);

        if ($params->get('show_percentages') == 1){
        	$DispalyNumbers = ' | '.$NumbersOfItem.' Tracks';
        }
        else {
        	$DispalyNumbers = null;
        }

        if ($params->get('show_numbers') == 1
          && $NumbersOfItem >= 1
          && $NumbersOfToal >= 1){
            $DispalyPercentages = ' | '.round($NumbersOfItem / $NumbersOfToal * 100,1).'%';
        }
        else {
        	$DispalyPercentages = null;
        }

    	$genresting = base64_encode($item->category_title);
		$genrelink = JRoute::_('index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->id.'&Itemid='.JRequest::getVar('Itemid'));
		echo '<li>';
		echo '<a href="'.$genrelink.'">'.$item->category_title.$DispalyNumbers.$DispalyPercentages.'</a>';
	    echo '</li>';
	    echo '<li class="divider"></li>';
    }
echo '</ul>';
echo '<div class="modulefoot">';
echo 'Total: '.$NumbersOfToal.' Tracks';
echo '</div>';