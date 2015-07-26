var changedMarkers=[], newMarkers=[], flightPlanCoordinates=[], flightPath, resultsLines=[];



function newMarker(name)
{
    var lat = map.getCenter().A;
    var lng = map.getCenter().F;
    var newId = markers.length;
    addMarker(newId, lat, lng, name, newImage);
    newMarkers[newId] = {'id':newId, 'latMarket':lat, 'lngMarket':lng, 'nameMarket':name};
    $("#saveAllButton").removeClass("blue");
    $("#saveAllButton").addClass("red");
}
var test;
function moveMarker(map,curMarker)
{
    console.log(curMarker.getPosition())
    $.post("http://maps.googleapis.com/maps/api/geocode/json?latlng="+curMarker.getPosition().A+","+curMarker.getPosition().F+"&sensor=false&language=uk",{},function(data){
        //console.log(data.results.formatted_address.split(', ')[1])
        if (data.status!='ZERO_RESULTS') {
            var addr = data.results[0].formatted_address.split(", ");
            var place = addr[addr.length-2]
        }else{
            var place = "²íø³"
        }
        if(!curMarker.isNew)
        {
            curMarker.setIcon(modImage);
            changedMarkers[curMarker.id] = {'idMarket':curMarker.id, 'nameCity': place, 'latMarket':curMarker.getPosition().A, 'lngMarket':curMarker.getPosition().F};
        }
        else{
            newMarkers[curMarker.id].latMarket = curMarker.getPosition().A;
            newMarkers[curMarker.id].lngMarket = curMarker.getPosition().F;
        }
        
        $("#saveAllButton").removeClass("blue");
        $("#saveAllButton").addClass("red");
        $("#unsavedMessage").show()
    })
    
}

function removeMarker(id) {
    $.post("/map/removeMarker/"+id,{})
}

function updateGeo(marker)
{
    var curLatLng = marker.getPosition()
    var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': curLatLng}, function(results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
	  console.log(results)
	} else {
	  alert('Geocoder failed due to: ' + status);
	}
    })
}

function saveMap() {
    console.log("save")
    $.post("/map/savePlaces",{'markers': JSON.stringify(changedMarkers), 'new_markers': JSON.stringify(newMarkers)},function(data){
        data = JSON.parse(data)
        newMarkers.forEach(function(el){
            console.log(el.id)
            el.id = data[el.id]
            console.log(el.id)
        });
        $("#saveAllButton").removeClass("red");
        $("#saveAllButton").addClass("blue");
        markers.forEach(function(marker){
            marker.setIcon(defImage);
            marker.isNew = false;
        });
        newMarkers=[];
        changedMarkers=[];
        $("#errorMessage").show()
        $("#unsavedMessage").hide()
    });
}

function getPlaces() {
    $.post("/map/getPlaces",{},function(data){
        data = JSON.parse(data)
        data.forEach(function(el){
            addMarker(el.id, el.lat, el.lng, el.name)
        });
    });
    
    $("#saveAllButton").click(saveMap);
    $("#newMarkerButton").click(function(){newMarker($("#markerName").val()); })
}

function getResult(start, depth, c_dist) {
    $.post("/dashboard/testResult/",{start:start, depth: depth, c: c_dist, disabledProducts: serializeProducts(), disabledMarkets: serializeMarkets()},function(data){
        data = JSON.parse(data)
        console.log(data)
        
        $("#listChains > .panel-body").html(data.chain)
        
        try{
            if (typeof flightPath !== "undefined") {
                flightPath.setMap(null);
            }
            flightPlanCoordinates = []
            var way = data.way
            way.forEach(function(el, index){
                var point = markers[el].getPosition()
                flightPlanCoordinates.push(new google.maps.LatLng(point.A, point.F))
            });
               
            flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                strokeColor: '#FF9a00',
                strokeOpacity: 1,
                strokeWeight: 3
            });
            
            flightPath.setMap(map);
              
            //Add results lines
            resultsLines.forEach(function(el){el.setMap(null)});
            
            var lineSymbol = {
                path: 'M 0,-1 0,1',
                strokeOpacity: 1,
                scale: 3
            };

            for (var key in data.operation)
            {
                var a = markers[key].getPosition()
                data.operation[key].forEach(function(dest){
                    var b = markers[dest.id].getPosition()
                    
                    //console.log(flightPlanCoordinates)
                    
                    var path = new google.maps.Polyline({
                        path: [new google.maps.LatLng(a.A, a.F),new google.maps.LatLng(b.A, b.F)],
                        strokeColor: 'darkgreen',
                        icons: [{
                            icon: lineSymbol,
                            offset: '0',
                            repeat: '20px'
                        }],
                        strokeOpacity: 0,
                        strokeWeight: 3
                    });
                    
                    path.setMap(map);
                    resultsLines.push(path)
                })
            }
        }catch(e){console.log(e)}
          
    });
}

$(document).ready(function(){
    $("#getResultButton").click(function(){
        var depth = $("#parameterDepth").html();
        var start = $("#startPoint").val();
        var c_dist = $("#factorDistance").html();
        
        $(this).attr('disabled','disabled');
        $('#getResultSpinner').slideDown(500).addClass("spin-active add-item")
    
        try{
            getResult(start,depth,c_dist)
        }catch(e)
        {
            //console.error(e)
        }
        setTimeout(function(){
            $("#getResultButton").removeAttr('disabled','disabled');
            $('#getResultSpinner').slideUp(500).removeClass("spin-active add-item")
            $('#main').addClass("spin-active")
            setTimeout(function(){
                $('#main').removeClass("spin-active")
            },1000)
            $('html,body').animate({scrollTop: 52},1500,'swing',function(){
                $("#results").collapse("show")
                $("#showChain").click()
            })
        },2000)
    })
});

function serializeProducts(){
    var disabledItems = []
    $(".item-product:not(.active)").each(function(el){
        disabledItems.push(Number($( this ).find('input').val()));
    });
    return JSON.stringify(disabledItems)
}

function serializeMarkets(){
    var disabledItems = []
    $(".item-market:not(.active)").each(function(el){
        disabledItems.push(Number($( this ).find('input').val()));
    });
    return JSON.stringify(disabledItems)
}
