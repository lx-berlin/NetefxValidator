<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
abstract class NetefxValidatorRule extends Object {
		protected $field;
		protected $args;		
		protected $errorMsg;
		protected $errorMsgType;
		
		/**
		 * @return string
		 */
		public function getField() {
			return $this->field;
		}
		/**
		 * @return array
		 */
		public function getArgs() {
			return $this->args;
		}
		/**
		 * @return string
		 */
		public function getErrorMessage() {
			return $this->errorMsg;
		}
		/**
		 * @return string
		 */
		public function getErrorMessageType() {
			return $this->errorMsgType;
		}
		/**
		 * Name of the Field that this rule is applied to
		 * @param string $fieldName
		 */
		public function setField($fieldName) {
			$this->field = $fieldName;
		}
		/**
		 * Arguments differ depending on the Rule
		 * @param mixed $args
		 */
		public function setArgs($args) {
			$this->args = (is_array($args))?$args:array($args);
		}
		/**
		 * @param string $message
		 */
		public function setErrorMessage($message) {
			$this->errorMsg = $message;
		}
		/**
		 * CSS class which will be added to the field if it is not valid (eg: 'validatonError')
		 * @param string $type
		 */
		public function setErrorMessageType($type) {
			$this->errorMsgType = $type;
		}
		
		/**
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param mixed $args additional arguments needed in speical rules like FUNCTION
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			parent::__construct();
			$this->setField($field);
			$this->setErrorMessage($errorMsg);
			$this->setArgs($args);
			$this->setErrorMessageType($errorMsgType);
		}
		
		/**
		 * evaluates the given expression, having names of other fields included in the characters @ and ~ resp. 
		 * Since you need numeric expressions to do arithmetic operations and for security reasons you have to call checkNumeric() before.
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
         * This function needs to be overwritten
         * @param array $data
         * @return boolean
         */
        abstract public function validate($data);
		
		public function jsValidation() {}
}