<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleBETWEEN extends NetefxValidatorRule {
		/**
		 * check if field value is numeric and between the 2 given values or expressions 
         * @example $rule = new NetefxValidatorRuleBETWEEN('Number', 'Insert a number btween 10 and 20', null, array(10, 20)); 
         * @example $rule = new NetefxValidatorRuleBETWEEN('Number', 'Insert a number btween 10 and 20, use "," as decimal seperator', null, array(10, 20, ','));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param array $args (the 3rd value in the array can set what character is used as decimal point)
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $args);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) { 
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[2]) ? $this->args[2] : "."));
            if (!is_numeric($data[$this->field]))	
				return false;
			if (!$this->checkNumeric($data,$this->args[0]))
				return false;
			if (!$this->checkNumeric($data,$this->args[1]))
				return false;
			return (($data[$this->field] <= $this->evaluate($data,$this->args[1])) &&
			        ($data[$this->field] >= $this->evaluate($data,$this->args[0])));
		}
}