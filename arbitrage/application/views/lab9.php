<html>
    <head>
        <link href="/static/admin/style/bootstrap.min.css" rel="stylesheet">
        <link href="/static/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/static/fonts/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="/static/upload/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/static/jquery/jquery.min.js"></script>
        <style>
            body{
                padding: 40px;
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
        </style>
    </head>
    <body>
        <form id="f">
            <table id="main">
                <tr>
                    <td><input type="number" required></td>
                </tr>
            </table>
            <br>
            <button class="btn btn-success form-control 1" id="addCol">Col</button>
            <button class="btn btn-success form-control 1" id="addRow">Row</button>
            <button type="submit" class="btn btn-warning form-control 1" id="go">Go</button>
        </form>
        
        <div id="result">
            
        </div>
    </body>
    <script>
        
        $("#addCol").on("click",function(e){
            e.preventDefault()
            $("table#main tr").append('<td><input type="number" required></td>');
        })
        
        $("#addRow").on("click",function(e){
            e.preventDefault()
            $("table#main").append('<tr>'+$("table#main tr:last").html()+'</tr>');
        })
        
        var jObject;
        
        $("form").submit(function(e){
            $(this).addClass("spinner-active");
            e.preventDefault()
            
            var elementTable = document.getElementById('main');
            jObject = [];
            
            var m = elementTable.rows.length;
            var n = elementTable.rows[0].cells.length;
            for (var i = 0; i < n; i++)
            {
                jObject[i] = [];
                
                for (var j = 0; j < m; j++)
                {
                    jObject[i][j] = parseFloat($(document.getElementById('main').rows[j].cells[i]).find('input').val());
                }
            }
    
            $.post("/dashboard/go",{data: JSON.stringify(jObject)},function(data){
                console.log(data);
                $("#result").html(data);
            });
            
            return false;
        })
    </script>
</html>