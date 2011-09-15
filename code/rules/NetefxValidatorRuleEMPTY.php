<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleEMPTY extends NetefxValidatorRule {
		/**
		 * Empty Check on given field, returned true if the field is empty
         * @example $rule = new NetefxValidatorRuleEMPTY("SomeField", "this field should be empty");
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error') {	
			if ($errorMsg === null) $errorMsg = sprintf(
				_t('NetefxValidatorRuleEMPTY.ErrorMessage', '%s needs to be empty'),
				$field
			);
			parent::__construct($field, $errorMsg, $errorMsgType, array());
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
            return ($data[$this->field] == '');
        }
}