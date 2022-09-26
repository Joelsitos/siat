<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");


echo "<center>";
echo "<h1>Proveedores</h1>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div><br><br>";


echo "<table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Nombre</th><th>Ciudad</th><th>Direccion</th><th>Correo</th><th>Telefono 1</th><th>Telefono 2</th><th>Contacto</th><th>Marcas</th>";
echo "</tr>";
$consulta="
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2,
	p.contacto, p.correo,p.cod_ciu,c2.nombre_ciu,c2.abrev_ciu
    FROM proveedores  p left join  ciudades2 c2 on (p.cod_ciu=c2.cod_ciu)
    WHERE 1 = 1 ORDER BY p.nombre_proveedor ASC";

$rs=mysqli_query($enlaceCon,$consulta);
$cont=0;
while($reg=mysqli_fetch_array($rs))
   {$cont++;
    $codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
	$correo  = $reg["correo"];
	$abrev_ciu  = $reg["abrev_ciu"];
	$nombre_ciu  = $reg["nombre_ciu"];
	
    echo "<tr>";
    echo "<td><input type='checkbox' id='idchk$cont' value='$codProv' ></td><td>$nomProv</td><td>$nombre_ciu</td><td>$direccion</td>
	<td>$correo</td><td>$telefono1</td>
	<td>$telefono2</td><td>$contacto</td>";
    echo "<td><table class='texto'><tr><td><a href='proveedorMarcas.php?codProveedor=$codProv'><img src='../../imagenes/etiqueta3.png' width='20'></a></td>";

				echo "<td><table class='texto'>";
		$sqlProvMarcas=" select pm.codigo, m.nombre, m.abreviatura from proveedores_marcas pm			
							inner join marcas m on(pm.codigo=m.codigo) where pm.cod_proveedor='".$codProv."' order by m.nombre asc";

			    $respProvMarcas=mysqli_query($enlaceCon,$sqlProvMarcas);
				while($datProvMarcas=mysqli_fetch_array($respProvMarcas)){
						$cod_marca=$datProvMarcas['codigo'];
						$nombre_marca=$datProvMarcas['nombre'];
						$abrev_marca=$datProvMarcas['abreviatura'];
					echo "<tr><td>$cod_marca - $abrev_marca - $nombre_marca.</td></tr>";	
				}	
			echo"</table></td></tr></table>";
			echo "</td>";
	echo "</tr>";
   }
echo "</table>";

echo "</center>";
?>
<input type="hidden" id="idtotal" name="idtotal" value="<?php echo $cont;?>">

<?php
echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";


?>
