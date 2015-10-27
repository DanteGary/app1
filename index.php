<?php
include_once 'dbMySql.php';
$con = new DB_con();
$table = "users";
$res=$con->select($table);

if (isset($_POST['eliminar'])) {
    $id=$_POST['id'];
    $res=$con->delete($id);

    if ($res) {
      ?>
     <script>
        alert('Exito al eliminar');
        window.location='index.php';
    </script>
    <?php 
    }

    else{
        ?>
        <script>
            alert('Error al eliminar');
            window.location='index.php';
        </script>
        <?php
    }
}

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
    <table align="center">
    <tr>
    <th colspan="11"><a href="add_data.php">Ingresar Nuevo</a></th>
    </tr>
    <tr>
    <th>Id</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Usuario</th>
    <th>Email</th>
    <th>Lugar</th>
    <th colspan="2" >Coordenadas</th>
    <th>Imagen</th>
    <th colspan="3">Opciones</th>
    </tr>
    <?php
	while($row=mysql_fetch_row($res))
	{
			?>
            <tr>
            <td><?php echo $row[0]; ?></td>
            <td><?php echo $row[1]; ?></td>
            <td><?php echo $row[2]; ?></td>
            <td><?php echo $row[9]; ?></td>
            <td><?php echo $row[3]; ?></td>
            <td><?php echo $row[4]; ?></td>
            <td><?php echo $row[5]; ?></td>
            <td><?php echo $row[6]; ?></td>
            <td><img src="imagenes/<?php echo $row[7]; ?>" alt="image" class="imagen"></td>
            <td>
               <form action="modificar.php" method="post"">
                       <input type="hidden" name="id" value="<?php echo $row[0]; ?>">
                       <input type="hidden" name="nombre" value="<?php echo $row[1]; ?>">
                       <input type="hidden" name="apellido" value="<?php echo $row[2]; ?>">
                       <input type="hidden" name="usuario" value="<?php echo $row[9]; ?>">
                       <input type="hidden" name="email" value="<?php echo $row[3]; ?>">
                       <input type="hidden" name="lugar" value="<?php echo $row[4]; ?>">
                       <input type="hidden" name="coordenadaX" value="<?php echo $row[5]; ?>">
                       <input type="hidden" name="coordenadaY" value="<?php echo $row[6]; ?>"> 
                        <input type="hidden" name="imagen" value="<?php echo $row[7]; ?>">
                       <input type="submit" value="M" name="modificar">

                </form>
            </td>
            <td><form action="index.php" method="post"> <input type="hidden" name="id" value="<?php echo $row[0]; ?>"> <input type="submit" value="X" name="eliminar"> </form></td>
            </tr>


            <?php
	}
	?>

    </table>
    </div>
    </div>
</center>
</body>
</html>