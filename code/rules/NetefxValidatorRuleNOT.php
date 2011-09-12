<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleNOT extends NetefxValidatorRule {
		/**
		 * returns true if the subrule is not valid
         * @example $rule = new NetefxValidatorRuleNOT ('Username', 'Your Username can not contain "Testuser"', null, $subRule);
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param NetefxValidatorRule $rule
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $rule = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $rule);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			return (!($this->args[0]->validate($data)));
		}
}