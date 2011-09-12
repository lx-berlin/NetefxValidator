<?php

/**
* NetefxValidator
* 
* @version 0.45
* @package NetefxValidator 
* @author lx-berlin
* @author zauberfisch
*/

class NetefxValidator extends Validator {
	
	protected $rules;
	protected $javascriptValidationHandler = 'none';

	/**
	 * @return array
	 */
	public function getRules() {
		return $this->rules;
	}
	
	/**
	 * if the first parameter is not an array, or we have more than one parameter, collate all parameters to an array, otherwise use the passed array
	 * @param array|mixed $items
	 */
	public function setRules($items = null) {
		$this->rules = (!is_array($items) || count(func_get_args()) > 1) ? func_get_args() : $items;
	}
	
	/**
	 * if the first parameter is not an array, or we have more than one parameter, collate all parameters to an array, otherwise use the passed array
	 * @param array|mixed $items
	 */
	public function __construct() {
		$this->setRules(func_get_args());
		parent::__construct();
		
	}

	
	/**
	 * javascript not implemented yet
	 * @return string
	 */
	function javascript() {
		$js = "";
		return $js;
	}

	/**
	* calls validate() on all fields and rules
	* @return boolean
	*/
	function php($data) {
		$valid = true;

		$fields = $this->form->Fields();
		foreach($fields as $field) {
			$valid = ($field->validate($this) && $valid);
		}
		if($this->rules) {
			foreach($this->rules as $rule) { 
				
				if (!$rule->validate($data)) {
					$errorMessage = $rule->getErrorMessage();
					$errorMessageType = $rule->getErrorMessageType();
					$fieldName = $rule->getField();
	 
					$this->validationError(
							$fieldName,
							$errorMessage,
							$errorMessageType
					); 
					$valid = false;
				}
			}
		}
        
        if (!$valid) Session::set("NetefxValidatorError",true);
        
		return $valid;
	}
		
}
