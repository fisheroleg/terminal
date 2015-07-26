<?php

abstract class Calculator {
	private function calc_exp($x)
	{
		return exp($x);
	}
	
	public function taylor($x)
	{
		
	}
}

// tests/PostTest.php
class ContentTest extends PHPUnit_Framework_TestCase {
	private $CI;

	public function setUp() {
		$this->CI = &get_instance();
	}
	
	public function test_data_model_get()
	{
		try{
			$this->CI->load->model('data_model');
			$this->assertEquals(true, $this->CI->data_model->get_num_markets()==false);
			$this->assertEquals(false, $this->CI->data_model->get_num_products()==false);
			$this->assertEquals(false, $this->CI->data_model->get_markets()==false);
			$this->assertEquals(false, $this->CI->data_model->get_products()==false);
			$this->assertEquals(false, $this->CI->data_model->get_items()==false);
		}catch(Exception $e){
			print $e;
		}
	}
	
	public function test_superadmin()
	{
		try{
			$this->CI->load->model('user_model');
			$this->assertEquals(false, $this->CI->user_model->getProfileInfo(1)==false);
		}catch(Exception $e){
			print $e;
		}
	}
	
	/** 
	* @dataProvider providerPower 
	*/
	
	public function test_register($data)
	{
		try{
			$this->CI->load->model('user_model');
			$this->assertEquals(false, $this->CI->user_model->register($data)==false);
		}catch(Exception $e){
			print $e;
		}
	}
	
	public function testMult($matrix, $res) {
		$this->CI->load->model('content_model');
		$this->CI->content_model->init($matrix);
		
		$this->CI->content_model->multiple();
		$matrix = $this->CI->content_model->get();
		
		$this->assertEquals($res, $matrix);
	}
	//
	//public function providerPower ()
	//{
	//	return array (
	//		array(
	//			"firstname"=>"Ok",
	//			"surname"=>"Ok",
	//			"password"=>"qweqwe123",
	//			"mail"=>
	//		),
	//		array (
	//			array (
	//				array(-2,-3),
	//				array(-3,-4)
	//			),
	//			
	//			array (
	//				array(6,12),
	//				array(9,16)
	//			),
	//		), 
	//		array (
	//			array (
	//				array(-2,3),
	//				array(3,0)
	//			),
	//			
	//			array (
	//				array(4,0),
	//				array(-6,0)
	//			),
	//		)
	//	);
	//}
}