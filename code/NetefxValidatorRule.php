<?php

// basic rules
define("NV_REQUIRED", "REQUIRED");
define("NV_EMPTY", "EMPTY");
define("NV_EXISTS", "EXISTS");

// logical rules
define("NV_OR", "OR");
define("NV_AND", "AND");
define("NV_NOT", "NOT");
define("NV_IMPLIES", "IMPLIES");
define("NV_XOR", "XOR");

// numeric rules
define("NV_GREATER","GREATER");
define("NV_GREATEREQUAL","GREATEREQUAL");
define("NV_SMALLER","SMALLER");
define("NV_SMALLEREQUAL","SMALLEREQUAL");
define("NV_EQUALS","EQUALS");
define("NV_BETWEEN","BETWEEN");

// regular expression rule
define("NV_REGEXP","REGEXP");

// textual rules
define("NV_TEXTEQUALS","TEXTEQUALS");
define("NV_TEXTIS","TEXTIS");
define("NV_TEXTCONTAINS","TEXTCONTAINS");
define("NV_ISONEFROM","ISONEFROM");
define("NV_ISNOTONEFROM","ISNOTONEFROM");
define("NV_MINCHARACTERS","MINCHARACTERS");
define("NV_MAXCHARACTERS","MAXCHARACTERS");
define("NV_CHARACTERSBETWEEN","CHARACTERSBETWEEN");

// unique rule
define("NV_UNIQUE","UNIQUE");

// function rule
define("NV_FUNCTION","FUNCTION");


/**
* RuleClass for NetefxValidator
* 
* @todo unit tests
* @todo test examples
* @todo test validateFUNCTION
* 
* @version 0.46
* @package NetefxValidator
*/
class NetefxValidatorRule extends Object {
		protected $field;
		protected $type;
		protected $args;		
		protected $errorMsg;
		protected $errorMsgType;
		
		/**
		 * @return string the field which this rule belongs to
		 */		
		public function field(){
			return $this->field;
		}
		
		/**
		 * @return string the error message of this rule
		 */	
		public function errorMsg(){
			return $this->errorMsg;
		}
		
		/**
		 * @return string the error message type of this rule
		 */	
		public function errorMsgType(){
			return $this->errorMsgType;
		}
		
		/**
		 * @param string $field name of the field
		 * @param string $type (use NV_* constants defined in NetefxValidatorRule.php)
		 * @param string|array $args additional arguments needed in speical rules like FUNCTION 
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 */
		function __construct($field, $type = NV_REQUIRED, $args = '', $errorMsg = '', $errorMsgType = 'error') {	
			parent::__construct();
			$this->field = $field;
			$this->type = $type;
			$this->args = (is_array($args))?$args:array($args);
			$this->errorMsg = $errorMsg;
			$this->errorMsgType = $errorMsgType;
		}
		
		/**
		 * Wertet den übergebenen Ausdruck aus, wobei Namen von anderen Feldern in die Zeichen @ und ~ eingeschlossen sind.
		 * Aus Sicherheitsgründen und da nur mit Zahlen gerechnet werden kann, muss unbedingt vorher checkNumeric() aufgerufen werden.
		 * @todo translate comment
		 * @param array $data
		 * @param string $expr
		 * @return string
		 */
		protected function evaluate($data,$expr) {
            $expr = str_replace ('@','$data["',$expr);
			$expr = str_replace ('~','"]',$expr);
			$expr = "return ".$expr.";";
			return eval($expr);
		}
		
		/**
		 * @param array $data
		 * @param string $expr
		 * @return boolean
		 */
		protected function checkNumeric($data,$expr) {			
			$pos = -1;
			$pos2 = -1;
			do {
				$pos = strpos ($expr,'@', $pos+1);
				$pos2 = strpos ($expr,'~', $pos2+1);
				if ($pos !== false) {
					$fieldname = substr ($expr,$pos+1,$pos2-$pos-1);
					if (!is_numeric($data[$fieldname]))		
						return false;
				} else {
					return true;
				}
			}
			while (true);
		}
		
        /**
        * converts custom number format to english number format (eg: german uses , instead of . for the decimal seperator)
        * 
        * @param string $number
        * @param string $separator (eg: "." or ",")
        */
		protected function numberFormatConversion($number, $separator) {
			if (preg_match("/^[0-9".$separator."]{1,}$/", $number)>0) {
				$number = str_replace($separator,".",$number);           
	            return $number;
			} 
			return false;			
        }
        
		/**
		 * Calls the particular validation function for the given type
		 * @throws user_error if validation rule type does not exists
		 * @param array $data
		 * @return boolean
		 */
		public function validate($data){
			$method_name = "validate{$this->type}";
			if ($this->hasMethod($method_name))
				return $this->$method_name($data);
			user_error("The validation rule type '{$this->type}' is not yet supported.");
		}
		
        /**
         * Check if the given field is filled out
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule("FirstName", NV_REQUIRED, null, "FirstName is required");
         * @param array $data
         * @return boolean
         */
        protected function validateREQUIRED($data) {
            return (trim($data[$this->field]) != '');
        }
        
        /**
         * Empty Check on given field, returned true if the field is empty
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule("SomeField", NV_EMPTY, null, "this field should be empty");
         * @param array $data
         * @return boolean
         */
        protected function validateEMPTY($data) {
            return ($data[$this->field] == '');
        }
        
        /**
         * Check if given field exists on the form
         * Called by $this->validate()
         * @example $ruleEXISTS = new NetefxValidatorRule('email',  NV_EXISTS); <br> $ruleREQUIRED = new NetefxValidatorRule ('email',  NV_REQUIRED); <br> $rule = new NetefxValidatorRule ("email",  "IMPLIES", array($ruleEXISTS, $ruleREQUIRED), "This field is required"); 
         * @param array $data
         * @return boolean
         */
        protected function validateEXISTS($data) {
	        return (isset($data[$this->field]));
        }
        
        /**
         * Check if at least 1 of the subrules is valid (subrules are passed as a array to the args param)
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule ('FieldName',  NV_OR, array($rule1, $rule2), 'This field must contain at least 3 characters or be empty'); 
         * @param array $data
         * @return boolean
         */
		protected function validateOR($data) {
			foreach ($this->args as $rule)
				if ($rule->validate($data))
					return true;
			return false;
		}
		
        /**
         * Check if all of the subrules are valid (subrules are passed as a array to the args param)
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule ('FieldName',  NV_AND, array($rule1, $rule2), 'This field must contain at least 3 characters and contain the word "Netefx"'); 
         * @param array $data
         * @return boolean
         */
		protected function validateAND($data) {
			foreach ($this->args as $rule)
				if (!($rule->validate($data)))
					return false;
			return true;
		}
		
        /**
         * returns true if the subrule is not valid
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule ('Username',  NV_NOT, $subRule, 'Your Username can not contain "Testuser"'); 
         * @param array $data
         * @return boolean
         */
		protected function validateNOT($data) {
			return (!($this->args[0]->validate($data)));
		}
		
		/**
         * Check if first rule is valid the second needs to be valid as well. If first rule is not valid true will be returned.
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule ('Username',  NV_IMPLIES, array($subRule1, $subRule2), 'If you choose credit card as payment method, it is required to enter your credit card number'); 
         * @param array $data
         * @return boolean
         */		
		protected function validateIMPLIES($data) {
			if ($this->args[0]->validate($data))
				return ($this->args[1]->validate($data));
			return true;
		}
		
		/**
         * returns true if exactly 1 of the subrules is valid, more or less will return false 
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule ('FieldName',  NV_XOR, array($subRule1, $subRule2, $subRule3, ...), 'Please choose exactly 1 option.'); 
         * @param array $data
         * @return boolean
         */		
		protected function validateXOR($data) {
			$valid = false;
			foreach ($this->args as $rule) {
				if ($rule->validate($data)) {
					if ($valid) return false;
					$valid = true;
				}
			}
			return $valid;
		}
		
		/**
         * check if field value is numeric and greater than the given value or expression (if args is passed as array, the 2nd value in the array can set what character is used as decimal point)
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule ('FieldName',  NV_GREATER, 10, 'Insert a number greater than 10'); 
         * @example $rule = new NetefxValidatorRule ('FieldName',  NV_GREATER, array(10, ','), 'Insert a number greater than 10 and use "," as decimal seperator');
         * @example $rule = new NetefxValidatorRule ('C',  NV_GREATER, '2*@A~+@B~', 'C > must be greater than "2 * FieldA + FieldB"'); 
         * @todo the first couple of lines are the same for all greater and smaller validations, they should be moved to a seperated function
         * @param array $data
         * @return boolean
         */	
		protected function validateGREATER($data) {  		
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;    
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return ($data[$this->field] > $this->evaluate($data,$this->args[0]));
		}
        
        
        /**
         * Check if field value is numeric and greater or equal to the given value or expression
         * @example see NetefxValidator::validateGREATER() for examples
         */
        protected function validateGREATEREQUAL($data) {
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;    
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
            return ($data[$this->field] >= $this->evaluate($data,$this->args[0]));
        }
        
        /**
         * Check if field value is numeric and smaller than the given value or expression
         * @example see NetefxValidator::validateGREATER() for examples
         */
		protected function validateSMALLER($data) {
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;    
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return ($data[$this->field] < $this->evaluate($data,$this->args[0]));
		}
		
        /**
         * Check if field value is numeric and smaller or equal to the given value or expression
         * @example see NetefxValidator::validateGREATER() for examples
         */
		protected function validateSMALLEREQUAL($data) {
			$data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;    
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return ($data[$this->field] <= $this->evaluate($data,$this->args[0]));
		}
		
        /**
         * Check if field value is numeric and equal to the given value or expression
         * @example see NetefxValidator::validateGREATER() for examples
         * @see NetefxValidatorRule::validateTEXTIS() for text equals validation
         */
		protected function validateEQUALS($data) {
			$data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;    
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return ($data[$this->field] == $this->evaluate($data,$this->args[0]));
		}
		
		/**
         * check if field value is numeric and between the 2 given values or expressions (the 3rd value in the args array can set what character is used as decimal point)
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule ('Number',  NV_BETWEEN, array('10','20'), 'Insert a number btween 10 and 20'); 
         * @example $rule = new NetefxValidatorRule ('Number',  NV_BETWEEN, array(10,'20' ','), 'Insert a number btween 10 and 20, use "," as decimal seperator');
         * @param array $data
         * @return boolean
         */	
		protected function validateBETWEEN($data) { 
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[2]) ? $this->args[2] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			if (!$this->checkNumeric($data,$this->args[1]))
				return false;
			return (($data[$this->field] <= $this->evaluate($data,$this->args[1])) &&
			        ($data[$this->field] >= $this->evaluate($data,$this->args[0])));
		}
		
		/**
		 * Check if the given expression matches
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('Name',  NV_REGEXP, '/^.{2,}$/', 'This field is required'); 
         * @param array $data
         * @return boolean
         */	
		protected function validateREGEXP($data) {
			return preg_match($this->args[0], $data[$this->field]) > 0;
		}
		
		/**
		 * Check if 2 fields are equal
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('Password2', NV_TEXTEQUALS, 'Password', 'Password und Password2 need to match');
         * @param array $data
         * @return boolean
         */	
		protected function validateTEXTEQUALS($data) {
			return (strcmp($data[$this->field],$data[$this->args[0]])==0);
		}

		/**
		 * Check if the field is equal to a string
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('Company', NV_TEXTIS, 'Netefx', 'Company must be "Netefx"');
         * @param array $data
         * @return boolean
         */	
		protected function validateTEXTIS($data) {
			return (strcmp($data[$this->field],$this->args[0])==0);
		}
		
		/**
		 * Check if the field contains a string
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('Company', NV_TEXTCONTAINS, 'Netefx', 'Company must contain "Netefx"');
         * @param array $data
         * @return boolean
         */	
		protected function validateTEXTCONTAINS($data) {
			$pos = strpos ($data[$this->field],$this->args[0]);
			return ($pos !== false);
		}

		/**
		 * Check if the field is equal to one of the given strings
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('Flat', NV_ISONEFROM, array('Paris', 'Berlin'), 'Only Paris and Berlin are available at the moment.');
         * @todo wouldn't be IsOneOf the correct name?
         * @param array $data
         * @return boolean
         */	
		protected function validateISONEFROM($data) {
			foreach ($this->args as $text)
				if (strcmp($data[$this->field],$text)==0)
					return true;	
			return false;
		}

		/**
		 * Check if the field is not equal to one of the given strings
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('Flat', NV_ISNOTONEFROM, array('Paris', 'Berlin'), 'Paris and Berlin are not available at the moment.');
         * @todo wouldn't be IsNotOneOf the correct name?
         * @param array $data
         * @return boolean
         */	
		protected function validateISNOTONEFROM($data) {
			foreach ($this->args as $text)
				if (strcmp($data[$this->field],$text)==0)
					return false;
			return true;
		}
		
		/**
		 * Check length of the field
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('FirstName', NV_MINCHARACTERS, 2, 'Please enter at least two characters');
         * @param array $data
         * @return boolean
         */	
		protected function validateMINCHARACTERS($data) {
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return (strlen(trim($data[$this->field])) >= $this->evaluate($data,$this->args[0]));
		}

		/**
		 * Check length of the field
         * Called by $this->validate()
         * @example $rule = new NetefxValidatorRule('FirstName', NV_MAXCHARACTERS, 2, 'Please enter no more than two characters');
         * @param array $data
         * @return boolean
         */	
		protected function validateMAXCHARACTERS($data) {
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return (strlen(trim($data[$this->field])) <= $this->evaluate($data,$this->args[0]));
		}
		
		/**
		 * Check if field length is between 2 values
		 * Called by $this->validate()
		 * @example $rule = new NetefxValidatorRule("FirstName", NV_CHARACTERSBETWEEN, array(2, 20), 'Please enter between 2 and 20 characters');
		 * @param array $data
         * @return boolean
		 */
		protected function validateCHARACTERSBETWEEN($data) {
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			if (!$this->checkNumeric($data,$this->args[1]))
				return false;
			return ((strlen(trim($data[$this->field])) >= $this->evaluate($data,$this->args[0])) &&
			(strlen(trim($data[$this->field])) <= $this->evaluate($data,$this->args[1])));
		}
		
		
		/**
         * Validierung einer UNIQUE-Regel
         * Überprüft, ob es noch keinen ANDEREN Eintrag gibt, bei dem das übergebene Feld der übergebenen Klasse diesen Wert hat
         * 
         * Beispiel:  
         * $rule_email_1 = new NetefxValidatorRule("EMail", "UNIQUE",  array('EMail','Member'), "E-Mail schon vergeben");
         * 
         */
		
		
		/**
		 * Check if any other DataObject has the same value already
		 * first argument is the DB field name, the second the classname and the third is optional the ID to excluse from the uniqe rule or the name of another field in the form (default is field ID in the form) 
		 * Called by $this->validate()
		 * @example $rule = new NetefxValidatorRule('Nickname', NV_UNIQUE, array('Nickname', 'Member', 'MemberID'), 'This Nickname is already in use.');
		 * @param array $data
         * @return boolean
		 */
        protected function validateUNIQUE($data) {
        	$new_value = Convert::raw2sql($data[$this->field]);
            $field_name = $this->args[0];
			$class_name = $this->args[1];
			if (isset($this->args[2])) {
				if (is_numeric($this->args[2]))
					$id = $this->args[2];
				elseif (isset($data[$this->args[2]]))
					$id = (int)$data[$this->args[2]];
			} else {
				$id = (int)$data['ID'];
			}
        	if ($id > 0)
        		$other_entry = DataObject::get_one($class_name,"$field_name = '$new_value' AND $class_name.ID<>".$id); 
            else
            	$other_entry = DataObject::get_one($class_name,"$field_name = '$new_value'");     	
            return ($other_entry && $other_entry->exists()) ? false : true; 
        }
		
		/**
		 * Use a custom function to validate this field (the validator will pass 2 params to the custom function, first $data and as second the params you gave to the rule)
		 * Called by $this->validate()
		 * @todo test it, also test the examples
		 * @example // this will call NetefxValidatorLibrary::min_number_checkboxes_checked($data, $args);<br>$class = 'NetefxValidatorLibrary'<br>$function = 'min_number_checkboxes_checked';<br>$args = array('field' => 'myField', 'min' => 5);<br>$rule = new NetefxValidatorRule("myField", NV_FUNCTION, array($class, $method, $args), 'this field is required');
		 * @example // this will call $object->myValidationMethod($data, $args);<br> $object = new MyObject();<br>$function = 'myValidationMethod';<br>$args = array('fieldName' => 'myField', 'someOtherThing' => 'yay');<br>$rule = new NetefxValidatorRule("myField", NV_FUNCTION, array($object, $function, $args), 'this field is required');
		 * @example $function = create_function('$data,$args', '$fieldName = $args["fieldName"]; if ($data[$fieldName] == "test") return true; else return false;');<br>$args = array('fieldName' => 'myField', 'someOtherThing' => 'yay');<br>$rule = new NetefxValidatorRule("myField", NV_FUNCTION, array($function, $args), 'this field is required');
		 * @example // since PHP 5.3.0<br>$function = function ($data, $args) {<br>$fieldName = $args['fieldName'];<br>if ($data[$fieldName] == 'test')<br>return true;<br>else<br>return false;<br>}<br>$args = array('fieldName' => 'myField', 'someOtherThing' => 'yay');<br>$rule = new NetefxValidatorRule("myField", NV_FUNCTION, array($function, $args), 'this field is required');
		 * @param array $data
         * @return boolean
		 */
		protected function validateFUNCTION($data) {
			if (is_callable($this->args[0])) {
				$function = $this->args[0];
				$params = (isset($this->args[1]))?array($data, $this->args[1]):array($data);
			} elseif ((is_string($this->args[0]) || is_object($this->args[0])) && is_string($this->args[1])) {
				$function = array($this->args[0], $this->args[1]);
				$params = (isset($this->args[2]))?array($data, $this->args[2]):array($data);
			}
			return call_user_func_array($function, $params);
		}		
}