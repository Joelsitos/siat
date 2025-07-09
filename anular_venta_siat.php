<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("siat_folder/funciones_siat.php");

require("enviar_correo/php/send-email_anulacion.php");

// $global_almacen=$_COOKIE["global_almacen"];
$global_almacen=0;

$codigo_registro=$_GET["codigo_registro"];
$enviar_correo=$_GET["enviar_correo"];
$correo_destino=$_GET["correo_destino"];
$rpt_tipoanulacion=$_GET["rpt_tipoanulacion"];
// $enviar_correo=true;
// $correo_destino="";
//datos de factura para anulacion siat
$anulado=0;
$fecha_X=date('Y-m-d');
$cufd=0;
$cuis=0;
$sql="SELECT s.fecha,s.siat_cuf,s.cod_almacen,s.salida_anulada,(select cod_impuestos from ciudades where cod_ciudad= a.cod_ciudad)as cod_impuestos,a.cod_ciudad,s.nro_correlativo,(select p.nombre_cliente from clientes p where p.cod_cliente=s.cod_cliente) as cliente,s.cod_cliente,s.siat_cuf,s.nit,
    (SELECT nombre_ciudad from ciudades where cod_ciudad=(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen))as nombre_ciudad,s.siat_codigotipodocumentoidentidad,s.siat_estado_facturacion,s.siat_complemento,s.siat_fechaemision
    FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen 
    WHERE s.cod_salida_almacenes in ($codigo_registro)";
     // echo $sql;
$resp_verif=mysqli_query($enlaceCon,$sql);
while($dat_verif=mysqli_fetch_array($resp_verif)){	
	$anulado=$dat_verif['salida_anulada'];
	$cuf=$dat_verif['siat_cuf'];
    $cod_impuestos=$dat_verif['cod_impuestos'];
    $cod_ciudad=$dat_verif['cod_ciudad'];
    $cod_impuestos=intval($cod_impuestos);
    $codigoPuntoVenta=obtenerPuntoVenta_BD($cod_ciudad);
    $cuis=obtenerCuis_siat($codigoPuntoVenta,$cod_impuestos);
    $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
    if($dat_verif['siat_codigotipodocumentoidentidad']==5){
          $nitCliente=$dat_verif['nit'];  
        }else{
          $nitCliente=$dat_verif['nit']." ".$dat_verif['siat_complemento'];
        }
        $sucursalCliente=$dat_verif['nombre_ciudad']; 
        $estado_siatCliente=$dat_verif['siat_estado_facturacion'];        
        $fechaCliente=date("d/m/Y",strtotime($dat_verif['siat_fechaemision']));
	$nro_correlativo = $dat_verif['nro_correlativo'];;
	$proveedor=$dat_verif['cliente'];
	$idproveedor=$dat_verif['cod_cliente'];
	// $correo_destino=obtenerCorreosListaCliente($idproveedor);
}		
$estado_envio = envio_factura_anulacion_token($idproveedor, $correo_destino, $enlaceCon, 2);
if($estado_envio==1){
	$texto_correo="<span style=\"border:1px;font-size:18px;color:#91d167;\"><b>SE ENVIÓ EL CORREO CON EXITO.</b></span>";
}elseif($estado_envio==0){
	$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>EL CLIENTE NO TIENE UN CORREO REGISTRADO</b></span>";
}else{
	$texto_correo="<span style=\"border:1px;font-size:18px;color:red;\"><b>Ocurrio un error al enviar el correo, vuelva a intentarlo.</b></span>";
}
echo "<script language='Javascript'>
	Swal.fire({
	title: 'SIAT: ".$mensaje." :)',
	html: '".$texto_correo."',
	type: 'success'
	}).then(function() {
		location.href='dFacturaElectronica.php?codigo_salida=".$codigo_registro."';
	});
	</script>";
exit;
// $anulado==0;
if($anulado==0){ //verificamos si no está anulado // 0 no anulada 1 //anulado
	if($cufd<>"0" and $cuis<>"0"){
		// echo "***";
		$respEvento=anulacionFactura_siat($codigoPuntoVenta,$cod_impuestos,$cuis,$cufd,$cuf,$rpt_tipoanulacion);
		// var_dump($respEvento);
		$mensaje=$respEvento[1];
		// $respEvento[0]=1;
		// $respEvento[1]="sisisi";
		if($respEvento[0]==1){
			
			// echo "<br>**".print_r($respEvento)."**<br>";
			$codigoEvento=$respEvento[0];
			$descripcion=$respEvento[1];

			$sql="update salida_almacenes set salida_anulada=1, estado_salida=3 where cod_salida_almacenes='$codigo_registro'";
			// $resp=mysql_query($sql);
			$resp=mysqli_query($enlaceCon,$sql);

			$sql="update facturas_venta set cod_estado=2 where cod_venta='$codigo_registro'";
			// $resp=mysql_query($sql);
			$resp=mysqli_query($enlaceCon,$sql);


			// //SACAMOS LA VARIABLE PARA ENVIAR EL CORREO O NO SI ES 1 ENVIAMOS CORREO DESPUES DE LA TRANSACCION
			// $banderaCorreo=obtenerValorConfiguracion(8);
			if($correo_destino==null || $correo_destino=="" || $correo_destino==" "){
				$enviar_correo=false;
			}
			
			if($enviar_correo){
				// header("location:sendEmailVenta.php?codigo=$codigo_registro&evento=2&tipodoc=1");
				// $estado_envio=envio_facturaanulada($idproveedor,$proveedor,$nro_correlativo,$cuf,$nitCliente,$sucursalCliente,$estado_siatCliente,$fechaCliente,$correo_destino,$enlaceCon);
				$estado_envio = envio_factura_token($idproveedor, $correo_destino, $enlaceCon, 2);
				if($estado_envio==1){
					$texto_correo="<span style=\"border:1px;font-size:18px;color:#91d167;\"><b>SE ENVIÓ EL CORREO CON EXITO.</b></span>";
				}elseif($estado_envio==0){
					$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>EL CLIENTE NO TIENE UN CORREO REGISTRADO</b></span>";
				}else{
					$texto_correo="<span style=\"border:1px;font-size:18px;color:red;\"><b>Ocurrio un error al enviar el correo, vuelva a intentarlo.</b></span>";
				}

				echo "<script language='Javascript'>
					Swal.fire({
				    title: 'SIAT: ".$mensaje." :)',
				    html: '".$texto_correo."',
				    type: 'success'
					}).then(function() {
					    location.href='dFacturaElectronica.php?codigo_salida=".$codigo_registro."';
					});
					</script>";//location.href='navegadorVentas.php';
			}else{
				echo "<script language='Javascript'>
				Swal.fire({
			    title: 'SIAT: ".$mensaje." :)',
			    html: '<b>EL CLIENTE NO TIENE UN CORREO REGISTRADO.</b>',
			    text: '',
			    type: 'success'
				}).then(function() {
				    location.href='dFacturaElectronica.php?codigo_salida=".$codigo_registro."';
				});
				</script>";	//location.href='dFacturaElectronica.php?codigo_salida=".$codigo_registro."';
			}

		}else{
			echo "<script language='Javascript'>
				Swal.fire({
			    title: 'SIAT: ".$mensaje." :(',
			    text: '',
			    type: 'error'
			}).then(function() {
			    location.href='dFacturaElectronica.php?codigo_salida=".$codigo_registro."';
			});
			</script>";
		}
	}else{
		echo "<script language='Javascript'>
			Swal.fire({
		    title: 'CUFD invalido para la Fecha de Emisión :(',
		    text: '',
		    type: 'error'
		}).then(function() {
		    location.href='dFacturaElectronica.php?codigo_salida=".$codigo_registro."';
		});
				</script>";		
	}
}else{
	echo "<script language='Javascript'>
		Swal.fire({
	    title: 'FACTURA YA ANULADA! :(',
	    text: '',
	    type: 'error'
	}).then(function() {
	    location.href='dFacturaElectronica.php?codigo_salida=".$codigo_registro."';
	});
			</script>";	
}




// echo "<script language='Javascript'>
// 		alert('El registro fue anulado.');
// 		location.href='dFacturaElectronica.php?codigo_salida=$codigo_registro';
// 		</script>";

?>