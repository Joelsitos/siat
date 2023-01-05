<?php
$estilosVenta=1;
require("conexionmysqli2.inc");
require("funciones.php");
require("funcion_nombres.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$nroCorrelativoBusqueda=$_GET['nroCorrelativoBusqueda'];

$verBusqueda=$_GET['verBusqueda'];
// $global_almacen=$_COOKIE['global_almacen'];
$global_almacen=$_GET['global_almacen'];
$clienteBusqueda=$_GET['clienteBusqueda'];

if($fechaIniBusqueda!=""){
    $fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);    
}else{
    $fechaIniBusqueda="--";
}
if($fechaFinBusqueda!=""){
    $fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
}else{
    $fechaFinBusqueda="--";
}


echo "<br><br><center><table class='table table-sm' cellspacing='0'>";

echo "<tr><th>&nbsp;</th><th>Sucursal</th><th>Caja</th><th>Nro. Doc</th><th>Fecha/hora<br>Registro Salida</th><th>TipoPago</th><th>Razon Social</th><th>NIT</th><th>Observaciones</th><th>Factura</th>";
    echo "</tr>";
//
//$sqlUser=" and s.cod_chofer='".$_COOKIE["global_usuario"]."' ";
if(isset($_GET["admin"])){
  $admin=$_GET["admin"];
}else{
    $admin=0;
}


// $consulta = "
//     SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
//     (select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
//     s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
//     (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit,s.cod_tipopago,s.monto_final,'' AS depositado,(SELECT cod_medico from recetas_salidas where cod_salida_almacen=s.cod_salida_almacenes LIMIT 1)cod_medico,monto_cancelado_usd,s.cod_delivery,s.fecha_anulacion,s.cod_chofer_anulacion,s.siat_estado_facturacion
//     FROM salida_almacenes s, tipos_salida ts 
//     WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida=1001 $sqlUser ";


$consulta = " SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
    (select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.cod_almacen), s.observaciones, 
    s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
    (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit,
    (select t.nombre_tipopago from tipos_pago t where t.cod_tipopago=s.cod_tipopago)as tipopago,s.siat_estado_facturacion,s.siat_usuario
    FROM salida_almacenes s, tipos_salida ts 
    WHERE s.cod_tiposalida = ts.cod_tiposalida and s.cod_tiposalida=1001 AND s.cod_almacen = '$global_almacen' ";

// $nroProcesoBusqueda=$_GET['nroProcesoBusqueda'];
// if($nroProcesoBusqueda!=0){
//   $consulta = $consulta."AND s.cod_salida_almacenes='$nroProcesoBusqueda' ";
// }else{

    if($nroCorrelativoBusqueda!="")
       {$consulta = $consulta."AND s.nro_correlativo='$nroCorrelativoBusqueda' ";
       }
    if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
       {$consulta = $consulta."AND '$fechaIniBusqueda'<=s.fecha AND s.fecha<='$fechaFinBusqueda' ";
       }
    if($clienteBusqueda!=0){
    	$consulta=$consulta." and cod_cliente='$clienteBusqueda' ";
    }   
    if($verBusqueda==1){
    	$consulta=$consulta." AND estado_salida=4 ";
    }
    if($verBusqueda==2){
        $consulta=$consulta." AND salida_anulada=1 ";
    }

// }
$consulta = $consulta."ORDER BY s.fecha desc, s.nro_correlativo DESC limit 0,20";

// echo $consulta;
$resp = mysqli_query($enlaceCon,$consulta);
//echo $consulta;
	
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_almacen = $dat[4];
    $obs_salida = $dat[5];
    $estado_almacen = $dat[6];
    $nro_correlativo = $dat[7];
    $salida_anulada = $dat[8];
    $cod_almacen_destino = $dat[9];
    $nombreCliente=$dat[10];
    $codTipoDoc=$dat[11];
    $nombreTipoDoc=nombreTipoDoc($enlaceCon,$codTipoDoc);
    $razonSocial=$dat[12];
    $razonSocial=strtoupper($razonSocial);
    $nitCli=$dat[13];
    $tipoPago=$dat[14];    
    
    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
    
    $sqlEstadoColor="select color from estados_salida where cod_estado='$estado_almacen'";
    $respEstadoColor=mysqli_query($enlaceCon,$sqlEstadoColor);
    $numFilasEstado=mysqli_num_rows($respEstadoColor);
    if($numFilasEstado>0){
        $datEstadoColor = mysqli_fetch_array($respEstadoColor);
        $color_fondo = $datEstadoColor[0];
        //$color_fondo=mysql_result($respEstadoColor,0,0);
        
    }else{
        $color_fondo="#ffffff";
    }
    $chk = "<input type='checkbox' name='codigo' value='$codigo'>";


    $urlDetalle="dFacturaElectronica.php";
    $siat_estado_facturacion=$dat['siat_estado_facturacion'];
    $siat_usuario=$dat['siat_usuario'];
    // if($codTipoDoc==4){
    //     $nro_correlativo="<i class=\"text-danger\">M-$nro_correlativo</i>";
    //     if($siat_estado_facturacion!=1){
    //          //$urlDetalle="dFactura.php";
    //     }
    // }else{
    //     $nro_correlativo="F-$nro_correlativo";
    // }
    $datosAnulacion="";
    $stikea="";
    $stikec="";
    if($salida_anulada==1){
        $stikea="<strike class='text-danger'>";        
        $stikec=" (ANULADO)</strike>";
        // $datosAnulacion="title='<small><b class=\"text-primary\">$nro_correlativo ANULADA<br>Caja:</b> ".nombreVisitador($dat['cod_chofer_anulacion'])."<br><b class=\"text-primary\">F:</b> ".date("d/m/Y H:i",strtotime($dat['fecha_anulacion']))."</small>' data-toggle='tooltip'";
        $chk="";
    }

    $estado_preparado=0;
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr>";
    echo "<td align='center'>&nbsp;$chk</td>";
    echo "<td align='center'><b>$stikea $nombre_almacen $stikec</b></td>";
    echo "<td align='center'>$stikea$siat_usuario $stikec</td>";
    echo "<td align='center'>$stikea$nombreTipoDoc-$nro_correlativo $stikec</td>";
    echo "<td align='center'>$stikea$fecha_salida_mostrar $hora_salida$stikec</td>";
    // echo "<td>$stikea $nombre_tiposalida $stikec</td>";
    echo "<td>$stikea $tipoPago $stikec</td><td>$stikea &nbsp;$razonSocial $stikec</td><td>$stikea&nbsp;$nitCli $stikec</td><td>$stikea &nbsp;$obs_salida $stikec</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";    
    
    $urlConversionFactura="convertNRToFactura.php?codVenta=$codigo";    
    
    $NRparaMostrar=$nombreTipoDoc."-".$nro_correlativo;
    $fechaParaMostrar=$fecha_salida_mostrar;
    
    /*echo "<td bgcolor='$color_fondo'><a href='javascript:llamar_preparado(this.form, $estado_preparado, $codigo)'>
        <img src='imagenes/icon_detail.png' width='30' border='0' title='Detalle'></a></td>";
    */
    switch ($siat_estado_facturacion) {
        case 1:$color_fondo="#99E80A";break;
        case 2:$color_fondo="#FF2E09";break;
        case 3:$color_fondo="#12A4DF";break;  
        default:$color_fondo="#12A4DF";break;      
    }
    

    
    
    $codigoVentaCambio=0;
    $sqlCambio="select c.cod_cambio from salida_almacenes c where c.cod_cambio=$codigo";
    $respCambio=mysqli_query($enlaceCon,$sqlCambio);
    // if($global_admin_cargo==1){
     while($datCambio=mysqli_fetch_array($respCambio)){
        $codigoVentaCambio=$datCambio[0];        
     }
     // if($codigoVentaCambio==0 ){
     //    echo "<td  bgcolor='$color_fondo'><a href='cambiarProductoVenta.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/change.png' width='30' border='0' title='Cambio de Producto'></a></td>";
     // }else{
     //    echo "<td  bgcolor='$color_fondo'><a href='notaSalidaCambio.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/icon_detail.png' width='30' border='0' title='Ver Detalle del Cambio'></a></td>";
     // }

     // if($codTipoDoc==2 && $salida_anulada==0){
        // echo "<td bgcolor='$color_fondo'>
        // <a href='#' onClick='ShowFacturar($codigo,$nro_correlativo);'>
        // <img src='imagenes/icon_detail.png' width='30' border='0' title='Convertir en Factura'></a></td>";   
     // }elseif($codTipoDoc==1 && $salida_anulada==0){
        // echo "<td align='center' bgcolor='$color_fondo'>
        // <a href='#' onClick='convertirNR($codigo);'>
        // <img src='imagenes/restaurar2.png' width='20' border='0' title='Convertir en NR y Anular Factura'></a>
        // </td>";
     // }else{
      //       echo "<td align='center' bgcolor='$color_fondo'> </td>";
      //    }
     // if($codTipoDoc!=1 && $codTipoDoc!=2){
     //    echo "<td  bgcolor='$color_fondo'> ";
     //    echo "</td>";   
     // }
     echo "<td  bgcolor='$color_fondo'> <a href='$urlDetalle?codigo_salida=$codigo&admin=$admin' target='_BLANK' title='DOCUMENTO FACTURA'  class='text-dark'><i class='material-icons'>description</i></a>";
        echo "</td>";
    // }

    echo "</tr>";
}


echo "</table></center><br>";

mysqli_close($enlaceCon);

?>
