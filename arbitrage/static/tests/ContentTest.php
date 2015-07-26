<?php
// tests/PostTest.php
class ContentTest extends PHPUnit_Framework_TestCase {
	private $CI;

	public function setUp() {
		$this->CI = &get_instance();
	}

	public function testGetAllPosts() {
		$this->CI->load->model('content_model');
		$posts = $this->CI->content_model->getProducts();
		$this->assertEquals(false, $posts);
	}
}