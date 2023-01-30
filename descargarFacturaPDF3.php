<?php

require "conexionmysqli2.inc";
require "funciones.php";
require_once "siat_folder/funciones_siat.php";  

/* error_reporting(E_ALL);
 ini_set('display_errors', '1');
*/

if(isset($_GET['codVenta'])){
    $codSalida=$_GET['codVenta'];
}else{
    $codSalida=$codigoVenta;
}
$sqlDatosVenta="select s.siat_cuf
        from `salida_almacenes` s
        where s.`cod_salida_almacenes`='$codSalida'";
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
$cuf="";
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
    $cuf=$datDatosVenta['siat_cuf'];
}

$nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.pdf";  
$nombreFile2="siat_folder/Siat/temp/Facturas-XML/$cuf.xml";  
$urlSIAT=obtenerValorConfiguracion($enlaceCon, 50);
$url = $urlSIAT."formatoFacturaOnLine.php?codVenta=".$codSalida;
//Get content as a string. You can get local content or download it from the web.
$downloadedFile = file_get_contents($url);
//Save content from string to .html file.
file_put_contents($nombreFile, $downloadedFile);

/*DESDE AQUI EL XML*/

$facturaImpuestos=generarXMLFacturaVentaImpuestos($codSalida);

$archivo = fopen($nombreFile2,'a');    
fputs($archivo,$facturaImpuestos);
fclose($archivo);

$banderaArchivos=0;
if( file_exists($nombreFile) && file_exists($nombreFile2) ) {
    $banderaArchivos=1;
}

?>




