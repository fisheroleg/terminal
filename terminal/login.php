<?php
class Authorization
{
    function create($db,$login,$password,$role)
    {
        if($this->getRole()==4)
        {
            $db->query("INSERT INTO users(username, password, role) VALUES (".$login.",".$password.",".$role.")");
            return true;
        }
        else return false;
    }
    
    function login($db,$login,$password)
    {
        if($this->isLogin()) $this->logout();
	//die("SELECT * FROM users WHERE username='".$login."' AND password='".md5($password)."' LIMIT 1");

        $res = $db->singleRow("SELECT * FROM users WHERE username='".$login."' AND password='".md5($password)."' LIMIT 1");
        
	if($res)
        {
            $_SESSION['user']=$res['username'];
            $_SESSION['role']=$res['role'];
            $_SESSION['id']=$res['id'];
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function logout()
    {
        session_unset();
    }
    
    function isLogin()
    {
        if(isset($_SESSION['user']))
        {
            return $_SESSION['user'];
        }
        else
        {
            return false;
        }
    }
    
    function getRole()
    {
        if(isset($_SESSION['role']))
        {
            return $_SESSION['role'];
        }
        else
        {
            return false;
        }
    }
    
    function hasRight($db,$action,$col)
    {
	//die("SELECT * FROM rights WHERE uid='".$_SESSION['id']."' AND action='".$action."' AND col= '".$col."'");
        return $db->find("SELECT * FROM rights WHERE uid='".$_SESSION['id']."' AND action='".$action."' AND col= '".$col."'");
    }
}
?>