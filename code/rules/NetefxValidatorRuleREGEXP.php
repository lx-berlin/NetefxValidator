<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleREGEXP extends NetefxValidatorRule {
		/**
		 * Check if the given expression matches
         * @example $rule = new NetefxValidatorRuleREGEXP('Name', 'This field is required', null, '/^.{2,}$/');
         * @uses preg_match()
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param string $expression
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $expression = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $expression);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			return preg_match($this->args[0], $data[$this->field]) > 0;
		}
}