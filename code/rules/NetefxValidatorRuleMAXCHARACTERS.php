<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleMAXCHARACTERS extends NetefxValidatorRule {
		/**
		 * Check length of the field
         * @example $rule = new NetefxValidatorRuleMAXCHARACTERS('FirstName', 'Please enter no more than two characters', null, 2);
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param int $max
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $max = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $max);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return (strlen(trim($data[$this->field])) <= $this->evaluate($data,$this->args[0]));
		}
}