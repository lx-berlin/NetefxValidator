<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleEXISTS extends NetefxValidatorRule {
		/**
		 * Check if given field exists on the form
         * @example $ruleEXISTS = new NetefxValidatorRuleEXISTS('email'); <br> $ruleREQUIRED = new NetefxValidatorRuleREQUIRED ('email'); <br> $rule = new NetefxValidatorRuleIMPLIES ("email", "This field is required", "error", array($ruleEXISTS, $ruleREQUIRED));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error') {	
			parent::__construct($field, $errorMsg, $errorMsgType, array());
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
	        return (isset($data[$this->field]));
        }
}