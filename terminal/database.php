<?php
class Database {
    private $db, $table, $password;
    function __construct() 
    {
        $this->db = array(
            'host'=>'mysql.hostinger.com.ua',
            'user'=>'u554731409_jesym',
            'password'=>'qqwwqq',
            'name'=>$table,
            'port'=>'80',
            'socket'=>''
        );
    }
    
    function connect()
    {
        $this->db = mysqli_connect("mysql.hostinger.com.ua","u554731409_jesym","qqwwqq","u554731409_luzeh");
    }
    
    function query($q)
    {
        $ret = "[";
        if($result= $this->db->query($q))
        {
            while ($str = $result->fetch_object()) {
                $ret = $ret.json_encode($str).",";
            }
            $ret.="{}]";
            echo $ret;
            return $this->db->use_result();
        }
        else return false;
    }
    
    function insert($q)
    {
        if($result= $this->db->query($q))
        {
            return true;
        }
        else return false;
    }
    
    function singleRow($q)
    {
        $result= $this->db->query($q);
        
        if($result)
        {
            $arr = $result->fetch_assoc();
            return $arr;
        }
        else return false;
    }
    
    function find($q)
    {
        $result= $this->db->query($q);
        if($result->num_rows>0)
        {
            return true;
        }
        else return false;
    }
    
    function close()
    {
        $this->db->close();
    }
}

?>