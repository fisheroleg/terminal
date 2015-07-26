<?php
// tests/PostTest.php
class ContentTest extends PHPUnit_Framework_TestCase {
	private $CI;

	public function setUp() {
		$this->CI = &get_instance();
	}

	/** 
	* @dataProvider providerPower 
	*/
	
	public function testMult($matrix, $res) {
		//
		//$res = $matrix[1];
		//$matrix = $matrix[0];
		$this->CI->load->model('content_model');
		$this->CI->content_model->init($matrix);
		
		$this->CI->content_model->multiple();
		$matrix = $this->CI->content_model->get();//output();
		
		$this->assertEquals($res, $matrix);
	}
	
	public function providerPower ()
	{
		return array (
			array(
				array (
					array(2,3),
					array(3,4)
				),
				
				array (
					array(4,9),
					array(6,12)
				)
			),
			array (
				array (
					array(-2,-3),
					array(-3,-4)
				),
				
				array (
					array(6,12),
					array(9,16)
				),
			), 
			array (
				array (
					array(-2,3),
					array(3,0)
				),
				
				array (
					array(4,0),
					array(-6,0)
				),
			)
		);
	}
}