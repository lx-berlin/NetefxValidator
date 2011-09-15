<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleGREATER extends NetefxValidatorRule {
		/**
		 * check if field value is numeric and greater than the given value or expression
         * @example $rule = new NetefxValidatorRuleGREATER('FieldName', 'Insert a number greater than 10', null, 10); 
         * @example $rule = new NetefxValidatorRuleGREATER('FieldName', 'Insert a number greater than 10 and use "," as decimal seperator', null, array(10, ','));
         * @example $rule = new NetefxValidatorRuleGREATER('C', 'C > must be greater than "2 * FieldA + FieldB"', null, '2*@A~+@B~'); 
         * @todo the first couple of lines are the same for all greater and smaller validations, they should be moved to a seperated function
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param int|string|array $args number, expression or array (if array, the 2nd value in the array can set what character is used as decimal point)
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			if ($errorMsg === null) $errorMsg = sprintf(
				_t('NetefxValidatorRuleGREATER.ErrorMessage', '%s needs to be greater than %s'),
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
			return ($data[$this->field] > $this->evaluate($data,$this->args[0]));
		}
}