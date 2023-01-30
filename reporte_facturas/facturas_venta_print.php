<?php
ini_set('memory_limit', '-1');
set_time_limit(0);

require_once('../formatoFacturaOnLine_funcion.php');

include "../conexionmysqli.php";


$html="";

$fechaDesde=$_POST['fecha_desde'];
$fechaHasta=$_POST['fecha_hasta'];

$sql_rangoNumero="";
if(isset($_POST['numero_rango'])){
	if($_POST['numero_rango']!="" || $_POST['numero_rango']!=0){
		$porcionesNumero=explode("-", $_POST['numero_rango']);
		$numeroInicio=$porcionesNumero[0];
		$numeroFin=$porcionesNumero[1];
		$sql_rangoNumero=" and s.nro_correlativo >= $numeroInicio and s.nro_correlativo<= $numeroFin";
	}
}

$sqlDatosVenta="select s.cod_salida_almacenes,s.salida_anulada from salida_almacenes s 
where s.fecha BETWEEN '$fechaDesde' and '$fechaHasta' $sql_rangoNumero
order by s.nro_correlativo asc ";
         // echo $sqlDatosVenta;
$bandera_index=true;
$tipo_entrada=2;
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){

    $cod_salida_almacenes=$datDatosVenta['cod_salida_almacenes'];

    $html.=formatofacturaSIAT($cod_salida_almacenes,$bandera_index,$tipo_entrada);
    // echo "aqui";

    $bandera_index=false;
    // break;
}

// echo $html;
$nombreFile="../siat_folder/Siat/temp/Facturas-XML/cadenafacturas.pdf";
// unlink($nombreFile);
//descargarPDFFacturasCopiaCliente("cadena facturas",$html,50,$nombreFile);
$sw=true;
guardarPDFArqueoCajaVerticalFactura("cadena facturas",$html,$nombreFile,-100,$sw);

?><script type="text/javascript">
        var link = document.createElement('a');
        link.href = '<?=$nombreFile?>';
        link.download = 'LoteFacturas.pdf';
        link.dispatchEvent(new MouseEvent('click'));
        window.close();
        //window.location.href='deleteFile.php?file=<?=$nombreFile?>';

        </script><?php

?>