<?php
require("../../conexionmysqli.php");

$codigo=$_GET["codigo"];
$sqlIngreso="select i.cod_tipoingreso, i.cod_proveedor from ingreso_almacenes i where i.cod_ingreso_almacen=$codigo" ;
$respIngreso=mysqli_query($enlaceCon,$sqlIngreso);
while($datIngreso=mysqli_fetch_array($respIngreso)){
	$codTipoIngreso=$datIngreso[0];
	$codProveedor=$datIngreso[1];
}




$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysqli_query($enlaceCon,$sql1);


$sql2="select cod_proveedor, nombre_proveedor from proveedores order by 2";
$resp2=mysqli_query($enlaceCon,$sql2);
?>
<center>
    <div id='pnlfrmcodigomodificar'>
        <br>
        <table class="texto" border="1" cellspacing="0">
            <tr><td colspan="2">Datos a Modificar</td></tr>
            <tr>
				<td>Tipo de Ingreso:</td>
				<td>
					<select name='comboproveedor' id='combotipoingreso' class='texto'>
					<?php
					while($dat1=mysqli_fetch_array($resp1)){
					?>
						<option value='<?=$dat1[0];?>' <?=($dat1[0]==$codTipoIngreso)?"selected":"";?> ><?=$dat1[1];?></option>
					<?php
					}
					?>
					</select>
				</td>
			</tr>
            <tr>
				<td>Proveedor:</td>
				<td>
					<select name='comboproveedor' id='comboproveedor' class='texto'>
					<?php
					while($dat2=mysqli_fetch_array($resp2)){
					?>
						<option value='<?=$dat2[0];?>' <?=($dat2[0]==$codProveedor)?"selected":"";?> ><?=$dat2[1];?></option>
					<?php
					}
					?>
					</select>
					</td>
			</tr>
        </table>
        <br>
    </div>
</center>
<?php

?>
