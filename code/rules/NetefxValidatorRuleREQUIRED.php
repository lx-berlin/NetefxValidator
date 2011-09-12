<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleREQUIRED extends NetefxValidatorRule {
		/**
		 * Check if the given field is filled out
         * @example $rule = new NetefxValidatorRuleREQUIRED("FirstName", "FirstName is required", null);
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error') {	
			if ($errorMsg === null) $errorMsg = sprintf(
				_t('NetefxValidatorRuleREQUIRED.ErrorMessage', '%s is required'),
				$field
			);
			parent::__construct($field, $errorMsg, $errorMsgType, array());
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
            return (trim($data[$this->field]) != '');
        }
}