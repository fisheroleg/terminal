<?php
//require_once("stack.php");
session_unset();
?>
<html>
    <head>
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/main.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="visuals">
            <div id="visuals">
                
            </div>
        </div>
        
        <div class="log">
            <div id="log">
                <span class="system messages">Session started</span><br>
            </div>
        </div>
        
        <textarea id="console" placeholder="Type command here. Maybe 'help'?:)" autofocus></textarea>
    </body>
    
</html>