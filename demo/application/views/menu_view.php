<html>
    <head>
        <link href="/static/style/bootstrap.min.css" rel="stylesheet">
        <link href="/static/style/main.css" rel="stylesheet">
        <link href="/static/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/static/fonts/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="/static/upload/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/static/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/static/script/nav.js"></script>
        <style type="text/css">
            body{
                padding: 40px;
                display: table;
                position: absolute;
                height: 100%;
                width: 100%;
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
            #result td{
                width:50px;
                height:50px;
                border: 1px solid black;
                text-align: center;
            }
            
            @keyframes menu-appear {
                0%   {opacity: 0;transform: perspective(50em) rotateY(30deg);}
                100%  {opacity: 1;transform: perspective(50em) rotateY(0deg);}
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
                background: #2196F3;
                padding: 50px;
                margin-top: -10%;
                width: 50%;
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
                position: relative;
                left: 0vw;
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
            
            @media (min-width: 768px) {
                .col-sm-4
                {
                    width: 30%;
                    padding: 0;
                }
            }
            
            @media (max-width: 768px) {
                .col-sm-1.splitter
                {
                    display: none;
                }
            }
            
            .block
            {
                height: 25vmin;
                margin: 2vmin;
                padding: 0;
                z-index: 1;
                overflow: hidden;
                position: relative;
                animation-name: menu-appear;
                animation-duration: 1s;
                -webkit-transition: transform 0.2s ease;
                -moz-transition: transform 0.2s ease;
            }
            
            .block-content
            {
                width: 100%;
                height: 100%;
                background-repeat: no-repeat;
                background-position: center;
                display: table;
            }
            
            .menu-task-list
            {
                background: rgb(0, 169, 236);
            }
            
            .menu-calendar
            {
                background: rgb(41, 199, 154);
            }
            
            .menu-matrix
            {
                background: rgb(242, 199, 92);
            }
            
            .menu-notifications
            {
                background-color: brown;
            }
            
            .menu-profile
            {
                background-color: #81D4FA;
            }
            
            .menu-settings
            {
                background-color: #8E24AA;
            }
            
            .small-box-footer
            {
                position: relative;
                text-align: center;
                padding: 3px 0;
                color: #fff;
                color: rgba(255,255,255,0.8);
                display: table-row;
                height: 4vmin;
                font-size: 3vmin;
                z-index: 10;
                background: rgba(0,0,0,0.1);
                text-decoration: none;
            }
            
            .small-box-icon
            {
                color:white;
                display: table-row;
                height: auto;
                font-size: 21vmin;
                text-align: center;
            }
            
            .col-sm-1.splitter
            {
                width: 4vmin;
            }
            
            .overlay
            {
                z-index: 2;
                position: absolute;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.3);
                opacity:0;
                -webkit-transition: opacity .25s ease;
                -moz-transition: opacity .25s ease;
            }
            
            .overlay:hover
            {
                opacity:1;
                -webkit-transition: opacity .25s ease;
                -moz-transition: opacity .25s ease;
            }
            
            .block:active
            {
                transform: perspective(50em) rotateY(15deg);
            }

            .header
            {
                height: 0;
                display: table-row;
                height: 9vh;
            }
            
            .header > div
            {
                font-size: 20px;
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                color: lightslategrey;
            }
            
            .header .user
            {
                margin: 0 0 0 4vw;
            }
            
            .header .link
            {
                margin: 0 4vw 0 4vw;
            }
            
            .header .link:hover
            {
                color: lightskyblue;
                
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="pull-right"><span class="user">Hello, <?=$username?>! </span><i class="fa fa-sign-out link" href="/main/signout"></i></div>
        </div>
        
        <div class="middle">
            <div class="menu">
                <div class="row">
                    <div class="col-sm-4 col-xs-6">
                        <div class="block line1">
                            <div class="overlay"></div>
                            <div class="block-content menu-task-list">
                                <a href="#" class="small-box-footer">Task list <i class="fa fa-arrow-circle-right"></i></a>
                                <i class="ion ion-ios-list-outline small-box-icon"></i>
                            </div>
                        </div>
                        
                        <div class="block line1">
                            <div class="overlay"></div>
                            <div class="block-content menu-matrix">
                                <a href="#" class="small-box-footer">Matrix <i class="fa fa-arrow-circle-right"></i></a>
                                <i class="ion ion-ios-grid-view-outline small-box-icon"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-4 col-xs-6">
                        <div class="block line2">
                            <div class="overlay"></div>
                            <div class="block-content menu-calendar">
                                <a href="#" class="small-box-footer">Calendar <i class="fa fa-arrow-circle-right"></i></a>
                                <i class="ion ion-calendar small-box-icon"></i>
                            </div>
                        </div>
                        
                        <div class="block line2">
                            <div class="overlay"></div>
                            <div class="block-content menu-notifications">
                                <a href="#" class="small-box-footer">Notifications <i class="fa fa-arrow-circle-right"></i></a>
                                <i class="ion ion-ios-alarm-outline small-box-icon"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="splitter col-sm-1 block col-xs-0"></div>
                    
                    <div class="col-sm-4 col-xs-12">
                        <div class="block line3">
                            <div class="overlay"></div>
                            <div class="block-content menu-profile">
                                <a href="#" class="small-box-footer">Profile <i class="fa fa-arrow-circle-right"></i></a>
                                <i class="ion ion-person small-box-icon"></i>
                            </div>
                        </div>
                
                        <div class="block line3">
                            <div class="overlay"></div>
                            <div class="block-content menu-settings">
                                <a href="#" class="small-box-footer">Settings <i class="fa fa-arrow-circle-right"></i></a>
                                <i class="ion ion-gear-a small-box-icon"></i>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                </div>
            </div>
        </div>
    </body>
</html>