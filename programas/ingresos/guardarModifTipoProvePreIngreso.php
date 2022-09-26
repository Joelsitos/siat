<?php
require("../../conexionmysqli.php");

$codigoIngreso=$_GET["codigo"];
$tipoIngreso = $_GET["combotipoingreso"];
$proveedor = $_GET["comboproveedor"];

$sql="update preingreso_almacenes set cod_tipoingreso='$tipoIngreso', cod_proveedor='$proveedor' where cod_ingreso_almacen=$codigo";
$resp=mysqli_query($enlaceCon,$sql);


if($resp){
    echo "OK";
}else{
	echo "ERROR";
}

?>
