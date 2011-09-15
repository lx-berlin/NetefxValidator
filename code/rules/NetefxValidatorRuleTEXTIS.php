<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleTEXTIS extends NetefxValidatorRule {
		/**
		 * Check if the field is equal to a string
         * @example $rule = new NetefxValidatorRuleTEXTIS('Company', 'Netefx', 'Company must be "Netefx"');
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param string $str
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $str = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $str);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			return (strcmp($data[$this->field],$this->args[0])==0);
		}
}