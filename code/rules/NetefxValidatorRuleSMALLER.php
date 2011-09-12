<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleSMALLER extends NetefxValidatorRule {
		/**
		 * Check if field value is numeric and smaller than the given value or expression
         * @example see NetefxValidator::validateGREATER() for examples
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param int|string|array $args number, expression or array (if array, the 2nd value in the array can set what character is used as decimal point)
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			if ($errorMsg === null) $errorMsg = sprintf(
				_t('NetefxValidatorRuleSMALLER.ErrorMessage', '%s needs to be smaller than %s'),
				$field,
				$this->args[0]
			);
			parent::__construct($field, $errorMsg, $errorMsgType, $args);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;    
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			return ($data[$this->field] < $this->evaluate($data,$this->args[0]));
		}
}