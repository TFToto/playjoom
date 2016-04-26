<?php
/**
 * @package     PlayJoom.Library
 * @subpackage  DateTime
 *
 * @copyright   Copyright (C) 2010 - 2016 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * PJDateTime Class.
 *
 * @package     PlayJoom.Library
 * @subpackage  DateTime
 * @since       0.11.0
 */
class PJDatetime {
	
	public static function getDateTimeInterval($access_time) {

		$user_datetime = new DateTime(date('Y-m-d H:i:s',self::UtcTimestamp()));
		$dateTimeEarlier = DateTime::createFromFormat('Y-m-d H:i:s', $access_time);
		
		$dateInterval = $user_datetime->diff($dateTimeEarlier);
	//echo var_dump($dateInterval);	
		switch(true) {
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 0 && $dateInterval->h == 0 && $dateInterval->i == 0):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_JUST_NOW_AGO');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 0 && $dateInterval->h == 0 && $dateInterval->i == 1):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_MINUTE_AGO');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 0 && $dateInterval->h == 0 && $dateInterval->i >= 2):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_MINUTES_AGO',$dateInterval->i);
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 0 && $dateInterval->h == 1 && $dateInterval->i == 0):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_HOUR_AGO');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 0 && $dateInterval->h == 1 && $dateInterval->i == 1):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_HOUR_AND_ONE_MINUTES_AGO');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 0 && $dateInterval->h == 1 && $dateInterval->i >= 2):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_ONE_HOUR_AND_N_MINUTES_AGO',$dateInterval->i);
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 0 && $dateInterval->h >= 2):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_HOURS_AND_N_MINUTES_AGO',$dateInterval->h, $dateInterval->i);
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 1 && $dateInterval->h == 0):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_DAY_AGO');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 1 && $dateInterval->h == 1):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_DAY_AND_ONE_HOUR_AGO');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 1 && $dateInterval->h >= 2):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_ONE_DAY_AND_N_HOURS_AGO',$dateInterval->h);
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d >= 2 && $dateInterval->h <= 1):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_DAYS_AND_ONE_HOUR_AGO',$dateInterval->d);
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d >= 2 && $dateInterval->d <= 6 && $dateInterval->h >= 2):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_DAYS_AND_N_HOURS_AGO',$dateInterval->d,$dateInterval->h);
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 7):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_WEEK');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 8):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_WEEK_AND_A_DAY');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d >= 9 && $dateInterval->d <= 13):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_ONE_WEEK_AND_N_DAYS',($dateInterval->d - 7));
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 14):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_TWO_WEEKS');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 15):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_TWO_WEEKS_AND_A_DAY');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d >= 16 && $dateInterval->d <= 20):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_TWO_WEEKS_AND_N_DAYS',($dateInterval->d - 14));
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 21):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_THREE_WEEKS');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d == 22):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_THREE_WEEKS_AND_A_DAY');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d >= 23 && $dateInterval->d <= 27):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_THREE_WEEKS_AND_N_DAYS',($dateInterval->d - 21));
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d >= 28):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_FOUR_WEEKS');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 1 && $dateInterval->d >= 1 && $dateInterval->d <= 6):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_MONTH_AND_ONE_WEEK');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 1 && $dateInterval->d >= 7 && $dateInterval->d <= 14):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_MONTH_AND_TWO_WEEKS');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m == 1 && $dateInterval->d >= 15):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_MONTH_AND_THREE_WEEKS');
			break;
		    case($dateInterval->y == 0 && $dateInterval->m >= 2&& $dateInterval->d <= 14):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_MONTHS', $dateInterval->m);
			break;
		    case($dateInterval->y == 0 && $dateInterval->m >= 2 && $dateInterval->d >= 15):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_MORE_THAN_N_MONTHS', $dateInterval->m);
			break;
		    case($dateInterval->y == 1 && $dateInterval->m == 0 && $dateInterval->d <= 15):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_YEAR');
			break;
		    case($dateInterval->y == 1 && $dateInterval->m == 0 && $dateInterval->d >= 16):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_MORE_THAN_ONE_YEAR');
			break;
		    case($dateInterval->y == 1 && $dateInterval->m == 1 && $dateInterval->d <= 15):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_YEAR_AND_A_MONTH');
			break;
		    case($dateInterval->y == 1 && $dateInterval->m == 1 && $dateInterval->d >= 16):
			return (string) JText::_('LIB_PLAYJOOM_DATETIME_ONE_YEAR_AND_MORE_THAN_A_MONTH');
			break;
		    case($dateInterval->y == 1 && $dateInterval->m >= 2 && $dateInterval->d <= 15):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_ONE_YEAR_AND_N_MONTHS',$dateInterval->y,$dateInterval->m);
			break;
		    case($dateInterval->y == 1 && $dateInterval->m >= 2 && $dateInterval->d >= 16):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_ONE_YEAR_AND_MORE_THAN_N_MONTHS',$dateInterval->y,$dateInterval->m);
			break;
		    case($dateInterval->y >= 2 && $dateInterval->m == 1 && $dateInterval->d <= 15):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_YEARS_AND_A_MONTH',$dateInterval->y);
			break;
		    case($dateInterval->y >= 2 && $dateInterval->m == 1 && $dateInterval->d >= 16):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_YEARS_AND_MORE_THAN_A_MONTH',$dateInterval->y);
			break;
		    case($dateInterval->y >= 2 && $dateInterval->m >= 2 && $dateInterval->d <= 15):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_YEARS_AND_N_MONTHS',$dateInterval->y,$dateInterval->m);
			break;
		    case($dateInterval->y >= 2 && $dateInterval->m >= 2 && $dateInterval->d >= 16):
			return (string) JText::sprintf('LIB_PLAYJOOM_DATETIME_N_YEARS_AND_MORE_THAN_N_MONTHS',$dateInterval->y,$dateInterval->m);
			break;
		    default:
			return $access_time;
			break;
		    
		}
	}

	/**Method for to get UTC Unix Timestamp
	 * 
	 * @return timestamp
	 */
	static function UtcTimestamp () {
	    
	    //Timestamp as UTC
	    $config = JFactory::getConfig();
	    $tz = new DateTime('now', new DateTimeZone($config->get('offset')));
	    
	    return time() -$tz->getOffset();
	}
}
