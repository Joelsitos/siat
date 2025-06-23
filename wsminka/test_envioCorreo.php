<?php
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
$estado_envio = envio_factura($codigoVenta, $correo_destino, $enlaceCon);


// ------------------------------
// Mostrar resultado
if ($estado_envio == 1) {
    echo "✅ Correo enviado correctamente.";
} else {
    echo "❌ Error al enviar el correo.";
}
?>
