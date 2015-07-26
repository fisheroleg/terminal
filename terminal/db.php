<?php
require("database.php");
require("login.php");

session_start();

$key = trim($_POST['action']);
$obj = $_POST['object'];
$val = $_POST['array'];

$db = new Database();
$auth = new Authorization();
$db->connect();

switch($key)
{
    case 'login':
        if($auth->login($db,$obj,$val))
        {
            echo "Success";
        }
        else
        {
            echo "User not found";
        }
        break;
    case 'logout':
        $auth->logout();
        echo "Success";
        break;
    case 'select':
        if($auth->getRole())
        {
            
            $sort = $val!=""?"ORDER BY ".$val:"";
            echo $db->query("SELECT * FROM catalog ".$sort);
        }
        else
        {
            echo "Forbidden";
        }
        break;
    case 'getusers':
        if($auth->getRole()==2)
        {
            echo $db->query("SELECT id,username FROM users");
        }
        else
        {
            echo "Forbidden";
        }
        break;
    case 'edit':
        $ret = '[{"array":[';
        foreach(explode(",",$val) as $el)
        {
            if($auth->hasRight($db,"edit",trim($el)))
            {
                $ret .= "\"";
                $ret .= trim($el);
                $ret .= "\",";
            }
        }
        $ret = trim($ret,",");
        $ret .= "]}]";
        echo $ret;
        break;
    case 'save':
        $edited = json_decode($val);
        foreach($edited->data as $index=>$val)
        {
            foreach($val as $i=>$el)
            {
                if($auth->hasRight($db,"edit",trim($i)))
                {
                    $ret .= "";
                    $ret .= trim($i);
                    $ret .= "=\"";
                    $ret .= trim($el);
                    $ret .= "\",";
                }
            }
            $ret = trim($ret,",");
            if(!$db->insert("UPDATE catalog SET ".$ret." WHERE id=".$index)) die("Query error");
            $ret="";
        }
        echo "Success";
        break;
    case 'remove':
        if($auth->getRole()=="2")
        {
            if(!$db->insert("DELETE FROM catalog WHERE id=".$val)) die("Query error");
            echo "Success";
        }
        else echo "Forbidden";
        break;
    case 'grant':
        if($auth->getRole()=="2")
        {
            if(!$db->insert("INSERT INTO rights(uId,action,col) VALUES(\"".$obj."\",\"edit\",\"".$val."\")")) die("Query error");
            echo "Success";
        }
        else echo "Forbidden";
        break;
    case 'add':
        if($auth->getRole()=="2")
        {
            $q="";
            $c=1;
            $values = explode(",",$val);
            foreach($values as $el)
            {
                if($c!=1) $q.=",";
                if($c==2&&$obj=="user") $el=md5($el);
                if($c>3) $el = date("Y-m-d", strtotime($el));
                $q.="\"".trim($el)."\"";
                $c+=1;
            }
            if($obj=="row"){
                $table="catalog(name,price,action,fromDate,toDate)";
            }else if($obj=="user"){
                $table = "users(username,password,role)";
            }else{
                die("Command not recognized");
            }
            if($db->insert("INSERT INTO ".$table." VALUES(".$q.")"))
            {
                echo "Success";
            }
            else echo "Fail";
        }
        else
        {
            echo "Forbidden";
        }
        break;
    
    default:
        die("Command not recognized");
}

$db->close();
?>