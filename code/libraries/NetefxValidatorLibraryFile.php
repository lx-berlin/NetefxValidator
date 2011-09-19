<?php

/**
* LibraryFunctions for Files for NetefxValidator
* 
* @version 0.7 (19.09.2011)
* @package NetefxValidator
*/

class NetefxValidatorLibraryFile {


// ************************ Often used functions for validation **********************
		
  
         /** check for allowed mime types for an upload (if exists)
         * 
         * @example  $rule_file_1  = new NetefxValidatorRuleFUNCTION("File", "wrong file type", 'error', 
         *   														array('NetefxValidatorLibraryFile', 'check_mime_types', array('field'  => 'File', 'allowedMimeTypes' => array("application/pdf","application/msword","x-unknown/x-unknown"))));
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
         * @example   $rule_image_1 = new NetefxValidatorRuleFUNCTION("Image", "you need to upload a photo", 'error',
         * 															  array('NetefxValidatorLibraryFile','check_file_exists', array('field'  => 'Image')));
         */
        static function check_file_exists ($data, $args) {
            $field = $args["field"];
            $arr_file = $data[$field];            
        	return ($arr_file["error"]==0);	          
        }
        
        /** check if file has correct size (0 for unlimited values) 
         * 
         * @example   $rule_file_2  = new NetefxValidatorRuleFUNCTION("File", "Maximal file size is 10000 bytes", 'error'
         * 															  array('NetefxValidatorLibraryFile', 'check_file_size', array('field'  => 'File', 'minSize' =>  0, 'maxSize' => 10000)));
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
         * @example   $rule_image_3  = new NetefxValidatorRuleFUNCTION("Image",  "image width at least 10, image height at most 500", 'error', 
         * 																array('NetefxValidatorLibraryFile', 'check_image_size', 
         * 																	array('field'  => 'Image', 'minWidth' => 10, 'maxWidth' => 0, 'minHeight' => 0, 'maxHeight' => 500)));
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