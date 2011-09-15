<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleTEXTEQUALS extends NetefxValidatorRule {
		/**
		 * Check if 2 fields are equal
         * @example $rule = new NetefxValidatorRuleTEXTEQUALS('Password2', 'Password', 'Password und Password2 need to match');
         * @see NetefxValidatorRuleTEXTIS to check if the field is equal to a string
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param string $otherField
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $otherField = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $otherField);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			return (strcmp($data[$this->field],$data[$this->args[0]])==0);
		}
}