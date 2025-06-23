<?php
require_once("../conexionmysqli2.inc");
require_once("../enviar_correo/php/send-email_anulacion.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$fechaEnvio = "2025-04-26";

// 1. Mejorar gestión de memoria
mysqli_query($enlaceCon, "SET SESSION MAX_EXECUTION_TIME=0"); // Desactivar timeout

$sqlLogs = "SELECT l.json FROM log_facturas l 
           WHERE l.json LIKE '%generarFacturaMinka%' 
           AND l.fecha BETWEEN '$fechaEnvio 00:00:00' AND '$fechaEnvio 23:59:59'
           ORDER BY l.fecha ASC";
$respLogs = mysqli_query($enlaceCon, $sqlLogs);

// 2. Procesar en bloques si son muchos registros
while ($datLogs = mysqli_fetch_array($respLogs)) {
    
    // Limpiar buffers de salida en cada iteración
    if (ob_get_level() > 0) ob_clean();
    
    $jsonLog = $datLogs[0];
    $datos = json_decode($jsonLog, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Error JSON: " . json_last_error_msg());
        continue; // Continuar con siguiente iteración
    }

    // 3. Validar campos requeridos
    $nit = $datos['nitCliente'] ?? null;
    $razon_social = $datos['nombreFactura'] ?? null;
    $email = $datos['correo'] ?? null;
    
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


    //6. Generar PDF con nombre único
    if ($idVenta > 0 || $email==0 || $email=="" || $email=="0") {
        
        // 7. Limpiar variables en cada iteración
        unset($codigoVenta, $banderaNotificacion);
        
        // Usar descarga directa desde script externo
        $_GET['codVenta'] = $idVenta;
        include "../descargarFacturaPDF10.php";

        // Obtener el CUF para construir ruta
        $sqlDatosVenta="select s.siat_cuf from salida_almacenes s where s.cod_salida_almacenes='$idVenta'";
        $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
        $nombrePDF = '';
        if ($row=mysqli_fetch_array($respDatosVenta)) {
            $cuf = $row['siat_cuf'];
            $nombrePDF = "../facturas_email/$cuf.pdf";
        }

        if (!file_exists($nombrePDF)) {
            error_log("No se encontró el PDF generado para la venta $idVenta");
            continue;
        }
                
        //echo "$nit $razon_social $idVenta<br>";

        $email=$email.",facturacion@ibnorca.org,lunagonzalesmarco@gmail.com";
        // 10. Enviar email con archivo adjunto
        $banderaNotificacion = envio_factura_token_solo(
            $idVenta,
            $email,
            $enlaceCon, $nombrePDF
        );
        
    }

    //12. Liberar memoria explícitamente
    unset($datos, $jsonLog, $nombrePDF, $pdfContent);
    gc_collect_cycles();
}

// 13. Cerrar conexiones explícitamente
mysqli_close($enlaceCon);
echo "Proceso finalizado correctamente";
?>