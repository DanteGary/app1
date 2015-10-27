<?php
include_once 'dbMySql.php';
$con = new DB_con();
$formatos=array('.jpg','.png','.gif','.ico');

// data insert code starts here.
if(isset($_POST['btn-save']))
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


	$fname = $_POST['first_name'];
	$lname = $_POST['last_name'];
	$city = $_POST['city_name'];
    $email = $_POST['email_name'];
    $lugar = $_POST['lugar_name'];
    $coordenada_x = $_POST['coordenada_x'];
    $coordenada_y = $_POST['coordenada_y'];
    $imagen = $nombre;
	
	$res=$con->insert($fname,$lname,$city,$email,$lugar,$coordenada_x,$coordenada_y,$imagen);
	if($res)
	{
		?>
		<script>
		alert('Record inserted...');
        window.location='index.php'
        </script>
		<?php
	}
	else
	{
		?>
		<script>
		alert('error inserting record...');
        window.location='index.php'
        </script>
		<?php

	}
}
// data insert code ends here.

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP Data</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<center>

<div id="body">
	<div id="content">
    <form method="post" enctype="multipart/form-data">
    <table align="center">
    <tr>
        <td><input type="text" name="first_name" placeholder="Nombre" required /></td>
    </tr>
    <tr>
        <td><input type="text" name="last_name" placeholder="Apellido" required /></td>
    </tr>
    <tr>
        <td><input type="text" name="city_name" placeholder="Usuario" required /></td>
    </tr>
    <tr>
        <td><input type="email" name="email_name" placeholder="Email" required /></td>
    </tr>
    <tr>
        <td><input type="text" name="lugar_name" placeholder="Lugar" required /></td>
    </tr>
    <tr>
        <td><input type="text" name="coordenada_x" placeholder="Coordenada x" required />
            <input type="text" name="coordenada_y" placeholder="Coordenada y" required />
        </td>
    </tr>
    <tr>
        <td><input name="archivo" type="file" /></td>
    </tr>
    <tr>
        <td>
        <button type="submit" name="btn-save"><strong>Guardar</strong></button></td>
    </tr>
    </table>
    </form>
    </div>
</div>

</center>
</body>
</html>