<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleTEXTCONTAINS extends NetefxValidatorRule {
		/**
		 * Check if the field contains a string
         * @example $rule = new NetefxValidatorRuleTEXTCONTAINS('Company', 'Company must contain "Netefx"', null, 'Netefx');
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param string $needle
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $needle = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $needle);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			return (strpos($data[$this->field],$this->args[0]) !== false);
		}
}