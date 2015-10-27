<?php
include_once 'dbMySql.php';
$con = new DB_con();
// data insert code starts here.
if(isset($_POST['btn-save']))
{
  $nombre=$_POST['nombre'];
  $apellido=$_POST['apellidos'];
  $email=$_POST['email'];
  $lugar=$_POST['lugar'];
  $pos=$_POST['pos'];
  $lat=$_POST['lat'];
  $lng=$_POST['lon'];

  error_reporting(E_ERROR | E_WARNING | E_PARSE);

  if(count($_FILES) > 0) {
    if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
      $imgData =addslashes(file_get_contents($_FILES['userImage']['tmp_name']));
      $imageProperties = getimageSize($_FILES['userImage']['tmp_name']);
    }
  }
  $campos="nombre, apellidos,email, lugar, Lat, Lng, Pos, imageType, imageData";
  $datos=" '$nombre','$apellido','$email','$lugar','$lat','$lng','$pos','{$imageProperties['mime']}','{$imgData}'";

  $res=$con->insert($campos,$datos);
  if($res)
  {
    echo "<b> Posición guardada: </b>".$lat.", ".$lng;
    ?>
    <script>
    alert('Se ha insertado satisfactoriamente');
        window.location='backend.php '
        </script>
    <?php
  }
  else
  {
    ?>
    <script>
    alert('error al insertar...');
        window.location='backend.php '
        </script>
    <?php
  }
}

?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Usuarios</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>
<center>

<div id="header">
	<div id="content">
    <label>Datos de usuario</label>
  </div>
</div>
<div id="body">
	<div id="content">
    <form name="frmImage" enctype="multipart/form-data" method="post" class="frmImageUpload">
      <table align="center">
        <tr>
          <td class="content_datos">
            <table class="datos">
              <tr>
                <td>Nombre:</td>
                <td><input type="text" name="nombre" id="nombre" requiered/></td>
              </tr>
              <tr>
                <td>Apellidos:</td>
                <td><input type="text" name="apellidos" id="apellidos" requiered/></td>
              </tr>
              <tr>
                <td>E-mail:</td>
                <td><input type="text" name="email" id="email" requiered/></td>
              </tr>
              <tr>
                <td>Lugar:</td>
                <td><input type="text" name="lugar" id="lugar" requiered/></td>
              </tr>
              <tr>
                <td>Coordenadas:</td>
                <td>
                  <?php
                    $lat = "-17.392566";
                    $lng = "-66.161816";
                    $pos = "-17.392566,-66.161816";
                    echo "<div id='info' name='pos'>".$pos."</div>"
                  ?>
                  <input type="text" id="latitud" name="lat">
                  <input type="text" id="longitud" name="lng">
                </td>
              </tr>
            </table>
          </td>
          <td class="content_images">
            <table class="images">
            <tr>
              <td>
                <label>Subir imagen:</label><br/>
                <input id="uploadImage" name="userImage" type="file" class="inputFile" onchange="PreviewImage();" />
                <img  id="uploadPreview" class="image_upload">
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
              <button type="submit" name="btn-save" class="btnSubmit btn btn-alert"><strong>Guardar</strong></button></td>
            </tr> 
            </table>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
</center>

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