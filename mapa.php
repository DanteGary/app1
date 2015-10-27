<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <link rel="stylesheet" href="styles.css" type="text/css" />
    <script type="text/javascript">
        function load() {
            var map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(-17.392566, -66.161816),
                zoom: 7,
                mapTypeId: 'roadmap'
            });
            var infoWindow = new google.maps.InfoWindow;
            downloadUrl("markers.php", function(data) {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName("marker");
                for (var i = 0; i < markers.length; i++) {
                    var point = new google.maps.LatLng(
                        parseFloat(markers[i].getAttribute("lat")),
                        parseFloat(markers[i].getAttribute("lng")));
                    var icon = 'marker.png';
                    var marker = new google.maps.Marker({
                        map: map,
                        position: point,
                        icon: icon,
                        title:markers[i].getAttribute("nombre")
                    });
                }
            });
        }
        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;
            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };
            request.open('GET', url, true);
            request.send(null);
        }
        function doNothing() {}
    </script>
</head>
<body onload="load()">
<div id="map" class="mapa-total"></div>
</body>
</html>








