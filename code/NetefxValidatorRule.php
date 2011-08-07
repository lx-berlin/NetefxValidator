<?php

/**
* RuleClass for NetefxValidator
* 
* @version 0.45
* @package NetefxValidator
* 
* new in 0.44: Konstanten
*/

// basic rules
define("NV_REQUIRED", "REQUIRED");
define("NV_EMPTY", "EMPTY");
define("NV_EXISTS", "EXISTS");

// logical rules
define("NV_OR", "OR");
define("NV_AND", "AND");
define("NV_NOT", "NOT");
define("NV_IMPLIES", "IMPLIES");
define("NV_XOR", "XOR");

// numeric rules
define("NV_GREATER","GREATER");
define("NV_GREATEREQUAL","GREATEREQUAL");
define("NV_SMALLER","SMALLER");
define("NV_SMALLEREQUAL","SMALLEREQUAL");
define("NV_EQUALS","EQUALS");
define("NV_BETWEEN","BETWEEN");

// regular expression rule
define("NV_REGEXP","REGEXP");

// textual rules
define("NV_TEXTEQUALS","TEXTEQUALS");
define("NV_TEXTIS","TEXTIS");
define("NV_TEXTCONTAINS","TEXTCONTAINS");
define("NV_ISONEFROM","ISONEFROM");
define("NV_ISNOTONEFROM","ISNOTONEFROM");
define("NV_MINCHARACTERS","MINCHARACTERS");
define("NV_MAXCHARACTERS","MAXCHARACTERS");
define("NV_CHARACTERSBETWEEN","CHARACTERSBETWEEN");

// unique rule
define("NV_UNIQUE","UNIQUE");

// function rule
define("NV_FUNCTION","FUNCTION");



class NetefxValidatorRule {
	
	
	
	
		protected $field;
		protected $type;
		protected $args;		
		protected $errorMsg;
		protected $errorMsgType;
		
		/**
		 * Der Konstruktor erwartet folgende Argumente:
		 * - das Feld, für das die Regel gilt
		 * - der Typ der Regel (erlaubt: "BETWEEN", "EQUALS", "GREATER", "GREATEREQUAL", "OR", "REGEXP",
		 *  "REQUIRED", "EMPTY", "SMALLER", "SMALLEREQUAL", "TEXTEQUALS", "FUNCTION"
		 * - ein Array mit den (typspezifischen) Argumenten der Regel
		 * - die Fehlermeldung, die angezeigt werden soll, wenn die Regel nicht erfüllt ist
		 * - Typ der Fehlermeldung (zb: error, required, good, ...)
		 */
		function __construct($field, $type, $args, $errorMsg, $errorMsgType = "error") {	
			$this->field= $field;
			$this->type= $type;
			$this->args= $args;
			$this->errorMsg= $errorMsg;
			$this->errorMsgType= $errorMsgType;
		}
		
		/**
		 * Wertet den übergebenen Ausdruck aus, wobei Namen von anderen Feldern in die Zeichen @ und ~ eingeschlossen sind.
		 * Aus Sicherheitsgründen und da nur mit Zahlen gerechnet werden kann, muss unbedingt vorher checkNumeric() aufgerufen werden.
		 */		
		function evaluate($data,$expr) {
            $expr = str_replace ('@','$data["',$expr);
			$expr = str_replace ('~','"]',$expr);
			$expr = "return ".$expr.";";
			return eval($expr);
		}
		
		/**
		 * Überprüft, ob die im Ausdruck enthaltenen Felder nur Zahlen enthalten
		 */
		function checkNumeric($data,$expr) {			
			$pos = -1;
			$pos2 = -1;
			do {
				$pos = strpos ($expr,'@', $pos+1);
				$pos2 = strpos ($expr,'~', $pos2+1);
				if ($pos !== false) {
					$fieldname = substr ($expr,$pos+1,$pos2-$pos-1);
					if (!is_numeric($data[$fieldname])) {		
						return false;
					}
				}
				else {
					return true;
				}
				
			}
			while (true);
		}
		
		/**
		 * Validiert die Regel anhand der übergebenen Daten
		 */
		function validate($data){
			$method_name = "validate{$this->type}";
			if (method_exists($this, $method_name)) {
				return $this->$method_name($data);
			}     
			else {				
				$this->errorMsg = "Der Typ ".$this->type." wird (noch) nicht unterstützt.";
				return false;
			}
		}
		
		/**
         * Validierung einer REQUIRED-Regel
         * Überprüft, ob überhaupt etwas in das Feld eingetragen ist
         * 
         * Beispiel:  
         * $rule_vorname_1 = new NetefxValidatorRule("Vorname", "REQUIRED", "", "Vorname fehlt (Pflichtfeld)");
         * 
         */
        function validateRequired($data) {
            return ($data[$this->field] != '');
        }
        
        /**
         * Validierung einer EMPTY-Regel
         * Überprüft, ob nichts ins Feld eingetragen ist
         * 
         * Beispiel:  
         * analog zu REQUIRED (Verwendung meistens in Kombination mit OR)
         * 
         */
        function validateEmpty($data) {
            return ($data[$this->field] == '');
        }
        
        /**
         * Validierung einer EXISTS-Regel
         * Überprüft, ob es das Feld überhaupt gibt im Formular
         * Sinnvoll für IMPLIES Verknüpfungen
         * 
         * Beispiel:  
         * $rule_emailSenden_Exists             = new NetefxValidatorRule ("emailSenden",  "EXISTS", "", "");
         * $rule_emailSenden_Required           = new NetefxValidatorRule ("emailSenden",  "REQUIRED", "", ""); 
         * $rule_emailSenden_Exists_Required    = new NetefxValidatorRule ("emailSenden",  "IMPLIES", array($rule_emailSenden_Exists, $rule_emailSenden_Required), "Bitte wählen Sie emailSenden aus.");
         * 
         */
        function validateExists($data) {
	        return (isset($data[$this->field]));
        }
        
        /**
		 * Validierung einer OR-Regel
		 * Überprüft, ob mindestens eine der Subregeln, aus denen diese Regel besteht, gültig sind.
		 * Beispiel: $rule_id_3 = new NetefxValidatorRule("Name", "OR", array($rule_id_1,$rule_id_2), "ID muss mind. 3 Zeichen haben (oder leer sein)"); 
		 */
		function validateOr($data) {
			foreach ($this->args as $rule) {
				if ($rule->validate($data)) {
					return true;
				}
			}  
			return false;
		}
		
        /**
		 * Validierung einer AND-Regel
		 * Überprüft, ob alle Subregeln, aus denen diese Regel besteht, gültig sind.
		 * Beispiel:  $rule_firma_5 = new NetefxValidatorRule("Firma",   "AND",   array($rule_firma_2,$rule_firma_3, $rule_firma_4), "Firma muss 'Netefx', 'Internet' und 'Services' enthalten.");  
		 */
		function validateAnd($data) {
			foreach ($this->args as $rule) {
				if (!($rule->validate($data))) {
					return false;
				}
			}  
			return true;
		}
		
        /**
		 * Validierung einer NOT-Regel
		 * Überprüft, ob die Subregeln, aus denen diese Regel besteht, nicht erfüllt ist.
		 * Beispiel: $rule_name_2 = new NetefxValidatorRule("Name",   "NOT",   array($rule_name_1), "Name darf nicht 'Schulz' enthalten.");  
		 */
		function validateNot($data) {
			return (!($this->args[0]->validate($data)));
		}
		
        /**
		 * Validierung einer IMPLIES-Regel
		 * Überprüft, ob nachfolgende Bedingung erfüllt ist: wenn die erste Regel erfüllt ist, muss es auch die zweite sein.
		 * Beispiel: $rule_name_4 = new NetefxValidatorRule("Name",   "IMPLIES",   array($rule_anrede_1, $rule_name_3), "Wenn Anrede 'Herr' ist, muss Name auch 'Herr' enthalten"); 
		 */
		function validateImplies($data) {
			if ($this->args[0]->validate($data)) {
				return ($this->args[1]->validate($data));
			}
			else {
				return true;
			}
		}
		
        /**
		 * Validierung einer XOR-Regel
		 * Überprüft, ob nachfolgende Bedingung erfüllt ist: entweder die erste Regel oder die zweite muss erfüllt sein (aber nicht beide)
		 * Beispiel: $rule_name_4 = new NetefxValidatorRule("Name",   "XOR",   array($rule_anrede_1, $rule_name_3), "Wenn Anrede 'Herr' ist, muss Name auch 'Herr' enthalten"); 
		 */
		function validateXOR($data) {
			if ($this->args[0]->validate($data)) {
				return (!($this->args[1]->validate($data)));
			}
			else {
				return ($this->args[1]->validate($data));
			}
		}
		
		/**
		 * Validierung einer GREATER-Regel
		 * Überprüft, ob der Inhalt des Feldes numerisch ist und größer als der angegebene Ausdruck
		 * 
		 *  Beispiel 1: $rule_anzahl_1 = new NetefxValidatorRule("Anzahl", "GREATER", array('10'),  "Anzahl muss mindestens 10 sein");
		 *  Beispiel 2:	$rule_c_1      = new NetefxValidatorRule("C",      "GREATER", array('2*@A~+@B~'), "C>2*A+B muss erfüllt sein");
		 * 
		 */
		function validateGreater($data) {
  			
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            
            if (!is_numeric($data[$this->field])) {		
				return false;    
			}
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			
			return ($data[$this->field] > $this->evaluate($data,$this->args[0]));
		}
		
        /**
        * wandelt z.B. deutsche Zahleneingaben ins englische Format um
        * 
        * @param string $number
        * @param string $separator ("." oder ",")
        */
		function numberFormatConversion($number, $separator) {
            
			if (preg_match("/^[0-9".$separator."]{1,}$/", $number)>0) {
				$number = str_replace($separator,".",$number);           
	            return $number;
			}
			
			else {
				return false;
			}
			
        }
        
        
        /**
         * Validierung einer GREATEREQUAL-Regel
         * Überprüft, ob der Inhalt des Feldes numerisch ist und größer gleich dem angegebenen Ausdruck
         * 
         * Beispiel: analog zu GREATER
         */
        function validateGreaterEqual($data) {
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            if (!is_numeric($data[$this->field])) {        
                return false;
            }
            if (!$this->checkNumeric($data,$this->args[0])) {
                return false;
            }
            return ($data[$this->field] >= $this->evaluate($data,$this->args[0]));
        }
        
        /* Der Wert wurde vom User mit , als Trennzeichen eingegeben (also in deutscher Schreibweise)
		 
		function validateGreaterEqualGermanFloat($data) {
            
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            
            $wert = str_replace(".","",$data[$this->field]);
			$wert = str_replace(",",".",$wert);
            if (!is_numeric($wert)) {		
				return false;
			}
            
            if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
            return ($wert >= $this->evaluate($data,$this->args[0]));
		}
		*/
        
        /**
		 * Validierung einer SMALLER-Regel
		 * Überprüft, ob der Inhalt des Feldes numerisch ist und kleiner als der angegebene Ausdruck
		 * 
         * Beispiel: analog zu GREATER
		 */
		function validateSmaller($data) {
			
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            
            if (!is_numeric($data[$this->field])) {		
				return false;
			}
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			return ($data[$this->field] < $this->evaluate($data,$this->args[0]));
		}
		
		/**
		 * Validierung einer SMALLEREQUAL-Regel
		 * Überprüft, ob der Inhalt des Feldes numerisch ist und kleiner gleich dem angegebenen Ausdruck
		 * 
         * Beispiel: analog zu GREATER
		 */
		function validateSmallerEqual($data) {
			
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            
            if (!is_numeric($data[$this->field])) {		
				return false;
			}
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			return ($data[$this->field] <= $this->evaluate($data,$this->args[0]));
		}
		
		/**
		 * Validierung einer EQUALS-Regel
		 * Überprüft, ob der Inhalt des Feldes numerisch ist und gleich dem angegebenen Ausdruck
		 * 
		 * Beispiel: analog zu GREATER
		 */
		function validateEquals($data) {
			
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[1]) ? $this->args[1] : "."));
            
            if (!is_numeric($data[$this->field])) {		
				return false;
			}
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			return ($data[$this->field] == $this->evaluate($data,$this->args[0]));
		}
		
		/**
		 * Validierung einer BETWEEN-Regel
		 * Überprüft, ob der Inhalt des Feldes numerisch ist und zwischen den angegebenen Ausdrücken (Grenzen eingeschlossen) liegt
		 * 
		 * $rule_anzahl_1 = new NetefxValidatorRule("Anzahl", "BETWEEN", array('10','20'), "Anzahl muss zwischen 10 und 20 liegen");
		 * 
		 */
		function validateBetween($data) {
			
            $data[$this->field] = $this->numberFormatConversion($data[$this->field], (isset($this->args[2]) ? $this->args[2] : "."));
            
            if (!is_numeric($data[$this->field])) {		
				return false;
			}
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			if (!$this->checkNumeric($data,$this->args[1])) {
				return false;
			}
			return (($data[$this->field] <= $this->evaluate($data,$this->args[1])) &&
			        ($data[$this->field] >= $this->evaluate($data,$this->args[0])));
		}
		
		/**
		 * Validierung einer REGEXP-Regel
		 * Überprüft, ob der Inhalt des Feldes dem angegebenen regulären Ausdruck gehorcht
		 * 
		 * Beispiel: $rule_name_1  = new NetefxValidatorRule("Name",  "REGEXP", array("/^.{2,}$/"), "Name fehlt/ist fehlerhaft (Pflichtfeld)");
		 */
		function validateRegExp($data) {
			return preg_match($this->args[0], $data[$this->field])>0;
		}
		
		/**
		 * Validierung einer TEXTEQUALS-Regel
		 * Überprüft, ob der Inhalt des Feldes dem Inhalt des angegebenen Feldes entspricht
		 * 
		 * Beispiel: $rule_passwort2_1 = new NetefxValidatorRule("Passwort2", "TEXTEQUALS", array('Passwort'), "Passwort und Passwort2 sind nicht identisch");
		 */
		function validateTextEquals($data) {
			return (strcmp($data[$this->field],$data[$this->args[0]])==0);
		}
		
		/**
		 * Validierung einer TEXTIS-Regel
		 * Überprüft, ob der Inhalt des Feldes exakt dem angegebenen Text entspricht
		 * 
		 * Beispiel: $rule_firma_1 = new NetefxValidatorRule("Firma",   "TEXTIS",   array('Netefx'), "Firma muss Netefx sein.");  
		 */
		function validateTextIs($data) {
			return (strcmp($data[$this->field],$this->args[0])==0);
		}
		
		/**
		 * Validierung einer TEXTCONTAINS-Regel
		 * Überprüft, ob der Inhalt des Feldes den angegebenen Text enthält
		 * 
		 * Beispiel: $rule_firma_2 = new NetefxValidatorRule("Firma",   "TEXTCONTAINS",   array('Netefx'), "Firma muss Netefx enthalten.");  
		 */
		function validateTextContains($data) {
			$pos = strpos ($data[$this->field],$this->args[0]);
			return ($pos !== false);
		}
		
		/**
		 * Validierung einer ISONEFROM-Regel
		 * Überprüft, ob der Inhalt des Feldes exakt einem der angegebenen Texte entspricht
		 * 
		 * Beispiel: $rule_OnlyParisAndBerlin = new NetefxValidatorRule("Flat", "ISONEFROM", array("Paris","Berlin"), "Only Paris and Berlin are available at the moment.");
		 */
		function validateIsOneFrom($data) {
			foreach ($this->args as $text) {
				if (strcmp($data[$this->field],$text)==0) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Validierung einer ISNOTONEFROM-Regel
		 * Überprüft, ob der Inhalt des Feldes exakt einem der angegebenen Texte entspricht
		 * 
		 * Beispiel: $rule_NotParisAndBerlin = new NetefxValidatorRule("Flat", "ISNOTONEFROM", array("Paris","Berlin"), "Paris and Berlin are not available at the moment.");
		 */
		function validateIsNotOneFrom($data) {
			foreach ($this->args as $text) {
				if (strcmp($data[$this->field],$text)==0) {
					return false;
				}
			}
			return true;
		}
		
		/**
		 * Validierung einer MINCHARACTERS-Regel
		 * Überprüft, ob der Inhalt des Feldes mindestens die angegebene Länge hat
		 * 
		 * Beispiel: $rule_FirstName_MinChar = new NetefxValidatorRule("FirstName", "MINCHARACTERS", array('2'), "Please enter at least two characters");
		 */
		function validateMinCharacters($data) {
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			return (strlen(trim($data[$this->field])) >= $this->evaluate($data,$this->args[0]));
		}
		
		/**
		 * Validierung einer MAXCHARACTERS-Regel
		 * Überprüft, ob der Inhalt des Feldes höchstens die angegebene Länge hat
		 * 
		 * Beispiel: $rule_FirstName_MinChar = new NetefxValidatorRule("FirstName", "MINCHARACTERS", array('2'), "Please enter at least two characters");
		 */
		function validateMaxCharacters($data) {
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			return (strlen(trim($data[$this->field])) <= $this->evaluate($data,$this->args[0]));
		}
		
		/**
		 * Validierung einer CHARACTERSBETWEEN-Regel
		 * Überprüft, ob die Länge des Inhalts des Feldes zwischen den angegebenen Grenzen (eingeschlossen) liegt
		 * 
		 * Beispiel: $rule_FirstName_CharBetween = new NetefxValidatorRule("FirstName", "CHARACTERSBETWEEN", array('2','20'), "Please enter between 2 and 20 characters");
		 */
		function validateCharactersBetween($data) {
			if (!$this->checkNumeric($data,$this->args[0])) {
				return false;
			}
			if (!$this->checkNumeric($data,$this->args[1])) {
				return false;
			}
			return ((strlen(trim($data[$this->field])) >= $this->evaluate($data,$this->args[0])) &&
			(strlen(trim($data[$this->field])) <= $this->evaluate($data,$this->args[1])));
		}
		
		
		/**
         * Validierung einer UNIQUE-Regel
         * Überprüft, ob es noch keinen ANDEREN Eintrag gibt, bei dem das übergebene Feld der übergebenen Klasse diesen Wert hat
         * 
         * Beispiel:  
         * $rule_email_1 = new NetefxValidatorRule("EMail", "UNIQUE",  array('EMail','Member'), "E-Mail schon vergeben");
         * 
         */
        function validateUnique($data) {
        	$new_value = $data[$this->field];
            $field_name = $this->args[0];
			$class_name = $this->args[1];
        	if (isset($data["ID"])) {
        		$other_entry = DataObject::get($class_name,"".$field_name." = '".$new_value."' AND ".$class_name.".ID<>".$data["ID"]); 
        	}
            else {
            	$other_entry = DataObject::get($class_name,"".$field_name." = '".$new_value."'");     	
            }
            return ($other_entry) ? false : true; 
        }
		
		/**
		 * Validierung einer FUNCTION-Regel
		 * Überprüft, ob die angegebene Funktion true zurückgibt
		 * 
		 * Beispiel: siehe Library
		 */
		function validateFunction($data) {
			$class = $this->args[0];
			$function = $this->args[1];
			$params = $this->args[2]; 
			return call_user_func(array($class, $function), $data, $params);
		}
		
		/**
		 * Gibt das Feld zurück, zu dem diese Regel gehört
		 */		
		function field(){
			return $this->field;
		}
		
		/**
		 * Gibt die Fehlermeldung zu dieser Regel zurück
		 */	
		function errorMsg(){
			return $this->errorMsg;
		}
		
		/**
		 * Gibt den Typ der Fehlermeldung zu dieser Regel zurück
		 */	
		function errorMsgType(){
			return $this->errorMsgType;
		}
		
        
        
        
		
		
		
}


?>
