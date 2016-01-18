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
 * @PlayJoom Module
 * @copyright Copyright (C) 2010-2013 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modAlphabeticalBarHelper {

	static function getIndexHTML() {
		global $mainframe;
		
		
		$url_alphacontent = "index.php?option=com_playjoom&view=alphabetical&amp;Itemid=".JRequest::getVar('Itemid');
		$url_alphacontent = PlayJoomHelperRoute::getPJlink('alphabetical',null);

		$alphabeticalindex = @explode( ",", 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z');
		
		$alphabeticalbar = modAlphabeticalBarHelper::getAlphabeticalBarModule( $alphabeticalindex, $url_alphacontent );
		return array($alphabeticalbar);
		
	}

	static function getAlphabeticalBarModule( $ar_bar, $url ) {
		global $options;
		
		// build alphabetical bar
		$alphabar = "";		
		
		$linkletter = $url . "&amp;LetterForAlphabetical=";
		
		// specials chars
		if ( JRequest::getVar('LetterForAlphabetical') =='#' ) {		
			$alphabar .= "\r\n<b>#</b>\r\n";
		} else {
			$alphabar .= "\r\n<li><a href=\"".JRoute::_($linkletter.urlencode("#")) . "\" title=\"#\">#</a></li>\r\n";
		}
		
		$alphabar .= '';
		
		// numbers
		if ( JRequest::getVar('LetterForAlphabetical') =='0-9' ) {		
			$alphabar .= "\r\n<b>0-9</b>\r\n";
		} else {
			$alphabar .= "\r\n<li><a href=\"".JRoute::_($linkletter."0-9") . "\" title=\"0-9\">Nr</a></li>\r\n";
		}
		
		// letters
		$tagbr = 0;
		$class = "alphabarlink";
		for($i=0;$i<sizeof($ar_bar);$i++) {
			
			if ( JRequest::getVar('LetterForAlphabetical') == $ar_bar[$i] ) {
				$alphabar .= stripslashes('');
				$alphabar .= "<li class='active'>" . $ar_bar[$i] . "</li>";
			} else {
				if ( $ar_bar[$i]!=strtolower('<br />') && $ar_bar[$i]!=strtolower('<br />') ) {				
					if ( !$tagbr ) {
						$alphabar .= stripslashes('');						
					} else $tagbr = 0;					
					$alphabar .= "<li><a href=\"" . JRoute::_($linkletter . $ar_bar[$i]) . "\" title=\"" . $ar_bar[$i] . "\">" . $ar_bar[$i] . "</a></li>";
				} else {
					//$alphabar .= "<br />";
					$tagbr = 1;
				}
			}			
			$alphabar .= "\r\n";
		}
		
		return $alphabar;
		
	}

}
?>