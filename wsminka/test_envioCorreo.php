<?php
// Mostrar errores de PHP en la web
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye tus funciones necesarias
require_once "../conexionmysqli2.php";
// require_once "../estilos_almacenes.inc";
require_once "../funciones.php";
require_once "../funciones_inventarios.php";
require_once "../enviar_correo/php/send-email_anulacion.php";
require_once "../siat_folder/funciones_siat.php";

// ------------------------------
// Parámetros de prueba
$codigoVenta    = 8;
$correo_destino = "roalmirandadark@gmail.com";

// ------------------------------
// Ejecutar envío
$estado_envio = envio_factura_token($codigoVenta, $correo_destino, $enlaceCon);

// ------------------------------
// Mostrar resultado
if ($estado_envio == 1) {
    echo "✅ Correo enviado correctamente.";
} else {
    echo "❌ Error al enviar el correo.";
}
?>
