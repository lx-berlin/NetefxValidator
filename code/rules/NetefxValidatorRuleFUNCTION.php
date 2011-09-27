<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleFUNCTION extends NetefxValidatorRule {
		/**
		 * Use a custom function to validate this field (the validator will pass 2 params to the custom function, first $data and as second the params you gave to the rule)
		 * @example $code = 'if ($data["myField"] == "test") return true; else return false;'; $rule = new NetefxValidatorRuleFUNCTION("myField", 'the value must be test', null, $code);
		 * @example // this will call NetefxValidatorLibrary::min_number_checkboxes_checked($data, $args);<br>$class = 'NetefxValidatorLibrary'<br>$function = 'min_number_checkboxes_checked';<br>$args = array('field' => 'myField', 'min' => 5);<br>$rule = new NetefxValidatorRuleFUNCTION('myField', array($class, $method, $args), 'at least 5 checkboxes need to be checked');
		 * @example // this will call $object->myValidationMethod($data, $args);<br> $object = new MyObject();<br>$function = 'myValidationMethod';<br>$args = array('fieldName' => 'myField', 'someOtherThing' => 'yay');<br>$rule = new NetefxValidatorRuleFUNCTION('myField', 'this field is required', null, array($object, $function, $args));
		 * @example $function = create_function('$data,$args', 'if ($data["myField"] == "test" && $args["someOtherThing"]) return true; else return false;');<br>$args = array('someOtherThing' => true);<br>$rule = new NetefxValidatorRuleFUNCTION("myField", 'this field is required', null, array($function, $args));
		 * @example // since PHP 5.3.0<br>$function = function ($data, $args) {<br>$fieldName = $args['fieldName'];<br>if ($data[$fieldName] == 'test')<br>return true;<br>else<br>return false;<br>}<br>$args = array('fieldName' => 'myField', 'someOtherThing' => 'yay');<br>$rule = new NetefxValidatorRuleFUNCTION('myField', 'this field is required', null, array($function, $args));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param string|callback|array string code to eval (assing return value to $return), callback a callable function or array (see examples)
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $args);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
			$args = $this->getArgs();
			$params = array(
				'data' => $data,
				'args' => null
			);
			if (is_callable($args[0])) {
				$function = $args[0];
				if (count($args) > 1) $params['args'] = $args[1];
			} elseif ((is_string($args[0]) || is_object($args[0])) && isset($args[1]) && is_string($args[1])) {
				$function = array($args[0], $args[1]);
				if (count($args) > 2) $params['args'] = $args[2];
			} else {
				if (count($args) > 1) $params['args'] = $args[1];
				return eval($args[0]);
			}
			return call_user_func_array($function, $params);
		}
}