<?php
include_once 'dbMySql.php';
$con = new DB_con();
$formatos=array('.jpg','.png','.gif','.ico');


if(isset($_POST['modificar']))
{
    $id= $_POST['id'];
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $lugar = $_POST['lugar'];
    $coordenada_x = $_POST['coordenadaX'];
    $coordenada_y = $_POST['coordenadaY'];
    $imagen = $_POST['imagen'];
}

if (isset($_POST['mapa'])) {

    $id_map = $_POST['user_id'];
    $latitud = $_POST['lat'];
    $longitud = $_POST['lng'];

    $res=$con->update1($id_map,$latitud,$longitud);
    if ($res) {
             echo '<script> alert(" Exito al ingresar la coordenada"); </script>';
            echo "<script> window.location='index.php'; </script>";
    }
    else{
            echo '<script> alert(" Error al ingresar la coordenada"); </script>';
            echo "<script> window.location='index.php'; </script>";
    }

}



if(isset($_POST['modificar_datos']))
{

        $nombre=$_FILES['archivo']['name']; 
        $nombreTmp=$_FILES['archivo']['tmp_name'];
        $exte=substr($nombre, strrpos($nombre,'.'));
        if (in_array($exte,$formatos)) {
            if (move_uploaded_file($nombreTmp,"imagenes/$nombre")) {
                 echo "<script> window.location='index.php'; </script>";
            }
        }
        else{
                echo '<script> alert(" Error al ingresar la imagen"); </script>';
                echo "<script> window.location='index.php'; </script>";
                
        }

    $user_id = $_POST['user_id'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $user = $_POST['user_name'];
    $email1 = $_POST['email1'];
    $lugar1 = $_POST['lugar1'];
    $latitud = $_POST['lat'];
    $longitud = $_POST['lng'];
    // $coordenada_x1 = $_POST['coordenada_x1'];
    // $coordenada_y1 = $_POST['coordenada_y1'];
    $imagenPer = $nombre;
    
    $res=$con->update($user_id,$fname,$lname,$user,$email1,$lugar1,$imagenPer,$latitud,$longitud);
    if($res)
    {
        ?>
        <script>
        alert('Exito al modificar');
        window.location='index.php'
        </script>
        <?php
    }
    else
    {
        ?>
        <script>
        alert('Error al modificar');
        window.location='index.php'
        </script>
        <?php
    }
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP Data</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>
<center>

<div id="body">
	<div id="content">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo $id; ?>">
    <table align="center">
    <tr>
    <td><input type="text" name="first_name" placeholder=" Ingrese su Nombre" value="<?php echo $nombre;?>"required /></td>
    </tr>

    <tr>
    <td><input type="text" name="last_name" placeholder="Ingrese su Apellido" value="<?php echo $apellido; ?>"required /></td>
    </tr>

    <tr>
    <td><input type="text" name="user_name" placeholder="Ingrese su Usuario" value="<?php echo $usuario; ?>" required /></td>
    </tr>
    
    <tr>
        <td><input type="email" name="email1" placeholder="Email" value="<?php echo $email; ?>" required /></td>
    </tr>
    <tr>
        <td><input type="text" name="lugar1" placeholder="Lugar"  value="<?php echo $lugar;?>" required /></td>
    </tr>
    <tr>
        <td><input type="text" name="coordenada_x1" placeholder="Coordenada x"  value="<?php echo $coordenada_x;?>" required />
            <input type="text" name="coordenada_y1" placeholder="Coordenada y" value="<?php echo $coordenada_y;?>" required /></td>
    </tr>
    <tr>
        <td>
            <img src="imagenes/<?php echo $imagen; ?>" alt="image" class="imagen">
            <input name="archivo" type="file" />
        </td>
       
            
        
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
                 <!--  <input type="submit" name="mapa" value="Guardar Coordenada"> -->
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
    <button type="submit" name="modificar_datos"><strong>Modificar</strong></button></td>
    </tr>
    </table>
    </form>
    </div>
</div>

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