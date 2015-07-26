$(document).ready(function(){
    $("#submitForm").on("click",function(){
        console.log("submit");
        $("#f").submit();
    })
    
    $("#f").on("submit",function(){
        if ($("#username").val().trim() == "" || $("#password").val().trim() == "") {
            return false;
        }
        
        $.post("/auth/login",{username: $("#username").val(), password: $("#password").val()},function(data){
            data = JSON.parse(data)
            if (data.status == "ok") {
                $("#container").addClass("container-hide")
                setTimeout(function(){$("body").html(""); window.location = "/main/menu";},300)
            }
            else{
                message(data.message);
            }
        })
        
        return false;
    })
    
    $("#submitForm").on("click",function(){
        console.log("submit");
        $("#s").submit();
    })
    
    $("#s").on("submit",function(){
        if ($("#username").val().trim() == "" || $("#password").val().trim() == "" || $("#email").val().trim() == "") {
            return false;
        }
        
        $.post("/auth/signup",{username: $("#username").val(), password: $("#password").val(), email: $("#email").val()},function(data){
            data = JSON.parse(data)
            if (data.status == "ok") {
                $("#container").addClass("container-hide")
                setTimeout(function(){window.location = "/main/menu";},300)
            }
            else{
                message(data.message);
            }
        })
        
        return false;
    })
    
    $(".overlay").on("click",function(){
        $(".middle").addClass("gone");
    })
    
    $(".link").on("click",function(){
        var link = $(this).attr("href");
        $("#container").addClass("container-hide")
        setTimeout(function(){window.location =  link;},300)
    })
    
    function message(text) {
        console.log(text)
        $("#messageBox").addClass("visible")
        $("#messageBox").html(text);
        setTimeout(function(){$("#messageBox").removeClass("visible")},3000)
    }
})