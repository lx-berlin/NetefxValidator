<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleXOR extends NetefxValidatorRule {
		/**
		 * returns true if exactly 1 of the subrules is valid, more or less will return false 
         * @example $rule = new NetefxValidatorRuleXOR ('FieldName', 'Please choose exactly 1 option.', null, array($subRule1, $subRule2, $subRule3, ...));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param NetefxValidatorRule|array $rules single rule or array of rules
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $rules = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $rules);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			$valid = false;
			foreach ($this->args as $rule) {
				if ($rule->validate($data)) {
					if ($valid) return false;
					$valid = true;
				}
			}
			return $valid;
		}
}