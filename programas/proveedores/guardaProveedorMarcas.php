<?php

require("../../conexionmysqli.php");


$vector=explode(",",$_POST['datos_marcas']);
echo  $vector;

$sql1="delete from proveedores_marcas where cod_proveedor=".$_POST['codProveedor']."";
	$resp1=mysqli_query($enlaceCon,$sql1);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++){
		$sql="insert into proveedores_marcas (cod_proveedor,codigo)values(".$_POST['codProveedor'].",".$vector[$i].")";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		
	}


if($resp) {
    echo "<script>
		alert('Se guardaron correctamente los cambios.');
		location.href='inicioProveedores.php';
		</script>";
} else {
   
    echo "<script>
		alert('Error al guardar los cambios.');
		location.href='proveedorMarcas.php?codProveedor=$codProveedor';
		</script>";
}

?>
