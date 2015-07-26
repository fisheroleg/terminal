<?php

session_start();
    
class Stack
{
    private $a;
    private $top;
    
    function __construct($data=null) 
    {
        if($data==null) $this->emptyStack();
        elseif (is_array($data)) $this->predefinedStack($data);
        elseif (get_class($data)=="Stack") $this->cloneStack($data);
    } 
    
    function emptyStack() 
    { 
        $this->a=array();
        $this->top=-1;
    } 
    
    function predefinedStack($a) 
    {
        $this->a=$a;
        $this->top=count($a)-1;
    }
    
    function cloneStack($stack) 
    {
        $this->a=$stack->a;
        $this->top=$stack->top;
    }
    
    public function getNumber()
    {
        return count($this->a);
    }
    
    public function push($num)
    {
        array_push($this->a,$num);
    }
    
    public function pop()
    {
        return array_pop($this->a);
    }
    
    public function out()
    {
        return $this->a;
    }
    
    public function compare($stack)
    {
        return $this->a==$stack->a;
    }
    
    public function mergeAndClear($stack)
    {
        $res = new Stack(array_merge($this->a,$stack->a));
        $this->a=array();
        $stack->a=array();
        return $res;
    }
}

$key = trim($_POST['action']);
$stack = $_POST['object'];
$val = $_POST['array'];

switch($key)
{
    case 'create':
        createStack($val, $stack);
        break;
    case 'pop':
        popStack($stack);
        break;
    case 'push':
        pushStack($val, $stack);
        break;
    case 'out':
        outputStack($stack);
        break;
    case 'count':
        countStack($stack);
        break;
    case 'comp':
        compStack($stack,$val);
        break;
    case 'merge':
        mergeStack($stack,$val);
        break;
    case 'merge':
        mergeStack();
        break;
    case 'remove':
        removeStack($stack);
        break;
}

function createStack($val,$name)
{
    $stack = new Stack(explode(",",$val));
    $_SESSION[$name]=serialize($stack);
    echo json_encode(array("Stack created",$stack->out()));
}

function popStack($name)
{
    $stack = unserialize($_SESSION[$name]);
    $res = $stack->pop($val);
    echo json_encode(array("Element ".$res." popped"));
    $_SESSION[$name]=serialize($stack);
}

function pushStack($val,$name)
{
    $stack = unserialize($_SESSION[$name]);
    $stack->push($val);
    echo json_encode(array("Element pushed",$val));
    $_SESSION[$name]=serialize($stack);
}

function countStack($name)
{
    $stack = unserialize($_SESSION[$name]);
    $res = $stack->getNumber($val);
    echo json_encode(array("Founded ".$res." elements"));
    $_SESSION[$name]=serialize($stack);
}

function compStack($name1,$name2)
{
    $stack1 = unserialize($_SESSION[$name1]);
    $stack2 = unserialize($_SESSION[$name2]);
    $res = $stack1->compare($stack2);
    if($res) echo json_encode(array("Stacks are equivalent"));
    else echo json_encode(array("Stacks are different"));
}

function mergeStack($name,$newName)
{
    $name=explode(",",$name);
    $stack1 = unserialize($_SESSION[$name[0]]);
    $stack2 = unserialize($_SESSION[$name[1]]);
    $res = $stack1->mergeAndClear($stack2);
    $_SESSION[$name[0]]=serialize($stack1);
    $_SESSION[$name[1]]=serialize($stack2);
    $_SESSION[$newName]=serialize($res);
    echo json_encode(array("Stack created, previous stacks are empty",$res->out()));
}

function outputStack($stack)
{
    print(json_encode($stack->out()));
}

function removeStack($name)
{
    $_SESSION[$name]="";
    echo json_encode(array("Stack \"".$name."\" removed"));
}
?>