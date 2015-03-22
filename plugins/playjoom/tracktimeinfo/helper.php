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


abstract class plgTracktimeinfoHelper
{
	/** 
     * ----------------------------------------------------------------------------------
     * Functions for Time and Date settings 
     * ----------------------------------------------------------------------------------
     */                                                                                        
     static function TimezoneSetting() {
     	
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
	    return plgTracktimeinfoHelper::UtcTimestamp() + plgTracktimeinfoHelper::TimezoneSetting();
     }

     /** 
      * ----------------------------------------------------------------------------------
      * Functions for hits calculation 
      * ----------------------------------------------------------------------------------
      */  	                                                                                      
	 function HitsAverage($hits_value, $PerDayMonthYear, $day_average, $month_average, $year_average) {
	  	 
	  	$value_days_of_counter   = plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"days");
		$value_months_of_counter = plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"months");
		$value_years_of_counter  = plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"years");
	                                                                           
		/*
		 * calculate hits average
		 */
		switch ($PerDayMonthYear) { 
			  case 'day' :
			       if ($value_days_of_counter < 1) {
			       	  return "1 "." ".JText::_( 'TCE_PLG_HITS_PLURAL_TIME' )." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_DAY' );
			       }
			       else {
			          $result_hits_average = round($hits_value / plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"days"),1); 
		              return $result_hits_average." ".JText::_( 'TCE_PLG_HITS_TIME' )." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_DAY' ); 
			       } 
		      break;
		      case 'month' :
	               if ($value_months_of_counter <= 2 
	                && $value_days_of_counter <= 31) {
	                  if ($value_days_of_counter < 1) {
	                  	 return "1 "." ".JText::_( 'TCE_PLG_HITS_PLURAL_TIME' )." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_DAY' );
			          }
			          else {
			             $result_hits_average = round($hits_value / plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"days"),1); 
		                 return $result_hits_average.' '.JText::_( 'TCE_PLG_HITS_TIME' )." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_DAY' );
			          }                               
				   }
				   else {
				      $result_hits_average = round($hits_value / plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"months"),1); 
		              return $result_hits_average." ".JText::_( 'TCE_PLG_HITS_TIME' )." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_month' ); 
				   } 
		      break;
			  case 'year' :
			       if ($value_years_of_counter <= 1) {
			          if ($value_months_of_counter <= 2 
			           && $value_days_of_counter <= 31) {
				         if ($value_days_of_counter < 1) {
			                return "1 "." ".JText::_( 'TCE_PLG_HITS_PLURAL_TIME' )." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_DAY' );
			             }
			             else {
			                $result_hits_average = round($hits_value / plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"days"),1); 
		                    return $result_hits_average." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_DAY' ); 
			             }
				      }
				      else {
				         $result_hits_average = round($hits_value / plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"months"),1); 
		                 return $result_hits_average." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_month' ); 
				      }
				   }
				   else {
				      $result_hits_average = round($hits_value / plgTracktimeinfoHelper::MonthsAndDaysCounter($day_average,$month_average,$year_average,"years"),1); 
		              return $result_hits_average." ".JText::_( 'TCE_PLG_HITS_AVERAGE_PER_year' ); 
				   }
		      break;
		      default :
				   return JText::_( 'TCE_PLG_ERROR_AVERAGE' ); 
		}
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
		                                                                   
		   for ($year_counter=$year; $year_counter<=date("Y",plgTracktimeinfoHelper::UserTimestamp()); $year_counter++) {
		      if ($year_counter == $year 
		       && $year_counter != date("Y",plgTracktimeinfoHelper::UserTimestamp())) {
		      	  
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
		           && $year_counter != date("Y",plgTracktimeinfoHelper::UserTimestamp())) {
		              /*
		               * Number of month for the next years *****************************************************
		               */
		      	      for ($month_counter=1; $month_counter<=12; $month_counter++) {
		      	         $sum_month = $sum_month +1;
		      	         $calc_num_day = mktime(0, 0, 0, $month_counter, 1, $year_counter);
		      	         $number_days_per_month = $number_days_per_month + date("t", $calc_num_day);
		      	      }
		      }
		      elseif ($year_counter == date("Y",plgTracktimeinfoHelper::UserTimestamp()) 
		      	   && $year != date("Y",plgTracktimeinfoHelper::UserTimestamp())) {
		      	      /*
		      	       * Numbers of months for the last year ***************************************
		      	       */
		      	      for ($month_counter=1; $month_counter<=date("m",plgTracktimeinfoHelper::UserTimestamp()); $month_counter++) {
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
		      	 for ($month_counter=$month; $month_counter<=date("m",plgTracktimeinfoHelper::UserTimestamp()); $month_counter++) {
		      	    $sum_month = $sum_month +1;
		      	    $calc_num_day = mktime(0, 0, 0, $month_counter, 1, $year_counter);
		      	    if ($sum_month == 1 
		      	     && $month != date("m",plgTracktimeinfoHelper::UserTimestamp())){
		      	        /*
		      	         * Days of the frist month****************************************************
		      	         */
		      	        $numbers_of_days_per_month = date("t", $calc_num_day) - $day;
		      	    }
		      	    elseif ($sum_month == 1 
		      	         && $month == date("m",plgTracktimeinfoHelper::UserTimestamp())) {
		      	            /*
		      	             * Days of the frist month****************************************************
		      	             */
		      	            $numbers_of_days_per_month = date("d",plgTracktimeinfoHelper::UserTimestamp()) - $day;
		      	    }	                                           	                                                                                             	                                       
		      	    elseif ($month_counter<=date("m",plgTracktimeinfoHelper::UserTimestamp())) {
		      	         /*
		      	          * Days of last month****************************************************
		      	          */
		      	         $numbers_of_days_per_month = $numbers_of_days_per_month + date("d",plgTracktimeinfoHelper::UserTimestamp());
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
          $plural_mon_year_replace_Array = Array("10&nbsp;".JText::_( 'TCE_PLG_DISTANCE_YEARS' ), 
                                                 "11&nbsp;".JText::_( 'TCE_PLG_DISTANCE_YEARS' ),
                                                 "11&nbsp;".JText::_( 'TCE_PLG_DISTANCE_YEARS' ),  
                                                 " ".JText::_( 'TCE_PLG_DISTANCE_ONEYEAR' ),                                                                                                                    
                                                 " ".JText::_( 'TCE_PLG_DISTANCE_ONEMONTH' )
                                           );
                                                                                    
   	      $start_time = strtotime($year_distance."-".$month_distance."-".$day_distance." ".$time_art); 
                                                                                    
          /*
           * Date and time stamp with Timezone correction
           */
   	      $start_time = $start_time + plgTracktimeinfoHelper::TimezoneServer();
          $current_time = plgTracktimeinfoHelper::UtcTimestamp() + plgTracktimeinfoHelper::TimezoneSetting(); //Current date and time stamp with UTC server correction
          $diff_time = $current_time - $start_time;
         
		  if (plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") <= 2 
		   && plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") >= 2) {
		       return " ".plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days")." ".JText::_( 'TCE_PLG_DISTANCE_DAYS' );
		  }
		  elseif (plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") <= 2 
		       && plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") == 0 
		       || plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") == 1 
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
          elseif (plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") <= 2 
               && plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"days") == 1) {
         	      // Next 2 Days after article created	      	                                           	                                       
		          $result_hour_min = " ".JText::_( 'TCE_PLG_DISTANCE_ONEDAY' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )." ".gmstrftime('%H '.JText::_( 'TCE_PLG_DISTANCE_HOURS' ).' %M '.JText::_( 'TCE_PLG_DISTANCE_MINUTES' ), $diff_time);
		          return $result_hour_min = preg_replace($plural_hours_minArray ,  $plural_hours_min_replace_Array , $result_hour_min);
		  }		                                           
		  elseif (plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") == 12) {
		 		  return " ".JText::_( 'TCE_PLG_DISTANCE_ONEYEAR' );
		  }
		  elseif (plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") > 12) {
		 	      $numbers_of_years = floor(plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") / 12); //immer auf ganze Zahl abgerundet! 
		 	      $numbers_of_rest_month = plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months") - 12*$numbers_of_years; //Berechnung der Restmonate zum vollem Jahr hin
		 	      $result_mon_year = $numbers_of_years." ".JText::_( 'TCE_PLG_DISTANCE_YEARS' )." ".JText::_( 'TCE_PLG_DISTANCE_AND' )." ".$numbers_of_rest_month." ".JText::_( 'TCE_PLG_DISTANCE_MONTHS' );
		 	      return $result_mon_year = preg_replace($plural_mon_yearArray ,  $plural_mon_year_replace_Array , $result_mon_year);
		  }
		  else {
		          return " ".plgTracktimeinfoHelper::MonthsAndDaysCounter($day_distance,$month_distance,$year_distance,"months")." ".JText::_( 'TCE_PLG_DISTANCE_MONTHSAGO' );
		  }
	   }
	      
     static function ConformDateFormat ($trackitems, $params, $format_option, $default_date = "1999-11-30 10:00:00") {
       	
       	 switch($format_option) {
              /*
               * Date / Time when item was added ***********************************************************************************************************************************************************************
               */
		      case "add" :
                   if ($trackitems->add_datetime == '0000-00-00 00:00:00'
                    || $trackitems->add_datetime == '') { //check if create value exists
			           $Addtrack_value = $default_date;
		           }
		           else {
			           $Addtrack_stamp = strtotime($trackitems->add_datetime) + plgTracktimeinfoHelper::TimezoneSetting() - plgTracktimeinfoHelper::TimezoneServer();
			           $Addtrack_value = date("Y-m-d H:i:s",$Addtrack_stamp);
		           }
		           
		           return JHTML::_('date',  $Addtrack_value,  JText::_($params->get('date_format', 'DATE_FORMAT_LC1')) );
		      break;
		      case "add_value" :
                   if ($trackitems->add_datetime == '0000-00-00 00:00:00'
                    || $trackitems->add_datetime == '') { //check if create value exists
			           $Addtrack_value = $default_date;
		           }
		           else {
			           $Addtrack_stamp = strtotime($trackitems->add_datetime) + plgTracktimeinfoHelper::TimezoneSetting() - plgTracktimeinfoHelper::TimezoneServer();
			           $Addtrack_value = date("Y-m-d H:i:s",$Addtrack_stamp);
		           }
		           return $Addtrack_value;
		      break;
		      
		      /*
               * Date / Time when item was modified ***********************************************************************************************************************************************************************
               */       	             
		      case "mod" :
                   if ($trackitems->mod_datetime == '0000-00-00 00:00:00'
                    || $trackitems->mod_datetime == '')  { //check if create value exists
			           return $default_date;
		           }
		           else {
			           $Modtrack_value = $trackitems->mod_datetime; 

			           return JHTML::_('date',  $Modtrack_value,  JText::_($params->get('date_format', 'DATE_FORMAT_LC1')));
		           }	           
		      break;
		      case "mod_value" :
                   if ($trackitems->mod_datetime == '0000-00-00 00:00:00'
                    || $trackitems->mod_datetime == '') { //check if create value exists
			           return $default_date;
		           }
		           else {
			           $Modtrack_stamp = strtotime($trackitems->mod_datetime) + plgTracktimeinfoHelper::TimezoneSetting() - plgTracktimeinfoHelper::TimezoneServer();
			           $Modtrack_value = date("Y-m-d H:i:s",$Modtrack_stamp);			  
		           }
		           return $Modtrack_value;
		           
		      break;
		      /*
               * Date / Time when item was played at last ***********************************************************************************************************************************************************************
               */       	             
		      case "access" :
                   if ($trackitems->access_datetime == '0000-00-00 00:00:00'
                    || $trackitems->access_datetime == '')  { //check if create value exists
			           return $default_date;
		           }
		           else {
			           $Accesstrack_value = $trackitems->access_datetime; 

			           return JHTML::_('date',  $Accesstrack_value,  JText::_($params->get('date_format', 'DATE_FORMAT_LC1')));
		           }	           
		      break;
		      case "access_value" :
                   if ($trackitems->access_datetime == '0000-00-00 00:00:00'
                    || $trackitems->access_datetime == '') { //check if create value exists
			           return $default_date;
		           }
		           else {
			           $Accesstrack_stamp = strtotime($trackitems->access_datetime) + plgTracktimeinfoHelper::TimezoneSetting() - plgTracktimeinfoHelper::TimezoneServer();
			           $Accesstrack_value = date("Y-m-d H:i:s",$Accesstrack_stamp);			  
		           }
		           return $Accesstrack_value;
		           
		      break;
		                                       	                                                        
		      default :
				   return JText::_( 'TCE_PLG_ERROR_NO_FORMAT_OPTION' );
		 }
     }

     static function GetTimeInfoList ($trackitems, $params, $Type) {

     	
        switch($Type) {
        	
        	case 'add' :
        		/*
        	     * Time info for Add Track
        	     */
                //Check for user name
		        if ($trackitems->add_user != '') {
			        $AddUserName = ' '.JText::_( 'PLG_PLAYJOOM_BY_USER' ).' '.$trackitems->add_user;
		        }
		        else {
			        $AddUserName = null;
		        }
		        /*
		         * *********DISTANCE********************
		         */
		        if ($params->get('viewtype_for_addtrack_time', 3) == 2
		         && $trackitems->add_datetime != ''
		         && $trackitems->add_datetime != '0000-00-00 00:00:00') {
		 				
		            $Display = '<li>'.
		                          JText::_( 'PLG_PLAYJOOM_ADD_TRACK' ).' '.
		                          plgTracktimeinfoHelper::AgeOfTrack(substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),8,2),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),5,2),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),0,4),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),11,8)).
		                          ' '.JText::_( 'TCE_PLG_DISTANCE_AGO' ).$AddUserName.
		                       '</li>';
		        }
		       /*
		        * ********* Combi of both ********************
		        */
		        elseif ($params->get('viewtype_for_addtrack_time', 3) ==  3
		             && $trackitems->add_datetime != '' 
		             && $trackitems->add_datetime != '0000-00-00 00:00:00') { 
		     
		             $Display = '<li>'.
		                           JText::_( 'PLG_PLAYJOOM_ADD_TRACK' ).' '.
		                           plgTracktimeinfoHelper::AgeOfTrack(substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),8,2),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),5,2),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),0,4),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add_value"),11,8)).' '.JText::_( 'TCE_PLG_DISTANCE_AGO' ).
		                           ' '.JText::_( 'PLG_PLAYJOOM_AT' ).' '.plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add").$AddUserName.
		                        '</li>';
		        }
		        /*
		         * ********* Just the Date ********************
		         */
		        elseif ($params->get('viewtype_for_addtrack_time', 3) ==  1
		             && $trackitems->add_datetime != '' 
		             && $trackitems->add_datetime != '0000-00-00 00:00:00') { 
			 
		             $Display = '<li>'.
			                       JText::_( 'PLG_PLAYJOOM_ADD_TRACK' ).' '.JText::_( 'PLG_PLAYJOOM_AT' ).' '.
			                       plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "add").
			                    '</li>';
		        }
		        else {
		            $Display = null;
		        }
		break;
        case 'mod' :
        		/*
        	     * Time info for Mod Track
        	     */
        	    //Check for user name
		        if ($trackitems->mod_user != '') {
			        $ModUserName = ' '.JText::_( 'PLG_PLAYJOOM_BY_USER' ).' '.$trackitems->mod_user;
		        }
		        else {
			        $ModUserName = null;
		        }
		        /*
		         * *********DISTANCE********************
		         */
		        if ($params->get('viewtype_for_modtrack_time', 0) == 2
		         && $trackitems->mod_datetime != ''
		         && $trackitems->mod_datetime != '0000-00-00 00:00:00') {
		 				
		            $Display = '<li>'.
		                          JText::_( 'PLG_PLAYJOOM_MOD_TRACK' ).' '.
		                          plgTracktimeinfoHelper::AgeOfTrack(substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),8,2),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),5,2),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),0,4),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),11,8)).
		                          ' '.JText::_( 'TCE_PLG_DISTANCE_AGO' ).$ModUserName.
		                       '</li>';
		        }
		       /*
		        * ********* Combi of both ********************
		        */
		        elseif ($params->get('viewtype_for_modtrack_time', 0) ==  3
		             && $trackitems->add_datetime != '' 
		             && $trackitems->add_datetime != '0000-00-00 00:00:00') { 
		     
		             $Display = '<li>'.
		                           JText::_( 'PLG_PLAYJOOM_MOD_TRACK' ).' '.
		                           plgTracktimeinfoHelper::AgeOfTrack(substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),8,2),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),5,2),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),0,4),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod_value"),11,8)).' '.JText::_( 'TCE_PLG_DISTANCE_AGO' ).
		                           ' '.JText::_( 'PLG_PLAYJOOM_AT' ).' '.plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod").$ModUserName.
		                        '</li>';
		        }
		        /*
		         * ********* Just the Date ********************
		         */
		        elseif ($params->get('viewtype_for_modtrack_time', 0) ==  1
		             && $trackitems->mod_datetime != '' 
		             && $trackitems->mod_datetime != '0000-00-00 00:00:00') { 
			 
		             $Display = '<li>'.
			                       JText::_( 'PLG_PLAYJOOM_MOD_TRACK' ).' '.JText::_( 'PLG_PLAYJOOM_AT' ).' '.
			                       plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "mod").
			                    '</li>';
		        }
		        else {
		            $Display = null;
		        }
		    break;		       
            
		    case 'access' :
        		/*
        	     * Time info for Access Track
        	     */
        	    
		        /*
		         * *********DISTANCE********************
		         */
		        if ($params->get('viewtype_for_access_time', 2) == 2
		         && $trackitems->access_datetime != ''
		         && $trackitems->access_datetime != '0000-00-00 00:00:00') {
		 				
		            $Display = '<li>'.
		                          JText::_( 'PLG_PLAYJOOM_ACCESS_TRACK' ).' '.
		                          plgTracktimeinfoHelper::AgeOfTrack(substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),8,2),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),5,2),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),0,4),
		                                                             substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),11,8)).
		                          ' '.JText::_( 'TCE_PLG_DISTANCE_AGO' ).
		                       '</li>';
		        }
		       /*
		        * ********* Combi of both ********************
		        */
		        elseif ($params->get('viewtype_for_access_time', 2) ==  3
		             && $trackitems->access_datetime != '' 
		             && $trackitems->access_datetime != '0000-00-00 00:00:00') { 
		     
		             $Display = '<li>'.
		                           JText::_( 'PLG_PLAYJOOM_ACCESS_TRACK' ).' '.
		                           plgTracktimeinfoHelper::AgeOfTrack(substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),8,2),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),5,2),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),0,4),
		                                                              substr(plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access_value"),11,8)).' '.JText::_( 'TCE_PLG_DISTANCE_AGO' ).
		                           ' '.JText::_( 'PLG_PLAYJOOM_AT' ).' '.plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access").
		                        '</li>';
		        }
		        /*
		         * ********* Just the Date ********************
		         */
		        elseif ($params->get('viewtype_for_access_time', 2) ==  1
		             && $trackitems->access_datetime != '' 
		             && $trackitems->access_datetime != '0000-00-00 00:00:00') { 
			 
		             $Display = '<li>'.
			                       JText::_( 'PLG_PLAYJOOM_ACCESS_TRACK' ).' '.JText::_( 'PLG_PLAYJOOM_AT' ).' '.
			                       plgTracktimeinfoHelper::ConformDateFormat($trackitems, $params, "access").
			                    '</li>';
		        }
		        else {
		            $Display = null;
		        }
		    break;		       
            }
        		
		return $Display;
     }
}
