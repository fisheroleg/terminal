<?php

Class User_model extends CI_Model
{
    /**
     * ���� �����������
     * @param var $mail ����� �����������
     * @param var $password ������ �����������
     * @return true  None
     */
    function login($username, $password)
    {
        $query = $this->db->query("SELECT * FROM users
                                  WHERE login_user='" . $username . "' AND password_user=MD5('" . $password . "')
                                      LIMIT 1");

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    /**
     * �������� �������������� �����������
     * @return var  true, ���� �������������
     */
    function check_logged()
    {
        $data = $this->session->userdata("user");
        return $data;
    }
    
    function set_session($username, $email)
    {
        $this->session->set_userdata(array("user"=>array("logged"=>true, "username"=>$username, "email"=>$email)));
    }
    
    function unset_session()
    {
        $this->session->unset_userdata("user");
    }

    /**
     * ������� ���������� � �������
     * @param var $id ID �����������
     * @return var  ��� �������
     */
    function getProfileInfo($id)
    {
        $query = $this->db->query("SELECT * FROM profile
                                      LEFT JOIN profile_details ON profile.idProfile = profile_details.profile_idProfile
                                      WHERE idProfile='" . $id . "'
                                      LIMIT 1");

        if ($query->num_rows() == 1) {
            return $query->result();//toDataArray($query->result());
        } else {
            return false;
        }
    }

    /**
     * �������� ������ �����������
     * @param var $data ��� �����������
     * @return true  None
     */
    function signup($data)
    {
        $sql = "INSERT INTO users (login_user, email_user, password_user)
                VALUES ('" . $data['username'] . "' , '" . $data['email'] . "', MD5('" . $data['password'] . "') )";

        $this->db->query($sql);
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
            return false;
       

    }
    function email_exists($mail){
        $sql="SELECT firstName,mail FROM profile WHERE mail='{$mail}' LIMIT 1";
        $result = $this->db->query($sql);
        $row = $result->row();

        return($result->num_rows()===1 && $row->mail) ? $row->firstName :false;
    }
    function verify_reset_password_code($mail,$code)
    {
         $sql="SELECT firstName,mail FROM profile WHERE mail='{$mail}' LIMIT 1";
        $result = $this->db->query($sql);
        $row = $result->row();

        if($result->num_rows()===1)
        {
            return($code==md5($this->config->item('salt').$row->firstName)) ? true:false;
        } else{
            return false;
        } 
    }
    function update_password(){
        $mail=$this->input->post('mail');
        $password = MD5($this->config->item('salt').$this->input->post('password'));
          $sql="UPDATE profile SET password = '{$password}' WHERE mail='{$mail}' LIMIT 1";
       $this->db->query($sql);

        if($this->db->affected_rows()==1)
        {
            return true;
        } else{
            return false;
        } 
    }
    }


