<?php

/**
* NetefxValidator
* @abstract special validationclass, that makes use of different validation rules
* 
* @version 0.45
* @package NetefxValidator 
*/

class NetefxValidator extends Validator {
	
	protected $rules;
	protected $useLabels = true;

	/**
	 * Der Konstruktor erwartet ein Array von NetefxValidatorRule-Objekten,
	 * die alle für die Validierung verwendeten Regeln enthält.
	 */
	function __construct() {
		$Rules = func_get_args();
		if( isset($Rules[0]) && is_array( $Rules[0] ) )
			$Rules = $Rules[0];
		$this->rules= $Rules;

		parent::__construct();
	}


	/**
	 * Javascript-Validierung ist nicht vorgesehen
	 */
	function javascript() {
		$js = "";
		return $js;
	}


	/**
	* Die eigentliche Validierung: ruft alle Regeln auf und zeigt ggf.
	* Validierungsfehler an
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
					$errorMessage = $rule->errorMsg();
					$errorMessageType = $rule->errorMsgType();
					$fieldName = $rule->field();
	 
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

?>