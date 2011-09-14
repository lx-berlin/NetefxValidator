<?php

/**
 * @package NetefxValidator
 * @author lx-berlin
 * @author Zauberfisch
 */
class NetefxValidatorTest extends SapphireTest {
	
	public static $fixture_file = 'NetefxValidator/code/tests/NetefxValidatorTest.yml';
	
	public function testREQUIRED() {
		$rule = new NetefxValidatorRuleREQUIRED('testField');

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));
	}

	public function testEMPTY() {
		$rule = new NetefxValidatorRuleEMPTY('testField');

		$data = array('testField' => '');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => 'test');
		$this->assertFalse($rule->validate($data));
	}

	public function testEXISTS() {
		$rule = new NetefxValidatorRuleEXISTS('testField');

		$data = array();
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));
	}

	public function testOR() {
		$rule = new NetefxValidatorRuleOR('testFieldOR', null, null, array(
			new NetefxValidatorRuleREQUIRED('testField'),
			new NetefxValidatorRuleEMPTY('testField2'),
			new NetefxValidatorRuleEXISTS('testField3')
		));
		
		$data = array('testField' => 'test', 'testField2' => 'test');
		$this->assertTrue($rule->validate($data)); // rule 1 is valid
		
		$data = array('testField' => '', 'testField2' => '');
		$this->assertTrue($rule->validate($data)); // rule 2 is valid
		
		$data = array('testField' => '', 'testField2' => 'test', 'testField3' => '');
		$this->assertTrue($rule->validate($data)); // rule 3 is valid
		
		
		$data = array('testField' => 'test', 'testField2' => '');
		$this->assertTrue($rule->validate($data)); // rule 1 & 2 are valid
		
		$data = array('testField' => '', 'testField2' => '', 'testField3' => '');
		$this->assertTrue($rule->validate($data)); // rule 2 & 3 are valid
		
		$data = array('testField' => 'test', 'testField2' => 'test', 'testField3' => '');
		$this->assertTrue($rule->validate($data)); // rule 1 & 3 is valid
		
		
		$data = array('testField' => 'test', 'testField2' => '', 'testField3' => '');
		$this->assertTrue($rule->validate($data)); // rule 1 & 2 & 3 is valid
		
		$data = array('testField' => '', 'testField2' => 'test');
		$this->assertFalse($rule->validate($data)); // none valid

	} 


	public function testAND() {
		$rule = new NetefxValidatorRuleAND('testFieldAND', null, null, array(
			new NetefxValidatorRuleREQUIRED('testField'),
			new NetefxValidatorRuleEMPTY('testField2'),
			new NetefxValidatorRuleEXISTS('testField3')
		));
		
		$data = array('testField' => 'test', 'testField2' => 'test');
		$this->assertFalse($rule->validate($data)); // rule 1 is valid
		
		$data = array('testField' => '', 'testField2' => '');
		$this->assertFalse($rule->validate($data)); // rule 2 is valid
		
		$data = array('testField' => '', 'testField2' => 'test', 'testField3' => '');
		$this->assertFalse($rule->validate($data)); // rule 3 is valid
		
		
		$data = array('testField' => 'test', 'testField2' => '');
		$this->assertFalse($rule->validate($data)); // rule 1 & 2 are valid
		
		$data = array('testField' => '', 'testField2' => '', 'testField3' => '');
		$this->assertFalse($rule->validate($data)); // rule 2 & 3 are valid
		
		$data = array('testField' => 'test', 'testField2' => 'test', 'testField3' => '');
		$this->assertFalse($rule->validate($data)); // rule 1 & 3 is valid
		
		
		$data = array('testField' => 'test', 'testField2' => '', 'testField3' => '');
		$this->assertTrue($rule->validate($data)); // rule 1 & 2 & 3 is valid
		
		$data = array('testField' => '', 'testField2' => 'test');
		$this->assertFalse($rule->validate($data)); // none valid
	}
	
	public function testXOR() {
		$rule = new NetefxValidatorRuleXOR('testFieldXOR', null, null, array(
			new NetefxValidatorRuleREQUIRED('testField'),
			new NetefxValidatorRuleEMPTY('testField2'),
			new NetefxValidatorRuleEXISTS('testField3')
		));
		
		$data = array('testField' => 'test', 'testField2' => 'test');
		$this->assertTrue($rule->validate($data)); // rule 1 is valid
		
		$data = array('testField' => '', 'testField2' => '');
		$this->assertTrue($rule->validate($data)); // rule 2 is valid
		
		$data = array('testField' => '', 'testField2' => 'test', 'testField3' => '');
		$this->assertTrue($rule->validate($data)); // rule 3 is valid
		
		
		$data = array('testField' => 'test', 'testField2' => '');
		$this->assertFalse($rule->validate($data)); // rule 1 & 2 are valid
		
		$data = array('testField' => '', 'testField2' => '', 'testField3' => '');
		$this->assertFalse($rule->validate($data)); // rule 2 & 3 are valid
		
		$data = array('testField' => 'test', 'testField2' => 'test', 'testField3' => '');
		$this->assertFalse($rule->validate($data)); // rule 1 & 3 is valid
		
		
		$data = array('testField' => 'test', 'testField2' => '', 'testField3' => '');
		$this->assertFalse($rule->validate($data)); // rule 1 & 2 & 3 is valid
		
		$data = array('testField' => '', 'testField2' => 'test');
		$this->assertFalse($rule->validate($data)); // none valid
	}

	public function testNOT() {
		$rule = new NetefxValidatorRuleNOT('testField', null, null, new NetefxValidatorRuleREQUIRED('testField2'));

		$data = array('testField2' => '');
		$this->assertTrue($rule->validate($data));

		$data = array('testField2' => 'test');
		$this->assertFalse($rule->validate($data));
	}


	public function testIMPLIES() {
		$rule = new NetefxValidatorRuleIMPLIES('testFieldIMPLIES', null, null, array(new NetefxValidatorRuleREQUIRED('testField'), new NetefxValidatorRuleREQUIRED('testField2')));

		$data = array('testField' => '');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '', 'testField2' => '');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '', 'testField2' => 'test');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test', 'testField2' => 'test');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => 'test', 'testField2' => '');
		$this->assertFalse($rule->validate($data));
	}

	public function testGREATER() {
		$rule = new NetefxValidatorRuleGREATER('testField', null, null, 10); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertTrue($rule->validate($data));
		

		$rule = new NetefxValidatorRuleGREATER('testField', null, null, array(10, ',')); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertTrue($rule->validate($data));
	}
	
	
	public function testGREATEREQUAL() {
		$rule = new NetefxValidatorRuleGREATEREQUAL('testField', null, null, 10); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
			
		$data = array('testField' => '10.0');
		$this->assertTrue($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertTrue($rule->validate($data));
		

		$rule = new NetefxValidatorRuleGREATEREQUAL('testField', null, null, array(10, ',')); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
	
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertTrue($rule->validate($data));
	}

	public function testSMALLER() {
		$rule = new NetefxValidatorRuleSMALLER('testField', null, null, 10); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertFalse($rule->validate($data));
		

		$rule = new NetefxValidatorRuleSMALLER('testField', null, null, array(10, ',')); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertFalse($rule->validate($data));
	}

	public function testSMALLEREQUAL() {
		$rule = new NetefxValidatorRuleSMALLEREQUAL('testField', null, null, 10); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
				
		$data = array('testField' => '10.0');
		$this->assertTrue($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertFalse($rule->validate($data));
		

		$rule = new NetefxValidatorRuleSMALLEREQUAL('testField', null, null, array(10, ',')); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
				
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertFalse($rule->validate($data));
	}

	public function testEQUALS() {
		$rule = new NetefxValidatorRuleEQUALS('testField', null, null, 10); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.0');
		$this->assertTrue($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertFalse($rule->validate($data));
		

		$rule = new NetefxValidatorRuleEQUALS('testField', null, null, array(10, ',')); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertFalse($rule->validate($data));
	}

	public function testBETWEEN() {
		$rule = new NetefxValidatorRuleBETWEEN('testField', null, null, array(10, 20)); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.0');
		$this->assertTrue($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '19');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '20');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '20.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '20,1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '21');
		$this->assertFalse($rule->validate($data));
		
		$rule = new NetefxValidatorRuleBETWEEN('testField', null, null, array(10, 20, ',')); 

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9.9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '9,9');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.0');
		$this->assertFalse($rule->validate($data));
				
		$data = array('testField' => '10,0');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '10.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '10,1');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => '11');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => '19');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '20');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '20.1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '20,1');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '21');
		$this->assertFalse($rule->validate($data));
	}

	public function testREGEXP() {
		$rule = new NetefxValidatorRuleREGEXP('testField');
		// TODO write this test
	}
	
	public function testTEXTEQUALS() {
		$rule = new NetefxValidatorRuleTEXTEQUALS('testField', null, null, 'testField2');

		$data = array('testField' => '', 'testField2' => '');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => '', 'testField2' => ' ');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'test', 'testField2' => 'test');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test', 'testField2' => 'Test');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'test', 'testField2' => 'test2');
		$this->assertFalse($rule->validate($data));
	}

	public function testTEXTIS() {
		$rule = new NetefxValidatorRuleTEXTIS('testField', null, null, 'test');

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'TeSt');
		$this->assertFalse($rule->validate($data));
		
		
		$rule = new NetefxValidatorRuleTEXTIS('testField', null, null, '');

		$data = array('testField' => '');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => ' ');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => 'test');
		$this->assertFalse($rule->validate($data));
	}
	
	public function testTEXTCONTAINS() {
		$rule = new NetefxValidatorRuleTEXTCONTAINS('testField', null, null, 'test');

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'TeSt');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'testtestttest');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'xtesxtx');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'testxx');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'xxtestxx');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'xxtest');
		$this->assertTrue($rule->validate($data));
	}

	public function testINARRAY() {
		$rule = new NetefxValidatorRuleINARRAY('testField', null, null, array('zauberfisch', 'is', 'awesum'));

		$data = array('testField' => 'zauberfisch is awesum');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => 'awesum');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => ' is ');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'fisch');
		$this->assertFalse($rule->validate($data));
	}
	
	public function testNOTINARRAY() {
		$rule = new NetefxValidatorRuleNOTINARRAY('testField', null, null, array('zauberfisch', 'is', 'awesum'));

		$data = array('testField' => 'zauberfisch is awesum');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => 'awesum');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => ' is ');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'fisch');
		$this->assertTrue($rule->validate($data));
	}

	public function testMINCHARACTERS() {
		$rule = new NetefxValidatorRuleMINCHARACTERS('testField', null, null, 10);

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '123456789');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '1234567890');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '12345678901');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => 'testtest  ');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => ' testtest  ');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'test test ');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'test test1');
		$this->assertTrue($rule->validate($data));
	}
	
	public function testMAXCHARACTERS() {
		$rule = new NetefxValidatorRuleMAXCHARACTERS('testField', null, null, 9);

		$data = array('testField' => '');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '123456789');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '1234567890');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '12345678901');
		$this->assertFalse($rule->validate($data));

		$data = array('testField' => ' testtest  ');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test test ');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test test1');
		$this->assertFalse($rule->validate($data));
	}

	public function testCHARACTERSBETWEEN() {
		$rule = new NetefxValidatorRuleCHARACTERSBETWEEN('testField', null, null, array(9, 11));

		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '12345678');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => '123456789');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '1234567890');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '12345678901');
		$this->assertTrue($rule->validate($data));

		$data = array('testField' => ' testtest  ');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'test test ');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test test1');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test test 1');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => 'test test 12');
		$this->assertFalse($rule->validate($data));
	}

	public function testUNIQUE() {
		$rule = new NetefxValidatorRuleUNIQUE('testField', null, null, array('Page', 'URLSegment'));
		
		$data = array('testField' => 'home');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 'siteThatDoesNotExist');
		$this->assertTrue($rule->validate($data));
		
		$data = array('testField' => '1-1-test-product');
		$this->assertFalse($rule->validate($data));
		
		$data = array('testField' => 1930);
		$this->assertFalse($rule->validate($data));
		
		
		$rule = new NetefxValidatorRuleUNIQUE('testField', null, null, array('Page', 'URLSegment', 'testField', 'URLSegment'));
		
		$data = array('testField' => 'home');
		$this->assertTrue($rule->validate($data));
	}

	public function testFUNCTION() {
		$code = 'if ($data["testField"] == "test") $return = true; else $return = false;';
		$rule = new NetefxValidatorRuleFUNCTION("testField", null, null, $code);
		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));
		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));

		$class = 'NetefxValidatorTest';
		$function = 'helper_FUNCTION_var';
		$args = false;
		$rule = new NetefxValidatorRuleFUNCTION("testField", null, null, array($class, $function, $args));
		$data = array('testField' => 'test');
		$this->assertFalse($rule->validate($data));

		$class = 'NetefxValidatorTest';
		$function = 'helper_FUNCTION_var';
		$args = true;
		$rule = new NetefxValidatorRuleFUNCTION("testField", null, null, array($class, $function, $args));
		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));

		$object = new NetefxValidatorTest();
		$function = 'helperFUNCTIONvar';
		$args = false;
		$rule = new NetefxValidatorRuleFUNCTION("testField", null, null, array($object, $function, $args));
		$data = array('testField' => 'test');
		$this->assertFalse($rule->validate($data));

		$object = new NetefxValidatorTest();
		$function = 'helperFUNCTIONvar';
		$args = true;
		$rule = new NetefxValidatorRuleFUNCTION("testField", null, null, array($object, $function, $args));
		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));

		$function = create_function('$data,$args', 'if ($data["testField"] == "test") return true; else return false;');
		$args = array();
		$rule = new NetefxValidatorRuleFUNCTION("testField", null, null, array($function, $args));
		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));
		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));

		$function = function($data, $args) {
			if ($data['testField'] == 'test')
				return true;
			else
				return false;
		};
		$args = array();
		$rule = new NetefxValidatorRuleFUNCTION("testField", null, null, array($function, $args));
		$data = array('testField' => 'test');
		$this->assertTrue($rule->validate($data));
		$data = array('testField' => '');
		$this->assertFalse($rule->validate($data));
	}

	public static function helper_FUNCTION_var($data = null, $args = null) { return $args; }
	public function helperFUNCTIONvar($data = null, $args = null) { return $args; }
}