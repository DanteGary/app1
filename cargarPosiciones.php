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
        <style>
        #info{
            display: none;
        }
        </style>
      
     </head>
            
     <body >
        

        <form action="posiciones.php" method="get">
             <table>
                <tr>
                    <td>Coordenadas:</td>
                    <td>
                      <?php
                        $lat = "-17.392566";
                        $lng = "-66.161816";
                        $pos = "-17.392566,-66.161816";
                        echo "<div id='info' name='pos'>".$pos."</div>"
                      ?>
                      Latitud: <input type="text" id="latitud" name="lat">
                      Longitutd: <input type="text" id="longitud" name="lng">
                     
                    </td>
                </tr>

                <tr>
                  <td>
                    <?php 
                      echo "<div id='googleMap'></div>
                      <div id='respuesta'></div>";
                    ?>
                  </td>
                </tr>
                <tr>
                    <td>
                        Radio: <input type="text" name="radio" id="radio">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="guardarPosicion" id="guardarPosicion">
                    </td>
                </tr>   
            </table>
        </form>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
//mostrando imagen en vivo
    function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    };

    $(document).ready(function(){
      lat = "<?php echo $lat; ?>" ;
      lng = "<?php echo $lng; ?>" ;
      var map;
      function initialize() {
        var myLatlng = new google.maps.LatLng(lat,lng);
        var mapOptions = {
          zoom: 7,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
        var marker = new google.maps.Marker({
          position: myLatlng,
          draggable:true,
          animation: google.maps.Animation.DROP,
          web:"Localización geográfica!",
          icon: "marker.png"
        });
        
        google.maps.event.addListener(marker, 'dragend', function(event) {
          var myLatLng = event.latLng;
          lat = myLatLng.lat();
          lng = myLatLng.lng();
          document.getElementById('info').innerHTML = [
          lat,
          lng
          ].join(', ');
          var x = document.getElementById("latitud").value=lat;
          var y = document.getElementById("longitud").value=lng;
          //x.innerHTML=[lat];
          //y.innerHTML=[lng];
        });
        marker.setMap(map);
      }
      google.maps.event.addDomListener(window, 'load', initialize);
      $("#envia").click(function() { 
        var url = "upload.php";
        $("#respuesta").html('<img src="cargando.gif" />');
        $.ajax({
         type: "POST",
         url: url,
         data: 'lat=' + lat + '&lng=' + lng,
         success: function(data)
         {
           $("#respuesta").html(data);
         }
       });
      }); 
    });
</script>
    </body>
 </html>