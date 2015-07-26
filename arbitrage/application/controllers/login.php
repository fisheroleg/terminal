<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type: text/html; charset=utf-8");
//session_start();
class Login extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('user_model','user',TRUE);
		$this->load->model('stat_model');
		$this->load->library("session");
		$this->isLogged = $this->user->check_logged();
	}
	private function blocsBefore($ajax=false)
    {
        if (!$ajax) {
            if ($this->isLogged>0) {
                $this->data['profile'] = $this->session->userdata("profile");
                $this->load->view('site/site_header', $this->data['profile']);
            } else {
                $this->load->view('site/site_header');
            }
        }
    }

    private function blocksAfter($ajax=false)
    {
        if ($this->isLogged>0) {
            $this->load->helper('form');
        }
        if (!$ajax) {
            $this->load->view('site/site_footer');
        }
    }

	
	function index()
	{
		//This method will have the credentials validation
		$this->load->library('form_validation');
	      
		$this->form_validation->set_rules('mail', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
	      
		if($this->form_validation->run() == false)
		{
			$this->load->view('login_view');
		}
		else
		{
			redirect('/', 'refresh');
		}
	}
	
	function logout()
	{
		try {
		$this->session->sess_destroy();
		redirect('/', 'refresh');
		} catch (Exception $e) {
			echo $e->getMEssage();
		}
	}
	

    function register($ajax = false)
    {
	$ajax = filter_var($ajax, FILTER_VALIDATE_BOOLEAN);
        $data = $this->input->post(NULL);
        if (!isset($data['role']))
            $data['role'] = 0;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('firstname', 'Name', 'trim|required|xss_clean|callback_alpha_dash_space');
        $this->form_validation->set_rules('surname', 'Surname', 'trim|required|xss_clean|callback_alpha_dash_space');
        $this->form_validation->set_rules('mail', 'Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[4]');
        $this->form_validation->set_rules('password2', 'Repeat password', 'required|matches[password]|trim|xss_clean|min_length[4]');

       if ($this->form_validation->run() == FALSE) {
            print json_encode(validation_errors("<li>","</li>"));
        } else {
        	$mail = trim($this->input->post('mail'));
            $email_ex = $this->user->email_exists($mail);
            if($email_ex)
            {
            	 print json_encode("<li>User with this email already exists. </li>");
            }
            else{
		
		$data['role'] = 1;
		if($ajax==true) $data['role'] = $this->input->post('role');
            $id = $this->user->register($data);
		//die(var_dump($ajax));
            if ($id) {
                if ($ajax===true) {
			try{
				$this->data['users']=$this->user->getProfileInfo($id);
				$this->data['async']=true;
				die( json_encode(array('data'=>$this->load->view('admin/lists/users_list',$this->data,true),
						       'status'=>true)));
			}catch(Exception $e)
			{
				echo json_encode(array('data'=>'Error registring user',
						       'status'=>false));
			}
		}
		// Login user if registration was successful
                else {
                // Login user if registration was successful
                if ($this->check_database($data['password'], $data['mail']))
                    print json_encode("");
		}
            }
            else {
                print json_encode("<li>Cannot register new user. </li>");
            }
	    }
//            if($id)
//            {
//		if ($ajax==true)
//					{
//					$this->data['users']=$this->user->getProfileInfo($id);
//					$this->data['async']=true;
//					$this->load->view('admin/lists/users_list',$this->data);
//					}
//				}
//}
            //Go to private area
//            if ($ajax != 'true') {
//                $this->check_database($data['password'], $data['mail']);
//                redirect('/', 'refresh');
//            } else {
//                $this->data['users'] = $this->user->getProfileInfo($id);
//                $this->data['async'] = true;
//                $this->load->view('admin/lists/users_list', $this->data);
//            }
            //
        }

       
}
            //Go to private area
            //if ($ajax != 'true') {
            //    $this->check_database($data['password'], $data['mail']);
          //      redirect('/', 'refresh');
        //    } else {
      //          $this->data['users'] = $this->user->getProfileInfo($id);
    //            $this->data['async'] = true;
  //              $this->load->view('admin/lists/users_list', $this->data);
//            }
            //
       
 
	function alpha_dash_space($str)
{
	if(! preg_match("/^([-aA-zZ_ _-аА-яЯ_-])+$/i", $str))
	{
		$this->form_validation->set_message('alpha_dash_space', 'Name and Surname fields must contain only alphabetical charecters, spaces and -');
		return false;
	} else
	return true;
} 
	function signin()
	{
		try {
		$data = $this->input->post(NULL);
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('mail', 'Email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

		if($this->form_validation->run() == false)
		{
            print json_encode(validation_errors("<li>","</li>"));
			//die(validation_errors());//$this->load->view('registration_view');
		}
		else
		{
			if ($this->check_database($data['password'],$data['mail'])) {
                print json_encode("");
            }
            else {
                print json_encode("<li>Incorrect user or password</li>");
            }
		}
		} catch (Exception $e) {
			echo $e->getMEssage();
		}
	}



    function check_database($password,$mail)
	{
		$this->stat_model->insertLog("login","login");
		
		try {
		//Field validation succeeded.&nbsp; Validate against database
		if(!$mail) $mail = $this->input->post('mail');
	      
		//query the database
		$result = $this->user->login($mail, $password);

		if($result)
		{
			$sess_array = array();
			foreach($result as $row)
			{
				$sess_array = array(
					'id' => $row->idProfile,
					'mail' => $row->mail
				);
				$this->session->set_userdata('logged_in', $sess_array);
				
				$data = $this->user->getProfileInfo($row->idProfile);
				$data = get_object_vars($data[0]);
				
				$this->session->set_userdata('profile', $data);
			}
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('check_database', 'Invalid mail or password');
			return false;
		}
	    } catch (Exception $e) {
        echo $e->getMEssage();
        }
	}
	function reset_password()
	{
		try {
		$this->load->model('user_model');
		if(!isset($_POST['mail'])||empty($_POST['mail']))
		{
		$this->isLogged = $this->user_model->check_logged();
		
			$ajax = $this->input->post("ajax");
       		$this->blocsBefore($ajax);
       		$this->blocksAfter($ajax);
		 	
		//$data = $this->input->post(NULL);
		 $this->load->view('login/view_reset_pass');
		}
		elseif(isset($_POST['mail'])&&!empty($_POST['mail']))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('mail','Email','trim|required|valid_email|xss_clean');
			if($this->form_validation->run()== FALSE)
			{
				print json_encode(validation_errors("<li>","</li>"));
			}
			else{
				$mail = trim($this->input->post('mail'));
				$result = $this->user_model->email_exists($mail);
				if($result){
					$this->send_reset_password_email($mail,$result);
					//$this->load->view('site_header');
				//	$this->load->view('login/view_reset_pass_sent',array('mail'=>$mail));
					print json_encode(array("<li>Листа відправлено на ".$mail."</li>"));
					//$this->load->view('login/view_reset_pass_sent',array('mail'=>$mail));
				}else{
					//$this->load->view('site_header');
					print json_encode("<li>Користувача не знайдено. </li>");
				}
				
			}
		}

			} catch (Exception $e) {
			echo $e->getMEssage();
		}

		}
	function reset_pass_form($mail,$mail_code)
	{
		$this->load->model('user_model');
		$this->isLogged = $this->user_model->check_logged();
		
			$ajax = $this->input->post("ajax");
       		$this->blocsBefore($ajax);
		 	$this->blocksAfter($ajax);
		if(isset($mail,$mail_code)){
			$mail = trim($mail);
			$mail_hash = sha1($mail.$mail_code);
			$verified = $this->user_model->verify_reset_password_code($mail,$mail_code);
			if($verified)
			{
				//$this->load->view('site_header');
				$this->load->view('login/view_update_pass',array('mail_hash'=>$mail_hash,'mail_code'=>$mail_code,'mail'=>$mail));

			}
			else{
				//$this->load->view('site_header');
				$this->load->view('login/view_reset_pass',array('error'=>'There was a problem with your link.','mail'=>$mail));
			}
		}
	}
	
	function updated()
	{
		$this->blocsBefore();
		$this->load->view('login/view_update_pass_success');
		$this->blocksAfter();
	}
	
	function rejected()
	{
		$this->blocsBefore();
		$this->load->view('login/view_update_pass',array('error'=>'Problem updating your password.'));
		$this->blocksAfter();
		
	}
	
	function update_password()
	{
		try {
	$this->load->model('user_model');
		$this->isLogged = $this->user_model->check_logged();
		
			$ajax = $this->input->post("ajax");
       		$this->blocsBefore($ajax);
		 	$this->blocksAfter($ajax);
	
		if(!isset($_POST['mail'],$_POST['mail_hash'])||$_POST['mail_hash'] !== sha1($_POST['mail'].$_POST['mail_code']))
		{
			die('Error updating your password.');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('mail_hash','Email Hash','trim|required');
		$this->form_validation->set_rules('mail','Email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password_conf', 'Repeat password', 'matches[password]|trim|xss_clean');
        if($this->form_validation->run()== FALSE)
			{
				print json_encode(validation_errors("<li>","</li>"));
			}
			else{
				$result=$this->user_model->update_password();
				
				if($result)
				{
					redirect('/login/updated');
					//die(var_dump($result));
					//$this->load->view('site_header');
					//$this->load->view('login/view_update_pass_success');
					//die();
				}else{
					redirect('/login/rejected');
					//$this->load->view('site_header');
					//$this->load->view('login/view_update_pass',array('error'=>'Problem updating your password.'));
					//die();
				}
			}
	        } catch (Exception $e) {
        echo $e->getMEssage();
        }
	}

	function send_reset_password_email($mail,$firstName)
	{
	try {	
    
$config['charset'] = 'utf-8';
		$this->load->library('email');
		$this->email->initialize($config);
		$mail_code=md5($this->config->item('salt').$firstName);
		$this->email->set_mailtype('html');
		$this->email->from('no-reply@arbitrage.com','Admin');
		$this->email->to($mail);
		$this->email->subject('Please reset your password');
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head><body>';
		$message .='<p>Dear '.$firstName. ',</p>';
		$message.='<p>We want to help you reset your password! Please <strong><a href="'. base_url().'/login/reset_pass_form/'.$mail.'/'.$mail_code.'">click here </a></strong>to reset your password.</p>';
		$message.='<p>Thank you!</p>';
		$message.='</body></html>';
		$this->email->message($message);
		$this->email->send();

		} catch (Exception $e) {
        echo $e->getMEssage();
        }
	}



}
