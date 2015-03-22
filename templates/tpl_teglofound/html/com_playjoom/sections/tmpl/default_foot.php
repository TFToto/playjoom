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
 * @date $Date: 2010-12-22 18:09:25 +0100 (Mi, 22 Dez 2010) $
 * @revision $Revision: 40 $
 * @author $Author: toto $
 * @headurl $HeadURL: http://dev.teglo.info/svn/playjoom/components/com_playjoom/views/sections/tmpl/default_foot.php $
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
switch($this->params->get('show_section')) {
		                case 'artist' : 
		                               ?>
		                               <tr>
                                          <td class="sectiontablefooter" colspan="3"><?php echo $this->pagination->getListFooter(); ?></td>
                                       </tr>
		                               <?php 
		                break;
		                case 'album' :
		                               ?>
		                               <tr>
                                          <td class="sectiontablefooter" colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
                                       </tr>
		                               <?php
		                break;
		                case 'year' :
		                               ?> 
		                               <tr>
                                          <td class="sectiontablefooter" colspan="2"><?php echo $this->pagination->getListFooter(); ?></td>
                                       </tr>
		                               <?php
		                break;
		                default :
				                 ?>
				                 <tr>
                                    <td class="sectiontablefooter" colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
                                 </tr>
				                 <?php
		               }