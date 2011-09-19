<?php

/**
* LibraryFunctions for Dates for NetefxValidator
* 
* @version 0.5 (15.09.2011)
* @package NetefxValidator
* @todo translate comments
* @todo fix examples
*/

class NetefxValidatorLibraryDate {


// ************************ Often used functions for validation **********************
		
     /** Date is at least x days in past
       *        
       * @example $rule_vergangenheit_1  = new NetefxValidatorRule("Vergangenheit",  "FUNCTION",   array('NetefxValidatorLibrary', 
                                                                                                   'DateIsMinDaysBeforeToday', 
                                                                                                   array('date'  => 'Vergangenheit', 
                                                                                                         'min'       => 1)),  "Das Feld Vergangenheit muss ein Datum in der Vergangenheit enthalten");               
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
       * @example$rule_zukunft_1  = new NetefxValidatorRule("Zukunft",  "FUNCTION",     array('NetefxValidatorLibrary', 
                                                                                                   'DateIsMinDaysAfterToday', 
                                                                                                   array('date'  => 'Zukunft', 
                                                                                                         'min'       => 1)),  "Das Feld Zukunft muss ein Datum in der Zukunft enthalten");               
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
         * @example $rule_bis  = new NetefxValidatorRule("bis",  "FUNCTION",  array('NetefxValidatorLibrary', 
                                                                              'UntilIsMinDaysAfterFromOptional',
                                                                              array('dateFrom'  => 'von',
                                                                              		'dateUntil' => 'bis',
                                                                              		'min'       => 0)),  "bis darf nicht vor vor sein");               
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
         * @example     	$rule_bis  = new NetefxValidatorRule("bis",  "FUNCTION",     array('NetefxValidatorLibrary', 
                                                                                               'UntilIsMinDaysAfterFromOptional', 
                                                                                               array('dateFrom'  => 'von', 
                                                                                                     'dateUntil' => 'bis',
                                                                                                     'min'       => 0)),  "bis darf nicht vor vor sein");  
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