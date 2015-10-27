<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Google Maps JavaScript API v3 Example: Geocoding Simple</title>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<style type="text/css">   
  html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}
 
#map_canvas {
  height: 100%;
}
 
@media print {
  html, body {
    height: auto;
  }
 
  #map_canvas {
    height: 650px;
  }
}
</style>   
    <script>
      var geocoder;
      var map;
      function initialize() {
        var latlng = new google.maps.LatLng(-17.392566,-66.161816);
        var mapOptions = {
          zoom: 12,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
      }
 
      function codeAddress() {
        geocoder = new google.maps.Geocoder();
        var address = document.getElementById('address').value;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            document.getElementById('x').innerHTML = results[0].geometry.location.lat().toFixed(6);
            document.getElementById('y').innerHTML = results[0].geometry.location.lng().toFixed(6);
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
    </script>
  </head>
  <body onload="initialize()">
    <div>
      <input id="address" type="textbox" value="Cochabamba Bolivia" />
      <br />
      Latitud: <span id="x"></span>
      <br />
      Longitud: <span id="y"></span>
      <br />
      <input type="button" value="Localizar" onclick="codeAddress()">
    </div>
    <div id="map_canvas" style="height:90%;"></div>
  </body>
</html>