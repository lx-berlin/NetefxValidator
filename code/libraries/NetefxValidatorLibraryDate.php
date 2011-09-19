<?php

/**
* LibraryFunctions for Dates for NetefxValidator
* 
* @version 0.7 (19.09.2011)
* @package NetefxValidator
*/

class NetefxValidatorLibraryDate {


// ************************ Often used functions for validation **********************
		
     /** Date is at least x days in past
       *        
       * $rule_past_1    = new NetefxValidatorRuleFUNCTION ("End", "End must be in past",'error',
       *          											array('NetefxValidatorLibraryDate', 'DateIsMinDaysBeforeToday', array('date'  => 'End', 'min'   => 1)));               
    */
        
        static function DateIsMinDaysBeforeToday ($data, $args) {
        	$field_date = $args["date"];
            
            // A) date is defined as a date (YYYY-MM-DD)
            if (preg_match('/[\d]{4}+[\-]+[\d]{2}+[\-]+[\d]{2}/',$field_date)) {
                $date = $args["date"];  
            }
            // B) date is defined as the name of another inputfield
            else {
                $date = $data[$field_date];  
            }
            
            $today1 = getdate();
            $today = $today1["year"]."-".$today1["mon"]."-".$today1["mday"];
                        
            $min = (int)$args["min"];
            
            if ($date=="") return false;
            
            $timestamp_dateFrom  = strtotime($date);
            $timestamp_dateUntil = strtotime($today);
            $days_dif = ($timestamp_dateUntil - $timestamp_dateFrom)/86400;
            
            return ($days_dif >= $min);       
        }
        
        
     /** Date is at least x days in future
       * 
       * $rule_future_1    = new NetefxValidatorRuleFUNCTION ("Begin", "Begin must be at least for days in future",'error',
       *          											array('NetefxValidatorLibraryDate', 'DateIsMinDaysAfterToday', array('date'  => 'Begin', 'min'   => 4)));               
       *                      
    */
        
        static function DateIsMinDaysAfterToday ($data, $args) {
        	$field_date = $args["date"];
            
            // A) date is defined as a date (YYYY-MM-DD)
            if (preg_match('/[\d]{4}+[\-]+[\d]{2}+[\-]+[\d]{2}/',$field_date)) {
                $date = $args["date"];  
            }
            // B) date is defined as the name of another inputfield
            else {
                $date = $data[$field_date];  
            }
            
            $today1 = getdate();
            $today = $today1["year"]."-".$today1["mon"]."-".$today1["mday"];
                        
            $min = (int)$args["min"];
            
            if ($date=="") return false;
            
            $timestamp_dateUntil  = strtotime($date);
            $timestamp_dateFrom = strtotime($today);
            $days_dif = ($timestamp_dateUntil - $timestamp_dateFrom)/86400;
            
            return ($days_dif >= $min);       
        }
        
        /** date B is at least x days after date A
         *     
         * $rule_time_1    = new NetefxValidatorRuleFUNCTION ("End", "Begin must not be after End",'error',
         *          											array('NetefxValidatorLibraryDate', 'UntilIsMinDaysAfterFrom', array('dateFrom'  => 'Begin', 'dateUntil' => 'End', 'min'   => 0)));               
         *      
         */
        
        static function UntilIsMinDaysAfterFrom ($data, $args) {
            
            $field_dateFrom = $args["dateFrom"];
            
            // A) dateFrom is defined as a date (YYYY-MM-DD)
            if (preg_match('/[\d]{4}+[\-]+[\d]{2}+[\-]+[\d]{2}/',$field_dateFrom)) {
                $dateFrom = $args["dateFrom"];  
            }
            // B) dateFrom is defined as the name of another inputfield
            else {
                $dateFrom = $data[$field_dateFrom];  
            }
            
            $dateUntil = $data[$args["dateUntil"]];
            $min = (int)$args["min"];
            
            if (($dateFrom=="") OR ($dateUntil=="")) return false;
            
            $timestamp_dateFrom  = strtotime($dateFrom);
            $timestamp_dateUntil = strtotime($dateUntil);
            $days_dif = ($timestamp_dateUntil - $timestamp_dateFrom)/86400;
            
            return ($days_dif >= $min);
        }

        /**
         * date B is at least x days after date A (both empty allowed)
         * 
         */
		static function UntilIsMinDaysAfterFromOptional ($data, $args) {
            
            $field_dateFrom = $args["dateFrom"];
            
            // A) dateFrom is defined as a date (YYYY-MM-DD)
            if (preg_match('/[\d]{4}+[\-]+[\d]{2}+[\-]+[\d]{2}/',$field_dateFrom)) {
                $dateFrom = $args["dateFrom"];  
            }
            // B) dateFrom is defined as the name of another inputfield
            else {
                $dateFrom = $data[$field_dateFrom];  
            }
            
            $dateUntil = $data[$args["dateUntil"]];
            $min = (int)$args["min"];
            
            if (($dateFrom=="") AND ($dateUntil=="")) return true;
            if (($dateFrom=="") OR ($dateUntil=="")) return false;
            
            $timestamp_dateFrom  = strtotime($dateFrom);
            $timestamp_dateUntil = strtotime($dateUntil);
            $days_dif = ($timestamp_dateUntil - $timestamp_dateFrom)/86400;
            
            return ($days_dif >= $min);
        }
          
}