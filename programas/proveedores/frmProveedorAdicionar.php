<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$codProv   = "";
$nomProv   = "";
$email   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";

?>
<center>
    <br/>
    <h1>Adicionar Proveedor</h1>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Direccion</th>
        </tr>
        <tr>
            <td><span id="id"><?php echo "$codProv"; ?></span></td>
            <td><input type="text" id="nompro" value="<?php echo "$nomProv"; ?>"/></td>
            <td><input type="text" id="dir" value="<?php echo "$direccion"; ?>"/></td>
        </tr>
        <tr>
		 <th>Ciudad</th>
            <th colspan="2">Email</th>
           
        </tr>
        <tr>      
   <td>	<select name='cod_ciu' id='cod_ciu' class='texto' required>
  
		
<?php
	$sql3="select cod_ciu, nombre_ciu,abrev_ciu from ciudades2 where estado=1";
	$resp3=mysqli_query($enlaceCon,$sql3);
	while($dat3=mysqli_fetch_array($resp3)){
		$cod_ciu=$dat3[0];
		$nombre_ciu=$dat3[1];
		$abrev_ciu=$dat3[2];
?>
		<option value="<?php echo $cod_ciu?>"><?php echo $abrev_ciu."-".$nombre_ciu?></option>
<?php		
	}
?>
	</select></td>		
            <td colspan="2"><input type="text" id="email"  size="73" value="<?php echo "$email"; ?>"/></td>
         
        </tr>		
        <tr>
            <th>Telefono 1</th>
            <th>Telefono 2</th>
            <th>Contacto</th>
        </tr>
        <tr>
            <td><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></td>
            <td><input type="text" id="tel2" value="<?php echo "$telefono2"; ?>"/></td>
            <td><input type="text" id="contacto" value="<?php echo "$contacto"; ?>"/></td>
        </tr>
    </table>
</center>
<div class="divBotones">
    <input class="boton" type="button" value="Guardar" onclick="javascript:adicionarProveedor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
</div>