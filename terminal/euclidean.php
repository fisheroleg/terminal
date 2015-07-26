<?php


$key = trim($_POST['action']);
$first = $_POST['first'];
$second = $_POST['second'];

try{
    switch($key)
    {
        case 'gcf':
            gcf($first,$second);
            break;
        case 'hcf':
            hcf($first,$second);
            break;
        default:
            die("Command not recognized");
    }
}catch($e)
{
    die("Numbers not recognized");
}
function hcf($great,$small)
{
    if($great<$small) list($great,$small) = array($small,$great);

    $mod = $great % $small;
    if($mod == 0)
    {
        echo $small;
    }
    else
    {
        $nod = hcf($small,$mod);
    }
}

function gcf($great,$small)
{
    if($great<$small) list($great,$small) = array($small,$great);

    $mod = $great % $small;
    if($mod == 0)
    {
        global $first;
        global $second;
        if($first<$second) list($first,$second) = array($second,$first);
        echo ($first/$small)*$second;
    }
    else
    {
        $nod = gcf($small,$mod);
    }
}
?>