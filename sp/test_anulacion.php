<?php 


    require_once("../conexionmysqli.inc");
    require_once("../estilos_almacenes.inc");
    require_once("../siat_folder/funciones_siat.php");
    require_once("../enviar_correo/php/send-email_anulacion.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');  

$codSucursal=0;
$codigoSucursal=0;
$codigoPuntoVenta=1;
$cod_entidad=1;

$cuis="DFC4445D";


$cufd="BQUFDdEhDQkE=Nz0JBMjY0QUU5RkU=Q3xHQ2RJUUNZVUJIzNEFFQTFDMEQxN";

$cuf="45D79AF94379F31DE347EE959891D6DC0809F0578C0532A13E4E58E74";


$respEvento=anulacionFactura_siat($codigoPuntoVenta,$codigoSucursal,$cuis,$cufd,$cuf);
$mensaje=$respEvento[1];

print_r ($mensaje);




?>
