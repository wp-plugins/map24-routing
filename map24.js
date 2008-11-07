  function goMap24() {
    Map24.loadApi( ["core_api", "wrapper_api"] , map24ApiLoaded );
  }
  
  function map24ApiLoaded(){
    Map24.MapApplication.init( { NodeName: "map24_area" } ); 
  }
    
  var geocoder = null;
  var router = null;
  var routePoints = [];
  var routeID = null;
  
  function startRouting(){
    var startString = Map24.trim( $v('map24_start') );
    var destinationString = Map24.trim( $v('map24_end') );
    if( startString == "" ) { alert("Please insert a start point!"); return; }
    if( destinationString == "" ) { alert("Please insert a destination point!"); return; }
	  document.getElementById("map24_calc").disabled = true;
	  document.getElementById("map24_desc").innerHTML = "Calculate Route...";
    var geocoder = new Map24.GeocoderServiceStub();
    geocoder.geocode({ 
      SearchText: startString, 
      MaxNoOfAlternatives: 1, 
      CallbackFunction: setRouteEndPoint, 
      CallbackParameters: {position: "map24_start"}
    });
    geocoder.geocode({
      SearchText: destinationString, 
      MaxNoOfAlternatives: 1, 
      CallbackFunction: setRouteEndPoint,
      CallbackParameters: {position: "map24_end"}
    });
  }
  
  function setRouteEndPoint(locations, params){
    routePoints[ params.position ] = locations[0];
    if( typeof routePoints["map24_start"] != "undefined" && typeof routePoints["map24_end"] != "undefined") calculateRoute(); 
  }
  
  function calculateRoute() {
    router = new Map24.RoutingServiceStub();
    router.setDefaultDescriptionLanguage('en');
    router.calculateRoute({
      Start: routePoints["map24_start"],
      Destination: routePoints["map24_end"],
      CallbackFunction: displayRoute
    });
    routePoints = [];
  }
  
  function displayRoute( route ){
	  routeID = route.RouteID;
	
    var totalTime = ((route.TotalTime)/(60*60) ).toPrecision(3) 
    var totalLength = (route.TotalLength/1000) 
    var totalLengthMiles = (totalLength * 0.621371192).round(2);
    var res = "<b>Total Time:</b>"+totalTime+"h<br/>";
    res += "<b>Total Length:</b>"+totalLength+"km<br/>";
    
    res += "<ol>";
    for(var i = 0; i < route.Segments.length; i++){
      for(var j = 0; j < route.Segments[i].Descriptions.length; j++){ 
        res += "<li>"+route.Segments[i].Descriptions[j].Text.replace(/(\[|\[\/)[0-9A-Z_]+\]/g, '' )+"</li>";
      }
    }
    res += "</ol>";
    document.getElementById('map24_desc').innerHTML = res;
	  document.getElementById("map24_del").disabled = false;
  }
  
  function removeRoute(routeID) {
  	router.removeRoute({RouteId: routeID});
  	document.getElementById('map24_desc').innerHTML = '';
	  document.getElementById("map24_calc").disabled = false;
	  document.getElementById("map24_del").disabled = true;
  }
  
  function $v( id ) { 
    return   (document.getElementById( id ).value != "undefined") ? document.getElementById( id ).value : ""; 
  }
  
  Number.prototype.round = function (precision) {
    var number = Math.round(this * Math.pow(10, precision)) / Math.pow(10, precision);
    return number.toFixed(precision);
  };
  
  window.onload = goMap24();
