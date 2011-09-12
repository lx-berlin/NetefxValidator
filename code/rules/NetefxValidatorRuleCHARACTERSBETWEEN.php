<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleCHARACTERSBETWEEN extends NetefxValidatorRule {
		/**
		 * Check if field length is between 2 values
		 * @example $rule = new NetefxValidatorRuleCHARACTERSBETWEEN("FirstName", 'Please enter between 2 and 20 characters', null, array(2, 20));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param array $args array(min, max)
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $args);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			if (!$this->checkNumeric($data,$this->args[1]))
				return false;
			return ((strlen(trim($data[$this->field])) >= $this->evaluate($data,$this->args[0])) &&
			(strlen(trim($data[$this->field])) <= $this->evaluate($data,$this->args[1])));
		}
}