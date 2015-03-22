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
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date: 2011-01-15 15:37:54 +0100 (Sa, 15 Jan 2011) $
 * @revision $Revision: 72 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/artist/tmpl/default_albums.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//Load JavaScripts for light box
JHtml::_('behavior.modal');

$app	= JFactory::getApplication();

$artist = base64_decode(JRequest::getVar('artist'));

    echo '<div class="trackbox">';
    echo '<div class="firstline">4 Albums | form 1999 to 2007</div>';
    echo JHtml::_('sliders.start', 'audiofile-slider');
	echo JHtml::_('sliders.panel',JText::_('COM_PLAYJOOM_ALBUM_MORE_ALBUMS'),'more');
	echo "textinhalt";
	echo JHtml::_('sliders.end');
	echo '</div>';



?>

<?php foreach($this->items as $i => $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <?php echo $item->albumid; ?>
                </td>
                <td>
                        <?php echo $item->albumyear; ?>
                        
                </td>
                <td>
                        <?php echo $item->albumtitle; ?>
                </td>
                <td>
                        <?php echo PlayJoomHelper::getPlaylistEntries($item->albumid); ?>
                </td>
        </tr>
<?php endforeach; ?>