<?php 
header('Content-Type: text/xml'); 
echo '<markers>';
include ("conexion.php");
$sql=mysqli_query($con,"select * from users ORDER BY user_id");
while($row=mysqli_fetch_array($sql))
{
	echo "<marker id ='".$row['user_id']."' lat='".$row['coordenada_x']."' lng='".$row['coordenada_y']."'>\n";
	echo "</marker>\n";
}
mysql_close($bd);
echo "</markers>\n";
?>