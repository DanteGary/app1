<?php
 include('conexion.php');

if (isset($_POST['enviar'])) {

	$lats = $_POST["latitudes"];
	$longs = $_POST["longitudes"];
	$lugar = $_POST["lugar"];



	$cant = count($lats);

	$latitud_inicio = $lats[0];
	$longitud_inicio = $longs[0];

	$latitud_final = $lats[$cant-1];
	$longitud_final = $longs[$cant-1];


	$sql = "INSERT INTO lugar (id_lugar,nombre,latitud_inicio,longitut_inicio,latitud_final,longitut_final) VALUES ('','$lugar','$latitud_inicio','$longitud_inicio','$latitud_final','$longitud_final')";
	$row=mysqli_query($con, $sql);

	

	if (!$row) {
		echo "error";
	}
	else{
		$consult_id=$con->insert_id;
		echo "string".$consult_id;

		for ($i=0; $i < $cant ; $i++) { 
            $rutas = "INSERT INTO coordenadas (id_coordenada,latitud,longitud,lugar_id)
            VALUES ('','$lats[$i]','$longs[$i]',$consult_id)";
            if ($con->query($rutas) === TRUE) {
                
                echo '<script type="text/javascript">alert("Registro Ingresado");</script>';
                echo '<script type="text/javascript"> window.location ="mapa1.php";</script>';
            } else {
                echo "Error: " . $rutas . "<br>" . $conn->error;
                echo '<script type="text/javascript">alert("Registro no Ingresado");</script>';
                echo '<script type="text/javascript"> window.location ="mapa1.php";</script>';
            }
        }
	}

}



	
