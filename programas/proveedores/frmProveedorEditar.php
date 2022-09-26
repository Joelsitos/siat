<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

$codProv   = $_GET["codprov"];

$nomProv   = "";
$email   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";
$consulta="
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2, p.contacto,p.correo,p.cod_ciu,p.estado 
    FROM proveedores AS p 
    WHERE p.cod_proveedor = $codProv 
";
$rs=mysqli_query($enlaceCon,$consulta);
$nroregs=mysqli_num_rows($rs);
if($nroregs==1)
   {$reg=mysqli_fetch_array($rs);
    //$codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
	$email  = $reg["correo"];
	$codciu  = $reg["cod_ciu"];
	$estado  = $reg["estado"];
   }

?>
<center>
    <br/>
    <h1>Editar Distribuidor</h1>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Proveedor</th>
            <th>Direccion</th>
        </tr>
        <tr>
            <td><span id="codpro"><?php echo "$codProv"; ?></span></td>
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
	 if($cod_ciu==$codciu){ 
?>
		<option value="<?php echo $cod_ciu?>" selected="selected"><?php echo $abrev_ciu."-".$nombre_ciu;?></option>
<?php	
     } else	{ 
?>
		<option value="<?php echo $cod_ciu?>" ><?php echo $abrev_ciu."-".$nombre_ciu;?></option>
<?php		 
	 }
	}
?>
	</select> </td>		
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
	<input class="boton" type="button" value="Guardar" onclick="javascript:modificarProveedor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
</div>
