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
 * @subpackage views.medialist.tmpl
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2012 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;
$user = JFactory::getUser();
?>
		<tr>
			<td class="imgTotal">
				<a href="index.php?option=com_playjoom&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo rtrim(strtr(base64_encode($this->state->parent), '+/', '-_'), '='); ?>" target="folderframe">
					<?php echo JHtml::_('image', 'media/folderup_16.png', '..', array('width' => 16, 'height' => 16), true); ?></a>
			</td>
			<td class="description">
				<a href="index.php?option=com_playjoom&amp;view=mediaList&amp;tmpl=component&amp;folder=<?php echo rtrim(strtr(base64_encode($this->state->parent), '+/', '-_'), '='); ?>" target="folderframe">..</a>
			</td>
			<td>&#160;</td>
		<?php if ($user->authorise('core.delete', 'com_playjoom')):?>
			<td>&#160;</td>
		<?php endif;?>
		</tr>
