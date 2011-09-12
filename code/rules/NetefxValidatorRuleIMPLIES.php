<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleIMPLIES extends NetefxValidatorRule {
		/**
		 * Check if first rule is valid the second needs to be valid as well. If first rule is not valid true will be returned.
         * @example $rule = new NetefxValidatorRuleIMPLIES('Username', 'If you choose credit card as payment method, it is required to enter your credit card number', null, array($subRule1, $subRule2));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param array $args 2 sub rules
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $args);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			if ($this->args[0]->validate($data))
				return ($this->args[1]->validate($data));
			return true;
		}
}