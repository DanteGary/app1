<html>
	<head>
	<title>Google Maps directions for multiple waypoints</title>
<meta name="keywords" content="Google Maps directions for multiple waypoints, C#, .NET, ASP.NET, postcodes, maps, development, Doogal Bell, Chris Bell" />
<meta name="description" content="Use this page to get directions for routes with multiple waypoints using Google Maps, includes route optimisation" />
  <link rel="stylesheet" type="text/css" href="Content/site.min.css?v=6" />
  <script src="js/siteBundle.min.js?v=13" type="text/javascript"></script>
  <!-- <script src="js/jquery.js" type="text/javascript"></script>
  <script src="Scripts/bootstrap.js" type="text/javascript"></script>
  <script src="js/site.js" type="text/javascript"></script> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width" />
	<meta name="application-name" content="doogal.co.uk"/> 
	<meta name="msapplication-TileColor" content="#2161e0"/> 
	<meta name="msapplication-TileImage" content="58f5b71c-e014-451b-a429-3806bed36566.png"/>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<script type="text/javascript" src="SiteBundle.min.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script>
		var locationsAdded = 1;
var map;
var points = [];
var markers = [];
var directionsDisplay;
window.onload = function () {
    loadGoogleMaps("places", function () {
        var latlng = new google.maps.LatLng(-17.392566,-66.161816);
        var options = {
            zoom: 3,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggableCursor: "crosshair"
        };
        map = new google.maps.Map(document.getElementById("map"), options);
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(FullScreenControl(map));
        google.maps.event.addListener(map, "click", function (location) {
            getLocationInfo(location.latLng, "Location " + locationsAdded);
            locationsAdded++;
        });
        var renderOptions = { markerOptions: { visible: false } };
        directionsDisplay = new google.maps.DirectionsRenderer(renderOptions);
        // autocomplete
        var autocomplete = new google.maps.places.Autocomplete(document.getElementById("location"), {
            bounds: null,
            componentRestrictions: null,
            types: []
        });
        google.maps.event.addListener(autocomplete, "place_changed", function () {
            var place = autocomplete.getPlace();
            getLocationInfo(place.geometry.location, $("#location").val());
            map.setCenter(place.geometry.location);
            $("#location").val("");
        });
    });
};
function addLatLng() {
    "use strict";
    var latLong = new google.maps.LatLng($("#lat").val(), $("#lng").val());
    getLocationInfo(latLong, "Location " + locationsAdded);
    locationsAdded++;
    map.setCenter(latLong);
    $("#lat").val("");
    $("#lng").val("");
}
function addLocation() {
    "use strict";
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: $("#location").val() }, function (results) {
        if (results[0]) {
            var result = results[0];
            var latLong = result.geometry.location;
            getLocationInfo(latLong, $("#location").val());
            map.setCenter(latLong);
            $("#location").val("");
        }
        else {
            alert("Location not found");
        }
    });
}
function getLocationInfo(latlng, locationName) {
    "use strict";
    if (latlng != null) {
        var point = { LatLng: latlng, LocationName: locationName };
        points.push(point);
        buildPoints();
    }
}
function clearMarkers() {
    "use strict";
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
}
function buildPoints() {
    "use strict";
    clearMarkers();
    var html = "";
    for (var i = 0; i < points.length; i++) {
        var marker = new google.maps.Marker({ position: points[i].LatLng, title: points[i].LocationName });
        markers.push(marker);
        marker.setMap(map);
        html += "<tr><td>" + points[i].LocationName + "</td><td>" + roundNumber(points[i].LatLng.lat(), 6) + "</td><td>" + roundNumber(points[i].LatLng.lng(), 6) + "</td><td><button class=\"delete btn\" onclick=\"removeRow(" + i + ");\">Delete</button></td><td>";
        if (i < points.length - 1) {
            html += "<button class=\"moveDown btn\" onclick=\"moveRowDown(" + i + ");\">Move down</button>";
        }
        html += "</td><td>";
        if (i > 0) {
            html += "<button class=\"moveUp btn\" onclick=\"moveRowUp(" + i + ");\">Move up</button>";
        }
        html += "</td></tr>";
    }
    $("#waypointsLocations tbody").html(html);
}
function clearPolyLine() {
    "use strict";
    points = [];
    buildPoints();
    clearRouteDetails();
}
function clearRouteDetails() {
    "use strict";
    directionsDisplay.setMap(null);
    directionsDisplay.setPanel(null);
    $("#distance").html("");
    $("#duration").html("");
}
function removeRow(index) {
    "use strict";
    points.splice(index, 1);
    buildPoints();
    clearRouteDetails();
}
function moveRowDown(index) {
    "use strict";
    var item = points[index];
    points.splice(index, 1);
    points.splice(index + 1, 0, item);
    buildPoints();
    clearRouteDetails();
}
function moveRowUp(index) {
    "use strict";
    var item = points[index];
    points.splice(index, 1);
    points.splice(index - 1, 0, item);
    buildPoints();
    clearRouteDetails();
}
function getDirections() {
    "use strict";
    var directionsDiv = document.getElementById("directions");
    directionsDiv.innerHTML = "Loading...";
    var directions = new google.maps.DirectionsService();
    // build array of waypoints (excluding start and end)
    var waypts = [];
    var end = points.length - 1;
    var dest = points[end].LatLng;
    if (document.getElementById("roundTrip").checked) {
        end = points.length;
        dest = points[0].LatLng;
    }
    for (var i = 1; i < end; i++) {
        waypts.push({ location: points[i].LatLng });
    }
    var routeType = $("#routeType").val();
    var travelMode = google.maps.TravelMode.DRIVING;
    if (routeType === "Walking") {
        travelMode = google.maps.TravelMode.WALKING;
    }
    else if (routeType === "Public transport") {
        travelMode = google.maps.TravelMode.TRANSIT;
    }
    else if (routeType === "Cycling") {
        travelMode = google.maps.TravelMode.BICYCLING;
    }
    var unitsVal = $("#directionUnits").val();
    var directionUnits = google.maps.UnitSystem.METRIC;
    if (unitsVal === "Miles") {
        directionUnits = google.maps.UnitSystem.IMPERIAL;
    }
    var optimiseRoute = document.getElementById("optimise").checked;
    var request = {
        origin: points[0].LatLng,
        destination: dest,
        waypoints: waypts,
        travelMode: travelMode,
        unitSystem: directionUnits,
        optimizeWaypoints: optimiseRoute
    };
    directions.route(request, function (result, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            directionsDiv.innerHTML = "";
            directionsDisplay.setMap(map);
            directionsDisplay.setPanel(directionsDiv);
            directionsDisplay.setDirections(result);
            // calculate total distance and duration
            var distance = 0;
            var time = 0;
            var theRoute = result.routes[0];
            // start KML
            var kmlCode = kmlDocumentStart() + kmlStyleThickLine() + "<Placemark>\n" + kmlLineStart();
            for (var i = 0; i < theRoute.legs.length; i++) {
                var theLeg = theRoute.legs[i];
                distance += theLeg.distance.value;
                time += theLeg.duration.value;
                for (var j = 0; j < theLeg.steps.length; j++) {
                    for (var k = 0; k < theLeg.steps[j].path.length; k++) {
                        var thisPoint = theLeg.steps[j].path[k];
                        kmlCode += roundNumber(thisPoint.lng(), 6) + "," + roundNumber(thisPoint.lat(), 6) + " ";
                    }
                }
            }
            $("#distance").html("Total distance: " + getDistance(distance) + ", ");
            $("#duration").html("total duration: " + Math.round(time / 60) + " minutes");
            // end KML
            kmlCode += kmlLineEnd() + kmlStyleUrl("thickLine") + "</Placemark>\n" + kmlDocumentEnd();
            $("#kmlData").val(kmlCode);
        }
        else {
            var statusText = getDirectionStatusText(status);
            directionsDiv.innerHTML = "An error occurred - " + statusText;
        }
    });
}
function getDistance(distance) {
    "use strict";
    if ($("#directionUnits").val() === "Miles") {
        return Math.round((distance * 0.621371192) / 100) / 10 + " miles";
    }
    else {
        return Math.round(distance / 100) / 10 + " km";
    }
}
		</script>
	</head>
<body>
  <div class="container">
	<div class="header">
	<div class="Content">
	    
			

  <div id="map" style="width: 100%; height: 600px;text-align:center;">
    <span style="color:Gray;">Loading map...</span>
  </div>
  <br/>


  <div id="tabs" role="tabpanel">
    <div class="tab-content">
      <div id="locations" role="tabpanel" class="tab-pane active">
		    <div class="form-inline">
			   <label>Latitud: <input type="text" id="lat" style="width:100px;" class="form-control" /></label>
			    <label>Longitud: <input type="text" id="lng" style="width:100px;" class="form-control" /></label>
			    <input type="button" onclick="addLatLng()" value="Añadir" class="btn btn-primary"/>
		    </div>
            <form action="coordenadas.php" method="POST">
    		    <table id="waypointsLocations" style="width:100%;">
    			    <thead>
    				    <tr>
    					    <th style="text-align:left;">Posicion</th>
    					    <th style="text-align:left;"><img src="images/lat.png" />Latitud</th>
    					    <th style="text-align:left;"><img src="images/lng.png" />Longitud</th>
    					   
    				    </tr>
    			    </thead>
    			    <tbody>
    				    <tr>
    					    <td colspan="4">Added locations will appear here</td>
    				    </tr>
    			    </tbody>
    		    </table>

                <input type="text" name="lugar" placeholder="ingrese el nombre del lugar" required>
                <input type="submit" name="enviar" value="aceptar" />
            </form>
        <input type="button" onclick="clearPolyLine()" value="Clear" class="btn btn-primary"/> Removes all locations from the map
      </div>
	    <div id="options" role="tabpanel" class="tab-pane">
		    <table>
			    <tr>
				    <td>
					    <input type="checkbox" id="optimise" />
              <label for="optimise">Optimise route</label>
				    </td>
				    
			    </tr>
			    <tr>
				    <td>
					    <input type="checkbox" id="roundTrip" />
              <label for="roundTrip">Round trip</label>
				    </td>
				   
			    </tr>
		    </table>
      </div>
    </div>
  </div>
  <br/>
  <input type="button" onclick="getDirections()" value="Get directions" class="btn btn-primary"/> 
  <form action="download.php" method="post" style="display:inline;">
    <input type="hidden" name="fileName" value="route.kml" />
    <input type="submit" value="Download KML" class="btn btn-primary" />
    <textarea style="display:none;" id="kmlData" name="data" spellcheck="false">
		</textarea>
  </form>
  <br/><br/>
  <span id="distance"></span> <span id="duration"></span>
  <div id="directions">
  </div>

  <script src="js/waypoints.js?v=6" type="text/javascript"></script>

 </div>
 </div>
 </div>
 </body>
 </html>
</html>