<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");
require_once("../funcion_nombres.php");

	$codMaestro=$_GET['cod_maestro'];
	$nameMaestro=obtenerNombreMaestro($enlaceCon,$table,$codMaestro);
	
$sql=mysqli_query($enlaceCon,"select nombre, abreviatura from $tableDetalle where codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];
$abreviatura=$dat[1];

	
echo "<form action='$urlSaveEditDet' method='post'>";

echo "<h1>Editar $moduleDetNameSingular</h1>";
echo "<h1>$moduleNameSingular $nameMaestro</h1>";

echo "<center><table class='texto' width='60%'>";

echo "<input type='hidden' name='codigo' value='$codigo_registro'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' value='$nombre' required>
</td>";
echo "<tr><th align='left'>Abreviatura</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='abreviatura' size='30' value='$abreviatura' required>
</td>";
echo "</tr>";
echo "</table></center>";

echo "<input type='hidden' name='cod_maestro' value='$codMaestro'>";


echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"$urlList2\"'>
";

echo "</form>";
?>