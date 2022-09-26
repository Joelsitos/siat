<?php
require("../../conexionmysqli.php");

require("../../estilos_almacenes.inc");

require("../../funcion_nombres.php");
echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

$codProveedor=$_GET['codProveedor'];
$nombreProveedor=nombreProveedor($enlaceCon,$codProveedor);

?>
<script language='Javascript'>
	function guardar(f){	
	  var i;
                        var j=0;
                        datos=new Array();
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       datos[j]=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
						
                        if(j==0)
                        {       alert('Debe seleccionar al menos una Marca.');
                        }
                        else
                        {	
                                if(confirm('El Proveedor tendra control sobre las marcas seleccionadas, desea continuar?.'))
                                { 	f.datos_marcas.value=datos;                                        
                                }
                                else
                                {
                                        return(false);
                                }
								f.submit();
                        }

	}
</script>

<form action="guardaProveedorMarcas.php" method="post">
<input type="hidden" name="codProveedor" id="codProveedor" value="<?php echo $codProveedor?>">
<input type="hidden" id="datos_marcas" name="datos_marcas">
    
    <h1>Marcas <br>Proveedor - <?php echo $nombreProveedor;?></h1>
<center><table class='texto'>
<tr><th></th><th>Marcas</th></tr>
<?php	 
$sql="	select codigo, nombre, abreviatura  from marcas where estado=1 order by nombre asc";
$rs=mysqli_query($enlaceCon,$sql);
$cont=0;
while($reg=mysqli_fetch_array($rs)){
	$cont++;
    $codMarca = $reg['codigo'];
    $nomMarca = $reg['nombre'];
    $abrevMarca = $reg['abreviatura'];

	$sqlProvMarcas="select codigo from proveedores_marcas where cod_proveedor=".$_GET['codProveedor']." and codigo=".$codMarca;
	
	$rsProvMarcas=mysqli_query($enlaceCon,$sqlProvMarcas);	
	$cod_marca="";
	while($datProvMarcas=mysqli_fetch_array($rsProvMarcas)){
		$cod_marca = $datProvMarcas['codigo'];
	}
	?>
    <tr>
    <td><input type="checkbox" id="idchk<?php echo $cont;?>" value="<?php echo $codMarca;?>" <?php if($codMarca==$cod_marca){?> checked <?php } ?>></td>
	<td><?php echo $abrevMarca."-".$nomMarca;?></td>
	</tr>
   <?php
   } 
   ?>
</table>

</center>
<div class="divBotones">
    <input class="boton" type="button" value="Guardar" onclick="guardar(this.form);" />
    <input class="boton2" type="button" value="Cancelar" onclick="location.href='inicioProveedores.php'" />
</div>
</form>