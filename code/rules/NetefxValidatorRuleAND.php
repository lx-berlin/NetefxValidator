<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleAND extends NetefxValidatorRule {
		/**
		 * Check if all of the subrules are valid
         * @example $rule = new NetefxValidatorRuleAND('FieldName', 'This field must contain at least 3 characters and contain the word "Netefx"', null, array($rule1, $rule2));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param NetefxValidatorRule|array $rules single rule or array of rules
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $rules = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $rules);
			if ($this->getErrorMessage() === null) {
				$messages = array();
				foreach ($this->getArgs() as $rule)
					$messages[] = $rule->getErrorMessage();
				if (count($messages))
					$this->setErrorMessage(implode(_t('NetefxValidatorRuleAND.and', ' and '), $messages)); 
			}
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			foreach ($this->args as $rule)
				if (!($rule->validate($data)))
					return false;
			return true;
		}
}