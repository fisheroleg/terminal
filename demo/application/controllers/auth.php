<?php
    class Auth extends CI_Controller{
     
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(array("user_model"));
        $this->load->library('session');
    }
 
   //index where the controller starts
    public function index() 
    {
        
    }
    
    public function login()
    {
        try {
            $data = $this->input->post(NULL);
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            if($this->form_validation->run() == false)
            {
                print json_encode(array("status"=>"not", "message"=>validation_errors("<li>","</li>")));
                //die(validation_errors());//$this->load->view('registration_view');
            }
            else
            {
                if ($this->user_model->login($data['username'],$data['password'])) {
                    $this->user_model->set_session($data['username'],$data['password']);
                    print json_encode(array("status"=>"ok"));
                }
                else {
                    print json_encode(array("status"=>"not", "message"=>"Incorrect user or password"));
                }
            }
        }catch (Exception $e) {
            echo $e->getMEssage();
        }
    }
    
    public function signup()
    {
        try {
            $data = $this->input->post(NULL);
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

            if($this->form_validation->run() == false)
            {
                print json_encode(array("status"=>"not", "message"=>validation_errors("<li>","</li>")));
                //die(validation_errors());//$this->load->view('registration_view');
            }
            else
            {
                if ($this->user_model->signup($data)) {
                    $this->user_model->set_session($data['username'],$data['password']);
                    print json_encode(array("status"=>"ok"));
                }
                else {
                    print json_encode(array("status"=>"not", "message"=>"Can't register with this username"));
                }
            }
        } catch (Exception $e) {
                echo $e->getMEssage();
        }
    }
 
}