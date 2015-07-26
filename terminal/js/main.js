$(document).ready(function(){
    var nextNamespace = ""
    var prevCommand = ""
    var editableNum = 0;
    var SYS = {
        executeCommand: function()
        {
            var namespace = $("#console").val().toLowerCase().split(">")[0]
			if($("#console").val().toLowerCase().split(">")[1] == undefined) {$("#console").val(namespace + ">")}
			
			if(namespace != localStorage.getItem("namespace"))
			{
				localStorage.setItem("namespace",namespace)
				$("#visuals").html("");
			}
            nextNamespace = namespace+">";
            prevCommand = $("#console").val();
            switch (namespace) {
				case "help":
                    COM.help();
                    break;
                case "clear":
                    LOG.clear()
                    break;
                case "stack":
                    SYS.stack();
                    break;
                case "table":
                    SYS.table();
                    break;
                case "system":
                    SYS.table();
                    break;
                case "euclidean":
                    SYS.euclidean()
                    break;
                default:
                    LOG.addError("Namespace",namespace,"not found")
            }
        },
        
        stack: function()
        {
            var param = $("#console").val().split(">")[1].split(":")
            var command = param[0].toLowerCase().split(" ")
            switch(command[0])
            {
                case "create":
                    COM.create(command[1],param[1])
                    break;
                case "push":
                    COM.push(command[1], param[1])
                    break;
                case "pop":
                    COM.pop(command[1])
                    break;
                case "count":
                    COM.count(command[1])
                    break;
                case "comp":
                    COM.comp(command[1],param[1])
                    break;
                case "remove":
                    COM.remove(command[1])
                    break;
                case "merge":
                    COM.merge(command[1],command[2],param[1])
                    break;
                default:
                    LOG.addError("Command",command[0],"not found in Stack namespace")
            }
        },
        
        table: function()
        {
            var param = $("#console").val().split(">")[1].split(":")
            var command = param[0].toLowerCase().split(" ")
            switch(command[0])
            {
                case "select":
                    COM.table.select(param[1])
                    break;
                case "login":
                    COM.table.login(command[1],param[1])
                    break;
                case "logout":
                    COM.table.logout()
                    break;
                case "add":
                    COM.table.add(command[1],param[1])
                    break;
                case "edit":
                    COM.table.edit(param[1])
                    break;
                case "save":
                    COM.table.save()
                    break;
                case "getusers":
                    COM.table.getusers()
                    break;
                case "remove":
                    COM.table.remove(param[1])
                    break;
                case "grant":
                    COM.table.grant(command[1],param[1])
                    break;
                default:
                    LOG.addError("Command",command[0],"not found in Table namespace")
            }
        },
        
        euclidean: function()
        {
            var param = $("#console").val().split(">")[1].split(":")
            var command = param[0].toLowerCase().split(" ")
            switch(command[0])
            {
                case "gcf":
                    COM.euclidean.gcf(command[1],param[1])
                    break;
                case "hcf":
                    COM.euclidean.hcf(command[1],param[1])
                    break;
                default:
                    LOG.addError("Command",command[0],"not found in Table namespace")
            }
        }
    }
    
    var COM = {
        euclidean: {
            gcf: function(val1,val2)
            {
                $.post("euclidean.php", { first: val1, action: "gcf", second: val2 },
                    function(data)
                    {
                        LOG.addSuccess("GCF of",val1+":"+val2,data)
                    }
                )
            },
            hcf: function(val1,val2)
            {
                $.post("euclidean.php", { first: val1, action: "hcf", second: val2 },
                    function(data)
                    {
                        
                        LOG.addSuccess("HCF of",val1+":"+val2,data)
                    }
                )
            },
        },
            
        table: {
            select: function(order)
            {
                if (order==undefined) {
                    order=""
                }
                $.post("db.php", { object: "", action: "select", array: order },
                    function(data)
                    {
                        if (data=="Forbidden"||data=="Query error") {
                            LOG.addError("SELECT","catalog",data)
                            return;
                        }
                        data = JSON.parse(data)
                        LOG.addCommand("select * from","catalog","","success")
                        VIEW.table.addTable(data)
                    }
                )
            },
            
            login: function(name,password)
            {
                $.post("db.php", { object: name, action: "login", array: password },
                    function(data)
                    {
                        if(data=="Forbidden") LOG.addError("Login as",name,data)
                        else
                        if(data=="Success") LOG.addSuccess("Login as",name,data)
                    }
                )
            },
            
            logout: function()
            {
                $.post("db.php", { object: "", action: "logout", array: "" },
                    function(data)
                    {
                        if(data=="Forbidden") LOG.addError("Login as",name,data)
                        else
                        if(data=="Success") {
                            LOG.addSuccess("Logout","",data)
                            VIEW.clear();
                        }
                    }
                )
            },
            
            add: function(name,param)
            {
                if (name.trim()!="user"&&name.trim()!="row") {
                    LOG.addError("Command",name,"not recognized")
                }
                
                $.post("db.php", { object: name, action: "add", array: param },
                    function(data)
                    {
                        if(data=="Success") LOG.addCommand("Insert","catalog",param,data)
                        else if(data=="Forbidden") LOG.addError("Insert",name,data)
                    }
                )
            },
            
            edit: function(param)
            {
                $.post("db.php", { object: "", action: "edit", array: param },
                    function(data)
                    {
                        data = JSON.parse(data)
                        $.post("db.php", { object: "", action: "select", array: "" },
                            function(table)
                            {
                                table = JSON.parse(table)
                                VIEW.table.addTable(table)
                                VIEW.table.editTable(data)
                                $(".editable>textarea").change(function(){
                                    $(this).attr("upd","1");
                                })
                                editableNum=data[0].array.length
                            }
                        )
                    }
                )
            },
            
            save: function()
            {
                var request = '{"data":{'
                $("#visuals tr").each(function(){
                    var res = $(this).find("textarea[upd='1']")
                    if (res.length>0) {
                        request+="\""+res.parent().parent().find(".col-td-id").html()+"\":{"
                        res.each(function(){
                            request += "\"" + $(this).attr("class").split("-")[1] + "\":\"" + $(this).val()+"\","
                        });
                        request = request.substring(0, request.length - 1)
                        request += "},"
                    }
                });
                request = request.substring(0, request.length - 1)
                request += "}}"
                
                $.post("db.php", { object: "", action: "save", array: request },
                    function(data)
                    {
                        if (data=="Success") {
                            if(data=="Success") LOG.addSuccess("Save","catalog",data)
                            else LOG.addError("Insert",name,data)
                        }
                    }
                )
            },
            
            remove: function(id)
            {
                $.post("db.php", { object: "", action: "remove", array: id },
                    function(data)
                    {
                        if(data=="Success") LOG.addSuccess("Remove",id,data)
                        else LOG.addError("Remove",id,data)
                    }
                )
            },
            
            getusers: function()
            {
                $.post("db.php", { object: "", action: "getusers", array: "" },
                    function(data)
                    {
                        if(data!="Forbidden")
                        {
                            LOG.addSuccess("Select","users","Success")
                            VIEW.table.addTable(JSON.parse(data))
                        }
                        else LOG.addError("Select","users",data)
                    }
                )
            },
            
            grant: function(obj,val)
            {
                $.post("db.php", { object: obj, action: "grant", array: val },
                    function(data)
                    {
                        if(data!="success")
                        {
                            LOG.addSuccess("Grant",obj,"Success")
                        }
                        else LOG.addError("Grant",obj,data)
                    }
                )
            }
        },
        
        create: function(obj,param)
        {
            $.post("stack.php", { object: obj, action: "create", array: param },
                function(data)
                {
                    data = JSON.parse(data)
                    LOG.addCommand("create",obj,param,data[0])
                    VIEW.addStack(obj,data[1])
                }
            )
        },
        
        push: function(obj,param)
        {
            $.post("stack.php", { object: obj, action: "push", array: param },
                function(data)
                {
                    data = JSON.parse(data)
                    LOG.addCommand("push",obj,param,data[0])
                    VIEW.pushStack(obj,data[1])
                }
            )
        },
        
        pop: function(obj)
        {
            $.post("stack.php", { object: obj, action: "pop" },
                function(data)
                {
                    data = JSON.parse(data)
                    LOG.addCommand("pop",obj,"",data)
                    VIEW.popStack(obj)
                }
            )
        },
        
        count: function(obj)
        {
            $.post("stack.php", { object: obj, action: "count" },
                function(data)
                {
                    data = JSON.parse(data)
                    LOG.addCommand("count",obj,"",data)
                }
            )
        },
        
        comp: function(obj,param)
        {
            $.post("stack.php", { object: obj, action: "comp", array: param},
                function(data)
                {
                    LOG.addCommand("comp",obj+"\" to \""+param,"",JSON.parse(data))
                }
            )
        },
        
        merge: function(obj1,obj2,param)
        {
            var str=obj1+","+obj2
            $.post("stack.php", { object: String(str), action: "merge", array: param},
                function(data)
                {
                    LOG.addCommand("merge",obj1+"\" with \""+obj2+" in \""+param+"\"","",JSON.parse(data))
                    VIEW.clearStack(obj1)
                    VIEW.clearStack(obj2)
                    VIEW.addStack(param,JSON.parse(data)[1])
                }
            )
        },
        
        remove: function(obj)
        {
            $.post("stack.php", { object: obj, action: "remove"},
                function(data)
                {
                    LOG.addCommand("remove",obj,"",JSON.parse(data))
                    $("#visuals>."+obj).remove()
                }
            )
        },
        
        help: function()
        {
            $.post("help.php", {},
                function(data)
                {
                    LOG.addSuccess("Get","help","Success")
                    $("#visuals").html();
                    VIEW.addHelp(data)
                }
            )
        }
    }
    
    var LOG = {
        add: function(string, level)
        {
            
        },
        
        addCommand: function(com,obj,param,result)
        {
            var log = $("#log")
            log.append("<span class='message com'>"+com+" </span>")
            if(obj!="") log.append("<span class='message obj'>\""+obj+"\"</span>")
            log.append("<span class='message param'>{"+param+"}</span>")
            log.append("<span class='message result'> - "+result+"</span><br>")
        },
        
        addError: function(str,obj,message)
        {
            var log = $("#log")
            log.append("<span class='message com'>"+str+" </span>")
            log.append("<span class='message obj'>\""+obj+"\"</span>")
            log.append("<span class='message result error'> - "+message+"</span><br>")
        },
        
        addSuccess: function(str,obj,message)
        {
            var log = $("#log")
            log.append("<span class='message com'>"+str+" </span>")
            if(obj!="") log.append("<span class='message obj'>\""+obj+"\"</span>")
            log.append("<span class='message result'> - "+message+"</span><br>")
        },
        
        clear: function()
        {
            $("#log").html("")
        }
    }
    
    var VIEW = {
        table: {
            addTable: function(data)
            {
                var vis = $("#visuals")
                vis.html("");
                var str = '<table><tr class="request-title">'
                for (var entry in data[0]){
                    str+='<td class="item col-'+entry+'">'+entry+"</td>"
                }
                str+='</tr>'
                data.forEach(function(entry){
                    str+='<tr>'
                    for(var el in entry){
                        str+='<td class="item col-td-'+el+'">'+entry[el]+"</td>"
                    }
                    str+='</tr>'
                })
                str+='</table>'
                vis.append(str)
            },
            
            editTable: function(data)
            {
                data[0].array.forEach(function(el){
                    $(".col-td-"+el).each(function(){
                        $(this).addClass("editable");
                        var mem = $(this).html();
                        $(this).html("<textarea rows='1' class='edit-"+el+"'>"+mem+"</textarea>")
                    });
                });
                LOG.addCommand("Open fields","catalog",data[0].array,"Success")
            }
        },
        
        addStack: function(obj,data)
        {
            var vis = $("#visuals")
            vis.append('<div class="'+obj+'">')
            var stack = vis.find("."+obj)
            stack.append('<div class="name">'+obj+'</div>')
            data.forEach(function(entry){
                stack.append('<div class="item">'+entry+"</div>")
            })
            vis.append('</div>')
        },
        
        pushStack: function(obj,data)
        {
            var stack = $("#visuals").find("."+obj)
            stack.append('<div class="item">'+data+'</div>')
        },
        
        popStack: function(obj)
        {
            $("."+obj+">.item:last-child").remove();
        },
        
        clearStack: function(obj)
        {
            $("."+obj+">.item").remove();
        },
        
        addHelp: function(data)
        {
            var vis = $("#visuals")
            vis.append(data)
        },
        
        clear: function()
        {
            var vis = $("#visuals")
            vis.html("");
        }
    }
    
    $( "#console" ).keydown(function( event ) {
        if ( event.which == 13 ) {
            SYS.executeCommand()
            $("#console").val(nextNamespace)
            return false
        }else
        if ( event.which == 38 ) {
            $("#console").val(prevCommand)
            return false
        }
        return true;
    })
})
