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
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Trackvote plugin.
 *
 * @package		PlayJoom
 * @subpackage	plg_trackvote
 */
class plgPlayjoomTrackvote extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $params)
	{
		parent::__construct($subject, $params);
		$this->loadLanguage();
	}
		
    public function onInTrackbox($trackitems, $params)
	{
		$html = '';

		    $rating = intval($trackitems->rating);
			$rating_count = intval($trackitems->rating_count);
		   
			$img = '';

			// look for images in template if available
			$starImageOn = JHTML::_('image','system/rating_star.png', NULL, NULL, true);
			$starImageOff = JHTML::_('image','system/rating_star_blank.png', NULL, NULL, true);

			for ($i=0; $i < $rating; $i++) {
				$img .= $starImageOn;
			}
			for ($i=$rating; $i < 5; $i++) {
				$img .= $starImageOff;
			}
			
			$html .= '<div class="details_middle">';			
			if ($rating >= 1) 
			{
				$html .= '<span class="content_rating">';
			    $html .= JText::_( 'PLG_TRACKVOTE_USER_RATING' ) .':'. $img .'&#160;/&#160;';
			    $html .= $rating_count;
			    $html .= "</span>\n<br />\n";
			}

				$uri = JFactory::getURI();
				$uri->setQuery($uri->getQuery().'&hitcount=0&track_id='.$trackitems->id);

				$html .= '<form method="post" action="' . $uri->toString() . '">';
				$html .= '<span class="content_vote">';
				$html .= JText::_( 'PLG_PLAYJOOM_TRACKVOTE_POOR' );
				$html .= '<input type="radio" alt="vote 1 star" name="user_rating" value="1" />';
				$html .= '<input type="radio" alt="vote 2 star" name="user_rating" value="2" />';
				$html .= '<input type="radio" alt="vote 3 star" name="user_rating" value="3" checked="checked" />';
				$html .= '<input type="radio" alt="vote 4 star" name="user_rating" value="4" />';
				$html .= '<input type="radio" alt="vote 5 star" name="user_rating" value="5" />';
				$html .= JText::_( 'PLG_PLAYJOOM_TRACKVOTE_BEST' );
				$html .= '&#160;<input class="small button" type="submit" name="submit_vote" value="'. JText::_( 'PLG_PLAYJOOM_TRACKVOTE_RATE' ) .'" />';
				$html .= '<input type="hidden" name="task" value="vote" />';
				$html .= '<input type="hidden" name="hitcount" value="0" />';
				$html .= '<input type="hidden" name="url" value="'.  $uri->toString() .'" />';
				$html .= '</span>';
				$html .= '</form>';

                $html .= '</div>';
				
		return $html;
	}
}
