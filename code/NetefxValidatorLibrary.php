<?php

/**
* LibraryFunctions for NetefxValidator
* 
* @version 0.45 (28.07.2011)
* @package NetefxValidator
* 
* new in 0.45: checkboxes_no_overlapping
* new in 0.44: min_number_many_DOM_checked, max_number_many_DOM_checked
*/

class NetefxValidatorLibrary {


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
        
        
        
        // *** at least x checkboxes of a CheckboxField must be checked ***
        static function min_number_checkboxes_checked ($data, $args) {
            
        	
        	
        	$field = $args["field"];
            $min   = (int)$args["min"];
            
            if (($data[$field]=="") AND ($min>0)) return false;
            
            $items = explode(",",$data[$field]);
            $checked = 0;
            foreach ($items AS $key => $value) {
                $checked++;
            }
            return ($checked >= $min);
        }
      
        // *** maximal x checkboxes of a CheckboxField can be checked ***
        static function max_number_checkboxes_checked ($data, $args) {
            $field = $args["field"];
            $max   = (int)$args["max"];
            
            if ($data[$field]=="") return true;
            
            $items = explode(",",$data[$field]);
            $checked = 0;
            foreach ($items AS $key => $value) {
                $checked++;
            }
            return ($checked <= $max);             
        }
        
        // *** at least x checkboxes of a (Has|Many)ManyDataObjectManager must be checked ***
        static function min_number_many_DOM_checked ($data, $args) {
                  	
        	$field = $args["field"];
            $min   = (int)$args["min"];
            
            //if ($field=="TargetCountries") debug::show($data[$field]);
            
            if (($data[$field]=="") AND ($min>0)) return false;
            
            $checked = 0;
            foreach ($data[$field] AS $key => $value) {
                $checked++;
            }
            return ($checked >= $min+1);
        }
        
        // *** maximal x checkboxes of a (Has|Many)ManyDataObjectManager can be checked ***
        static function max_number_many_DOM_checked ($data, $args) {
                  	
        	$field = $args["field"];
            $max   = (int)$args["max"];
            
            //if ($field=="TargetCountries") debug::show($data[$field]);
            
            if ($data[$field]=="") return true;
            
            $checked = 0;
            foreach ($data[$field] AS $key => $value) {
                $checked++;
            }
            return ($checked <= $max+1);
        }
      
                
        /** there is no overlapping of the checked items of two checkboxes 
        * 
        * @example  $rule_excludedPersons_notInvited = new NetefxValidatorRule ("excludedPersons", NV_FUNCTION, array('NetefxValidatorLibrary',
                                                                                                               'checkboxes_no_overlapping',
                                                                                                               array('field' => 'excludedPersons',
                                                                                                                     'otherField' => 'invitedPersons')),  "you cannot invite a person and exclude her as well");   
        */
        static function checkboxes_no_overlapping ($data, $args) {
        	
        	$field1 = $args["field"];
        	$field2 = $args["otherField"];
         	
        	if (($data[$field1]=="") OR ($data[$field2]=="")) {      		
        		return true;
        	}
        	
            $items1 = explode(",",$data[$field1]);
            
            $items2 = explode(",",$data[$field2]);
            
            foreach ($items2 AS $key => $value) {
                if (in_array ($value, $items1)) {
                	return false;
                } 
            }
        	
        	return true;
        }
        
        
         /** check for allowed mime types for an upload (if exists)
         * 
         * @example  $rule_datei_1  = new NetefxValidatorRule("Datei",  "FUNCTION",    array('NetefxValidatorLibrary', 
                                                                                                   'check_mime_types', 
                                                                                                   array('field'  => 'Datei', 
                                                                                                         'allowedMimeTypes' => array("application/pdf","application/msword","x-unknown/x-unknown"))), "falscher Typ der Datei");
         */
        static function check_mime_types ($data, $args) {
            $field = $args["field"];
            $arr_file = $data[$field];
            $allowedMimeTypes = $args["allowedMimeTypes"];
            
            
        	if ($allowedMimeTypes AND $arr_file["type"]) {        		
            	if (!in_array($arr_file["type"],$allowedMimeTypes)) {
            		return false;
            	}          
            }
            return true;	          
        }
        
        /** check if file for an upload exists (for required uploads)
         * 
         * @example   $rule_bild_1 = new NetefxValidatorRule("Image",   "FUNCTION",    array('NetefxValidatorLibrary', 
                                                                                                   'check_file_exists', 
                                                                                                   array('field'  => 'Image')), "Foto ist Pflicht");
         */
        static function check_file_exists ($data, $args) {
            $field = $args["field"];
            $arr_file = $data[$field];            
        	return ($arr_file["error"]==0);	          
        }
        
        /** check if file has correct size (0 for unlimited values) 
         * 
         * @example   $rule_datei_2  = new NetefxValidatorRule("Datei",  "FUNCTION",    array('NetefxValidatorLibrary', 
                                                                                                   'check_file_size', 
                                                                                                   array('field'  => 'Datei', 
                                                                                                         'minSize' =>  0,
                                                                                                         'maxSize' => 10000)), "Datei darf maximal 10000 Bytes haben");
        */           
        
        static function check_file_size ($data, $args) {
            $field = $args["field"];
            $arr_file = $data[$field];
			$file_size = $arr_file["size"];

			$minSize   = $args["minSize"];
			$maxSize   = $args["maxSize"];
			
            if ($minSize AND $file_size < $minSize) {     		
            	return false;
            }
            if ($maxSize AND $file_size > $maxSize) {     		
            	return false;
            }       
            
        	return true;	          
        }
        
        /** check if image has correct width and height (0 for unlimited values) 
         * 
         * @example   $rule_bild_3  = new NetefxValidatorRule("Image",  "FUNCTION",    array('NetefxValidatorLibrary', 
                                                                                                   'check_image_size', 
                                                                                                   array('field'  => 'Image', 
                                                                                                         'minWidth' => 10,
                                                                                                         'maxWidth' => 0,
                                                                                                         'minHeight' => 0,
                                                                                                         'maxHeight' => 500)), "Breite des Bildes muss mindestens 10 sein, Höhe maximal 500");
        */    

        static function check_image_size ($data, $args) {
            $field = $args["field"];
            $arr_file = $data[$field];
            if ($arr_file["tmp_name"]) {
				$image_size = getImageSize($arr_file["tmp_name"]);
	            
	            if ($image_size) {
					$width= $image_size[0];
					$height= $image_size[1];
	
					$minWidth  = $args["minWidth"];
					$minHeight = $args["minHeight"];
					$maxWidth  = $args["maxWidth"];
					$maxHeight = $args["maxHeight"];
					
		            if ($minWidth AND $width < $minWidth) {     		
		            	return false;
		            }
		            if ($minHeight AND $height < $minHeight) {     		
		            	return false;
		            }   
		            if ($maxWidth AND $width > $maxWidth) {     		
		            	return false;
		            }   
		            if ($maxHeight AND $height > $maxHeight) {     		
		            	return false;
		            }  
	            }    
            }       
            
        	return true;	          
        }
        
        
        
        
}

?>