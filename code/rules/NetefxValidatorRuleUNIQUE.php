<?php 

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author zauberfisch
 */
class NetefxValidatorRuleUNIQUE extends NetefxValidatorRule {
		/**
		 * Check if any other DataObject has the same value already
		 * @example $rule = new NetefxValidatorRuleUNIQUE('Nickname', 'This Nickname is already in use.', null, array('Nickname', 'Member', 'MemberID', 'MemberID'));
		 * @param string $field name of the field
		 * @param string $errorMsg the message to be displayed
		 * @param string $errorMsgType the css class added to the field on validation error
		 * @param array $args first argument is the classname, the second the DB field name on the class, the third is optional the ID to excluse from the uniqe rule or the name of another field in the form (default is field ID in the form), the 4th is optional the DB field name to check for the exclude id
		 */
		public function __construct($field, $errorMsg = null, $errorMsgType = 'error', $args = null) {	
			parent::__construct($field, $errorMsg, $errorMsgType, $args);
		}
		
		/**
         * @param array $data
         * @return boolean
		 */
		public function validate($data) {
        	$newValue = Convert::raw2sql($data[$this->field]);
            $classCame = $this->args[0];
        	$fieldName = $this->args[1];
			if (isset($this->args[2]) && is_numeric($this->args[2])) {
				$id = $this->args[2];
			} else {
				$idFieldInForm = (isset($this->args[2]))?$this->args[2]:'ID';
				$id = (isset($data[$idFieldInForm]))?Convert::raw2sql($data[$idFieldInForm]):false;
			}
			$idFieldInDB = (isset($this->args[3]))?$this->args[3]:'ID';
			if (is_string($id)) $id = "'$id'";
        	if ((is_numeric($id) && $id > 0) || is_string($id))
        		$filter = "\"$fieldName\" = '$newValue' AND \"$idFieldInDB\" <> $id"; 
            else
            	$filter = "\"$fieldName\" = '$newValue'";
            $otherEntry = DataObject::get_one($classCame, $filter);     	
            return ($otherEntry && $otherEntry->exists()) ? false : true; 
        }
}