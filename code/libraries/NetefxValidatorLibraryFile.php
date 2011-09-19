<?php

/**
* LibraryFunctions for Files for NetefxValidator
* 
* @version 0.5 (15.09.2011)
* @package NetefxValidator
* @todo translate comments
* @todo fix examples
*/

class NetefxValidatorLibraryFile {


// ************************ Often used functions for validation **********************
		
  
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