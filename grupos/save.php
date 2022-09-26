<?php
require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

$sql="insert into $table (nombre, abreviatura, estado) values('$nombre','$abreviatura','1')";
//echo $sql;
$sql_inserta=mysqli_query($enlaceCon,$sql);

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='$urlList2';
			</script>";

?>