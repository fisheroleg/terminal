<html>
    <head>
        <link href="/static/style/bootstrap.min.css" rel="stylesheet">
        <link href="/static/style/main.css" rel="stylesheet">
        <link href="/static/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/static/fonts/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="/static/upload/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/static/jquery/jquery.min.js"></script>
        <style>
            body{
                padding: 40px;
                display: table;
                position: absolute;
                height: 100%;
                width: 100%;
            }
            
            span{
                color:lightgreen;
            }
            
            .btn
            {
                width: 50px;
                height: 50px;
            }
            input{
                width:50px;
                height:50px;
            }
            
            .input-group-addon
            {
                min-width:40px;
            }
            
            #result td{
                width:50px;
                height:50px;
                border: 1px solid black;
                text-align: center;
            }
            
            @keyframes example {
                0%   {transform: perspective(50em) rotateY(90deg);}
                100%  {transform: perspective(50em) rotateY(0deg);}
            }
            
            @keyframes hide {
                0%   {transform: perspective(50em) rotateY(0deg);}
                3%  {transform: perspective(50em) rotateY(90deg);}
                100%  {transform: perspective(50em) rotateY(90deg);}
            }
            
            @keyframes fail {
                0%   {background: #ffffff;}
                50%  {background: #F28D21;}
                100%   {background: #ffffff;}
            }

            #container
            {
                margin: auto;
                background: #1B5E20;
                padding: 50px 50px 25px 50px;
                margin-top: -10%;
                width: 50%;
                animation-name: example;
                animation-duration: 0.7s;
            }
            
            .key-fail
            {
                animation-name: fail!important;
                animation-duration: 0.3s!important;
            }
            
            .container-hide
            {
                animation-name: hide!important;
                animation-duration: 10s!important;
            }
            
            #f input
            {
                border: 0;
                -webkit-box-shadow: none;
                box-shadow: none;
            }
            
            .input-group-addon
            {
                border: 0;
                -webkit-box-shadow: none;
                box-shadow: none;
                background: #eee!important;
            }
            
            .middle
            {
                vertical-align: middle;
                display: table-cell;
                margin: 0;
                width: 50%;
            }
            
            .form-control-feedback {
                top: 12px;
            }
            
            #submitForm:hover
            {
                color: green;
            }
            
            #messageBox
            {
                background-color: #FFC107;
                margin: auto;
                width: 50%;
                padding: 7px;
                color: lightyellow;
                text-align: center;
                opacity: 0;
                -webkit-transition: opacity .5s ease;
            }
            
            #messageBox.visible
            {
                opacity: 1;
            }
        </style>
    </head>
    <body>
        <div class="middle">
            <div id="container">
                <form id="s" class="current">
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" id="username" aria-describedby="inputGroupSuccess1Status">
                        </div>
                        
                    </div>
                    
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input type="text" class="form-control" id="email" aria-describedby="inputGroupSuccess1Status">
                        </div>
                        
                    </div>
                    
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input class="form-control" id="password" type="password" aria-describedby="inputGroupSuccess1Status">
                        </div>
                        
                        <span id="submitForm" class="fa fa-chevron-right form-control-feedback" aria-hidden="true"></span>
                        <span class="sr-only">(success)</span>
                        
                    </div>
                </form>
                
                <span class="pull-right link" href="/">Sign in</span>
                
                <br>
            </div>
            
            <div id="messageBox"> - </div>
            
            <div class="next">
                    
            </div>
        </div>
    </body>
    <script>
        
       
    </script>
    
    <script src="/static/script/nav.js"></script>
</html>