<?php
$dbname            ='dbtuts';
$dbuser            ='root';
$dbpass            ='';
$dbserver          ='localhost';

$dbcnx = mysql_connect ("$dbserver", "$dbuser", "$dbpass");
mysql_select_db("$dbname") or die(mysql_error());
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <link rel="stylesheet" href="styles.css" type="text/css" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title>Vista</title>
        <script type="text/javascript">
        //Sample code written by August Li
        var icon = 'marker.png';
        var center = null;
        var map = null;
        var currentPopup;
        var bounds = new google.maps.LatLngBounds();
        function addMarker(lat, lng, info) {
            var pt = new google.maps.LatLng(lat, lng);
            bounds.extend(pt);
            var marker = new google.maps.Marker({
                position: pt,
                icon: icon,
                map: map
            });
            var popup = new google.maps.InfoWindow({
                content: info,
                maxWidth: 300
            });
            google.maps.event.addListener(marker, "click", function() {
                if (currentPopup != null) {
                    currentPopup.close();
                    currentPopup = null;
                }
                popup.open(map, marker);
                currentPopup = popup;
            });
            google.maps.event.addListener(popup, "closeclick", function() {
                map.panTo(center);
                currentPopup = null;
            });
        }
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(0, 0),
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
                },
                navigationControl: true,
                navigationControlOptions: {
                    style: google.maps.NavigationControlStyle.SMALL
                }
            });

            <?php
            $query = mysql_query("SELECT * FROM users");
            while ($row = mysql_fetch_array($query)){
                $name=$row['nombre'];
                $lat=$row['coordenada_x'];
                $lng=$row['coordenada_y'];
                $email=$row['email'];
                $image=$row['imagen'];
                
                echo ("addMarker($lat, $lng,'<h1>$name</h1><br/> <img src=\"imagenes/$image\" height=\"90px\" width=\"100%\" />');\n");

            }
            ?>

            center = bounds.getCenter();
            map.fitBounds(bounds);
        }
        </script>
     </head>
     <body onload="initMap()" style="margin:0px; border:0px; padding:0px;">
        <div id="map" class="mapa-total"></div>
    </body>
 </html>