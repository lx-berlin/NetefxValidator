<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleINARRAY extends NetefxValidatorRule {
		/**
		 * Check if the field is equal to one of the given strings
         * @example $rule = new NetefxValidatorRuleINARRAY('Flat', 'Only Paris and Berlin are available at the moment.', null, array('Paris', 'Berlin'));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param array $args array of strings|ints to be checked
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $args);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			foreach ($this->args as $text)
				if (strcmp($data[$this->field],$text)==0)
					return true;	
			return false;
		}
}