<?php
require_once("../conexionmysqli2.inc");
require_once("../enviar_correo/php/send-email_anulacion.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$fechaEnvio="2025-04-23";

$sqlLogs="SELECT l.json from log_facturas l 
where l.json like '%generarFacturaMinka%' and l.fecha BETWEEN '$fechaEnvio 00:00:00' and '$fechaEnvio 23:59:59'
order by 1 asc";
$respLogs=mysqli_query($enlaceCon, $sqlLogs);
while($datLogs=mysqli_fetch_array($respLogs)){
    $jsonLog=$datLogs[0];
    $datos = json_decode($jsonLog, true);
    // Verificar errores en el JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error al decodificar JSON: " . json_last_error_msg());
    }
    // Extraer los datos requeridos
    $nit = $datos['nitCliente'];          // NIT del cliente
    $razon_social = $datos['nombreFactura']; // Razón social (nombre en la factura)
    $email = $datos['correo'];            // Correo electrónico

    $sqlIdVenta="SELECT s.cod_salida_almacenes from salida_almacenes s where s.nit='$nit' and razon_social='$razon_social' and fecha='$fechaEnvio'";
    $respIdVenta=mysqli_query($enlaceCon, $sqlIdVenta);
    $idVenta=0;
    while($datIdVenta=mysqli_fetch_array($respIdVenta)){
        $idVenta=$datIdVenta[0];
    }

    if($idVenta==0){
        $sqlIdVenta="SELECT s.cod_salida_almacenes from salida_almacenes s where s.nit='$nit' and fecha='$fechaEnvio'";
        $respIdVenta=mysqli_query($enlaceCon, $sqlIdVenta);
        while($datIdVenta=mysqli_fetch_array($respIdVenta)){
            $idVenta=$datIdVenta[0];
        }
    }


    // Mostrar los resultados
    echo "NIT: " . $nit . "\n";
    echo "Razón Social: " . $razon_social . "\n";
    echo "Email: " . $email . "\n";
    echo "CODIGO DE VENTA: " . $idVenta . "\n";
    echo "<br>";

    $codigo=$idVenta;
    $codigoVenta=$codigo;
    require_once "../descargarFacturaPDF10.php";
    $banderaNotificacion=envio_factura_token_solo($codigoVenta,"lunagonzalesmarco@gmail.com,lunamarcoantonio@hotmail.com",$enlaceCon);
}

//echo $banderaNotificacion."<br>";
//echo "FINALIZACION PROCESO <br>";

?>