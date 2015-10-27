<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: 0; padding: 0 }
            #map-canvas { height: 100% }
        </style>
     <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
        function initialize() {
            var mapOptions = {
                zoom: 10,
                center: new google.maps.LatLng(-35.3075,149.1244)
            }
            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
        <?php
            $dbc = mysql_connect('localhost', 'root', '') or die('Unable to connect to MySQL: '.mysql_error()); 
            mysql_select_db ('dbtuts', $dbc) or die('Unable to connect to database: '.mysql_error());  
            $query = "SELECT * FROM lugar WHERE id_lugar=1";
            $result = mysql_query($query) or die("Error querying database: ".mysql_error());
            while ($row = mysql_fetch_array($result)) {
                ?>
                <script>
                    var link = 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20geo.placefinder%20where%20text%3D"<?php echo $row['location']?>"&format=json&diagnostics=true';
                    $.getJSON(link, function(json) {
                        console.log("<?php echo $row['nombre']?>");
                        console.log("Latitude: " + json.query.results.Result.latitude);
                        console.log("Longitude: " + json.query.results.Result.longitude);
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(Number(json.query.results.Result.latitude), Number(json.query.results.Result.longitude)),
                            map: map,
                            title: 'Hello World!'
                        });
                    });
                </script>
                <?php
            }
        ?>
    </head>
    <body>
        <div id="map-canvas" style="width: 100%; height: 100%"></div>
    </body>
</html>

