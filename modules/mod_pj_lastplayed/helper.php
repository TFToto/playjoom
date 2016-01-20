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
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_playjoom/helpers/route.php';

jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_playjoom/models');

abstract class modLastPlayedHelper
{
	public static function getList(&$params)
	{
		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic tracks model
		$model = JModelLegacy::getInstance('Sections', 'PlayjoomModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));

		// Access filter
		$access = !JComponentHelper::getParams('com_playjoom')->get('show_noauth', 1);
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		
		$ordering = 'a.access_datetime';
		$dir = 'DESC';

		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);

		$items = $model->getItems();

		//create item link
		foreach ($items as &$item) 
		{
			//Check for Trackcontrol
		    if (JPluginHelper::isEnabled('playjoom','trackcontrol')==false)
	        {
	         	$item->link = null; 
	        }
	        else 
	        {
	          	$item->link = JRoute::_('index.php?option=com_playjoom&view=broadcast&id='.$item->id);
	        }
	        $item->accessinfo = modLastPlayedHelper::GetTimeInfoList($item->access_datetime, $params, 'access');
	    }
		

		return $items;
	}
	
/** 
     * ----------------------------------------------------------------------------------
     * Functions for Time and Date settings 
     * ----------------------------------------------------------------------------------
     */                                                                                        
     static function TimezoneSetting () {
     	
     	$config = JFactory::getConfig();     	
     	$tz = new DateTime('now', new DateTimeZone($config->get('offset')));
     	
        if(isset($_COOKIE["usertimezone"])) {
        	
        	//Gets Timezone factor from User Browser as a cookie entry
	        //The timezone will be returned as timestamp 
            return $_COOKIE["usertimezone"]*3600;
        }
        
        else {
            //If cookie doen't exsist, it will returned the timezone factor from the web server
            return $tz->getOffset();
        }
     }

     static function TimezoneServer () {
	    
     	//Timezone setting from the websever in Joomla as timestamp
        $config = JFactory::getConfig();
        $tz = new DateTime('now', new DateTimeZone($config->get('offset')));
        
        return $tz->getOffset();
                                    
     }

     static function UtcTimestamp () {
	    
     	//Timestamp as UTC
	    $config = JFactory::getConfig();
	    $tz = new DateTime('now', new DateTimeZone($config->get('offset')));
	    
	    return time() -$tz->getOffset();
     }

     static function UserTimestamp () {
	    
     	//Current Timestamp for the User, if cookie exsist. Otherwise it will returned the normal timestamp value, the same like time() value. 
	    return modLastPlayedHelper::UtcTimestamp() + modLastPlayedHelper::TimezoneSetting();
     }

                                    
                                          
      /**
       * Returned results for the MonthsAndDaysCounter function
       * value for "days"               return $numbers_of_days_per_month
       * value for "months"             return $sum_month
       * value == "years"               return floor($sum_month/12)	                                        	                        
       * If value incorrect or missing  return JText::_( 'TCE_PLG_ERROR_AVERAGE' )
	   */                           
	 static function MonthsAndDaysCounter ($day, $month, $year, $value) {
	   	  
	   	   $sum_month = 0;
		   $number_days_per_month = 0;
		   $numbers_of_days_per_month = 0;
		                                                                   
		   for ($year_counter=$year; $year_counter<=date("Y",modLastPlayedHelper::UserTimestamp()); $year_counter++) {
		      if ($year_counter == $year 
		       && $year_counter != date("Y",modLastPlayedHelper::UserTimestamp())) {
		      	  
		      	  /*
		      	   * Number of month of the first year *****************************************
		      	   */
		      	  for ($month_counter=$month+1; $month_counter<=12; $month_counter++) {
		      	   	 $sum_month = $sum_month +1;
		      	     $calc_num_day = mktime(0, 0, 0, $month_counter, 1, $year_counter);
		      	     $number_days_per_month = $number_days_per_month + date("t", $calc_num_day);
		      	  }
		      }	
		      elseif ($year_counter >= $year 
		           && $year_counter != date("Y",modLastPlayedHelper::UserTimestamp())) {
		              /*
		               * Number of month for the next years *****************************************************
		               */
		      	      for ($month_counter=1; $month_counter<=12; $month_counter++) {
		      	         $sum_month = $sum_month +1;
		      	         $calc_num_day = mktime(0, 0, 0, $month_counter, 1, $year_counter);
		      	         $number_days_per_month = $number_days_per_month + date("t", $calc_num_day);
		      	      }
		      }
		      elseif ($year_counter == date("Y",modLastPlayedHelper::UserTimestamp()) 
		      	   && $year != date("Y",modLastPlayedHelper::UserTimestamp())) {
		      	      /*
		      	       * Numbers of months for the last year ***************************************
		      	       */
		      	      for ($month_counter=1; $month_counter<=date("m",modLastPlayedHelper::UserTimestamp()); $month_counter++) {
		      	         $sum_month = $sum_month +1;
		      	         $calc_num_day = mktime(0, 0, 0, $month_counter, 1, $year_counter);
		      	         $number_days_per_month = $number_days_per_month + date("t", $calc_num_day);
		      	      }
		      }
		      else {
		      	 /*
		      	  * If only one year
		      	  */
		      	 $numbers_of_days_per_month = 0;
		      	 for ($month_counter=$month; $month_counter<=date("m",modLastPlayedHelper::UserTimestamp()); $month_counter++) {
		      	    $sum_month = $sum_month +1;
		      	    $calc_num_day = mktime(0, 0, 0, $month_counter, 1, $year_counter);
		      	    if ($sum_month == 1 
		      	     && $month != date("m",modLastPlayedHelper::UserTimestamp())){
		      	        /*
		      	         * Days of the frist month****************************************************
		      	         */
		      	        $numbers_of_days_per_month = date("t", $calc_num_day) - $day;
		      	    }
		      	    elseif ($sum_month == 1 
		      	         && $month == date("m",modLastPlayedHelper::UserTimestamp())) {
		      	            /*
		      	             * Days of the frist month****************************************************
		      	             */
		      	            $numbers_of_days_per_month = date("d",modLastPlayedHelper::UserTimestamp()) - $day;
		      	    }	                                           	                                                                                             	                                       
		      	    elseif ($month_counter<=date("m",modLastPlayedHelper::UserTimestamp())) {
		      	         /*
		      	          * Days of last month****************************************************
		      	          */
		      	         $numbers_of_days_per_month = $numbers_of_days_per_month + date("d",modLastPlayedHelper::UserTimestamp());
		      	    }
		      	    else {
		      	         /*
		      	          * Days of a complete month*******************************************************************
		      	          */
		      	         $calc_num_day = mktime(0, 0, 0, $month_counter, 1, $year_counter);
		      	         $numbers_of_days_per_month = $numbers_of_days_per_month + date("t", $calc_num_day);
		      	    }
		      	 } 
		      }                                                            
	       }
	       if ($value == "days") {
	          return $numbers_of_days_per_month;	                                                              
	       }
	       elseif ($value == "months") {
	          return $sum_month;
	       }
	       elseif ($value == "years") {	                 
	          return floor($sum_month/12);	                                        	                        
	       }
	       else {
	          return JText::_( 'TCE_PLG_ERROR_AVERAGE' );
	       }
	   }
	   
	   /**
	    * 
	    */
	 static function AgeOfTrack ($day_distance, $month_distance, $year_distance, $time_art) { 
		   /*
		    * result of the age of the article ************************************************************
		    */
   	       $config = JFactory::getConfig();
   	       //$plural_hours_minArray = Array("/x/");
   	                                                                                
   	      $plural_hours_minArray = Array("/10 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )."/",
   	                                     "/11 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )."/",
   	                                     "/20 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )."/",
   	                                     "/21 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )."/",
   	                                     "/0 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )."/",
   	                                     "/00 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )."/",
   	                                     "/1 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )."/"
   	                               );
          $plural_hours_min_replace_Array = Array("10 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' ),
                                                  "11 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' ),
                                                  "20 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' ),
                                                  "21 ".JText::_( 'TCE_PLG_DISTANCE_HOURS' ),
                                                  " ",
                                                  " ",
                                                  " ".JText::_( 'TCE_PLG_DISTANCE_ONEHOURS' )
                                            );                                                                                 
		  $plural_mon_yearArray = Array("/10 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )." 0 ".JText::_( 'TCE_PLG_DISTANCE_MONTHS' )."/",
		                                "/10 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' )."/",
		                                "/11 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )." 0 ".JText::_( 'TCE_PLG_DISTANCE_MONTHS' )."/",
		                                "/11 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' )."/",
		                                "/1 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' )."/",
		                                "/1 ".JText::_( 'TCE_PLG_DISTANCE_MONTHS' )."/"
		                          );
          $plural_mon_year_replace_Array = Array("10 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' ), 
                                                 "11 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' ),
                                                 "11 ".JText::_( 'TCE_PLG_DISTANCE_YEARS' ),  
                                                 " ".JText::_( 'TCE_PLG_DISTANCE_ONEYEAR' ),                                                                                                                    
                                                 " ".JText::_( 'TCE_PLG_DISTANCE_ONEMONTH' )
                                           );
                                                                                    
   	      $start_time = strtotime($year_distance."-".$month_distance."-".$day_distance." ".$time_art); 
                                                                                    
          /*
           * Date and time stamp with Timezone correction
           */
   	      $start_time = $start_time + modLastPlayedHelper::TimezoneServer();
          $current_time = modLastPlayedHelper::UtcTimestamp() + modLastPlayedHelper::TimezoneSetting(); //Current date and time stamp with UTC server correction
          $diff_time = $current_time - $start_time;
         
		  if (modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") <= 2 
		   && modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") >= 2) {
		       return " ".modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days")."&nbsp;".JText::_( 'TCE_PLG_DISTANCE_DAYS' );
		  }
		  elseif (modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") <= 2 
		       && modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") == 0 
		       || modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") == 1 
		       && $diff_time <= 86400) {
		 	      /*
		 	       * diff_time = 60s = 1Mnin, and so on...  
		 	       * self day
		 	       */
		 	      if ($diff_time <= 120) {
		             return " ".JText::_( 'TCE_PLG_DISTANCE_ONEMINUTE' );
		          }
		          elseif ($diff_time >= 121 ) { 
		             $result_hour_min = floor($diff_time/3600)." ".JText::_( 'TCE_PLG_DISTANCE_HOURS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )." ".floor($diff_time/60-floor($diff_time/3600)*60)." ".JText::_( 'TCE_PLG_DISTANCE_MINUTES' );
		             return $result_hour_min = preg_replace($plural_hours_minArray ,  $plural_hours_min_replace_Array , $result_hour_min);
		             //ohne Korrektur
		             //return $result_hour_min;
		          }
		  } 
          elseif (modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") <= 2 
               && modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") == 1) {
         	      // Next 2 Days after article created	      	                                           	                                       
		          $result_hour_min = " ".JText::_( 'TCE_PLG_DISTANCE_ONEDAY' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )." ".gmstrftime('%H '.JText::_( 'TCE_PLG_DISTANCE_HOURS' ).' %M '.JText::_( 'TCE_PLG_DISTANCE_MINUTES' ), $diff_time);
		          return $result_hour_min = preg_replace($plural_hours_minArray ,  $plural_hours_min_replace_Array , $result_hour_min);
		  }		                                           
		  elseif (modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") == 12) {
		 		  return " ".JText::_( 'TCE_PLG_DISTANCE_ONEYEAR' );
		  }
		  elseif (modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") > 12) {
		 	      $numbers_of_years = floor(modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") / 12); //immer auf ganze Zahl abgerundet! 
		 	      $numbers_of_rest_month = modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") - 12*$numbers_of_years; //Berechnung der Restmonate zum vollem Jahr hin
		 	      $result_mon_year = $numbers_of_years." ".JText::_( 'TCE_PLG_DISTANCE_YEARS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )." ".$numbers_of_rest_month." ".JText::_( 'TCE_PLG_DISTANCE_MONTHS' );
		 	      return $result_mon_year = preg_replace($plural_mon_yearArray ,  $plural_mon_year_replace_Array , $result_mon_year);
		  }
		  else {
		          return " ".modLastPlayedHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months")." ".JText::_( 'TCE_PLG_DISTANCE_MONTHSAGO' );
		  }
	   }
	      
     static function ConformDateFormat ($AccessDateTime, $params, $format_option, $default_date = "1999-11-30 10:00:00") {
       	
       	 switch($format_option) {
              /*
               * Date / Time when item was played at last ***********************************************************************************************************************************************************************
               */       	             
		      case "access" :
                   if ($AccessDateTime == '0000-00-00 00:00:00'
                    || $AccessDateTime == '')  { //check if create value exists
			           return $default_date;
		           }
		           else {
			           $Accesstrack_value = $AccessDateTime; 

			           return JHTML::_('date',  $Accesstrack_value,  JText::_($params->get('date_format')));
		           }	           
		      break;
		      case "access_value" :
                   if ($AccessDateTime == '0000-00-00 00:00:00'
                    || $AccessDateTime == '') { //check if create value exists
			           return $default_date;
		           }
		           else {
			           $Accesstrack_stamp = strtotime($AccessDateTime) + modLastPlayedHelper::TimezoneSetting() - modLastPlayedHelper::TimezoneServer();
			           $Accesstrack_value = date("Y-m-d H:i:s",$Accesstrack_stamp);			  
		           }
		           return $Accesstrack_value;
		           
		      break;
		                                       	                                                        
		      default :
				   return JText::_( 'TCE_PLG_ERROR_NO_FORMAT_OPTION' );
		 }
     }

     static function GetTimeInfoList($AccessDateTime, $params, $Type) {

           		/*
        	     * Time info for Access Track
        	     */
        	    
		        /*
		         * *********DISTANCE********************
		         */
		        if ($params->get('viewtype_for_access_time', 2) == 2
		         && $AccessDateTime != ''
		         && $AccessDateTime != '0000-00-00 00:00:00') {
		 				         
		            $Display = '<br />'.modLastPlayedHelper::AgeOfTrack(substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),8,2),
		                                                             substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),5,2),
		                                                             substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),0,4),
		                                                             substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),11,8)).'&nbsp;'.JText::_( 'TCE_PLG_DISTANCE_AGO' );
		        }
		       /*
		        * ********* Combi of both ********************
		        */
		        elseif ($params->get('viewtype_for_access_time', 2) ==  3
		             && $AccessDateTime != '' 
		             && $AccessDateTime != '0000-00-00 00:00:00') { 
		     
		             $Display = '<br />'.modLastPlayedHelper::AgeOfTrack(substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),8,2),
		                                                              substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),5,2),
		                                                              substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),0,4),
		                                                              substr(modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access_value"),11,8)).'&nbsp;'.JText::_( 'TCE_PLG_DISTANCE_AGO' ).
		                           '&nbsp;'.JText::_( 'MOD_PLAYJOOM_AT' ).' '.modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access");
		        }
		        /*
		         * ********* Just the Date ********************
		         */
		        elseif ($params->get('viewtype_for_access_time', 2) ==  1
		             && $AccessDateTime != '' 
		             && $AccessDateTime != '0000-00-00 00:00:00') { 
			 
		             $Display = '<br />'.modLastPlayedHelper::ConformDateFormat($AccessDateTime, $params, "access");
		        }
		        else {
		            $Display = null;
		        }
        		
		return $Display;
     }
}