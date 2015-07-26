<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('data_model','user_model'));
		$this->load->database();
		$this->load->library('session');
		$this->load->helper(array("cookie","url"));
	}
	
	/**
	 * ������� ������������ ������� ������� �����, ����� ���������
	 * @param var $ajax true, ���� ����� �����������
	 * @return true None
	 */
	private function blocsBefore()
	{
		
	}
	
	/**
	 * ����� ���� ��������
	 * @param var $ajax true, ���� ����� �����������
	 * @return true None
	 */
	private function blocksAfter()
	{
		
	}
	
	/**
	 * Summary
	 * @return object  Description
	 */
	function index()
	{
		$this->load->view('login_view');
	}
	
	function check_key()
	{
		if ( $this->input->post('key') == "asu-methods" )
		{
			$this->session->set_userdata(array("logged"=>true));
			echo json_encode(array("status"=>"ok"));
		}
		else echo json_encode(array("status"=>"not"));
	}
	
	function menu($ajax = false)
	{
		$data = $this->session->userdata("user");
		if ( $data['logged'] )
		{
			echo $this->load->view("menu_view",$this->user_model->check_logged(),true);
		}
		else redirect('/');
	}
	
	function signup()
	{
		$this->load->view('signin_view');
	}
	
	function signout()
	{
		$this->user_model->unset_session();
		redirect('/');
	}
}
