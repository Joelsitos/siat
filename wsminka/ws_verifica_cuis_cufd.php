<?php
ob_start(); // Inicia el buffer de salida
require_once '../conexionmysqli2.php';    
require("../siat_folder/funciones_siat.php");

header('Content-Type: application/json');

$json  = file_get_contents("php://input");
$datos = json_decode($json, true);

if (!isset($datos['sucursal'])) {
    ob_clean();
    echo json_encode(["status" => false, "mensaje" => "No se recibió el código de sucursal"]);
    exit;
}

$cod_ciudad_externo = $datos['sucursal'];
$fecha = date('Y-m-d');

// ! == Obtener datos de ciudad ==
$datosCiudad = obtenerAlmacen($cod_ciudad_externo, $enlaceCon);
list($cod_ciudad, $cod_almacen, $cod_impuestos, $cod_entidad) = $datosCiudad;

if ($cod_ciudad == 0) {
    ob_clean();
    echo json_encode(["status" => false, "mensaje" => "No se encontró ciudad para el código externo $cod_ciudad_externo"]);
    exit;
}

// ! === Verificar PUNTO DE VENTA ===
$codigoPuntoVenta = obtenerPuntoVenta_BD($cod_ciudad_externo);
if (!$codigoPuntoVenta) {
    ob_clean();
    echo json_encode(["status" => false, "mensaje" => "No se pudo encontrar el Punto de Venta."]);
    exit;
}

// ! === Verificar o generar CUIS ===
$cuis = verificarCodigoCuisActual($cod_ciudad, $cod_ciudad_externo, $cod_impuestos, $codigoPuntoVenta, $cod_entidad, $enlaceCon);
if (!$cuis) {
    ob_clean();
    echo json_encode(["status" => false, "mensaje" => "No se pudo obtener ni generar CUIS."]);
    exit;
}

// ! === Verificar o generar CUFD ===
$datosCufd = verificarCodigoCufdActual($cod_ciudad, $cod_ciudad_externo, $fecha, $cuis, $cod_impuestos, $codigoPuntoVenta, $cod_entidad, $enlaceCon);
if (!$datosCufd) {
    ob_clean();
    echo json_encode(["status" => false, "mensaje" => "No se pudo obtener ni generar CUFD."]);
    exit;
}

// === Respuesta FINAL ===
ob_clean();
echo json_encode([
    "status" => true,
    "mensaje" => "CUIS y CUFD válidos encontrados",
    "cuis" => $cuis,
    "cufd" => $datosCufd['cufd'],
    "codigo_control" => $datosCufd['codigo_control'],
    "codigo_cufd" => $datosCufd['codigo']
]);

/* ==================================================================== */
/**********************
 * * VERIFICA ALMACEN
 **********************/
function obtenerAlmacen($cod_ciudad_externo, $enlaceCon) {
    $sql = "SELECT c.cod_ciudad, a.cod_almacen, c.cod_impuestos, c.cod_entidad
            FROM ciudades c
            JOIN almacenes a ON c.cod_ciudad = a.cod_ciudad
            WHERE c.cod_externo = '$cod_ciudad_externo'
            LIMIT 1";
    $resp = mysqli_query($enlaceCon, $sql);
    if ($row = mysqli_fetch_assoc($resp)) {
        return [$row['cod_ciudad'], $row['cod_almacen'], $row['cod_impuestos'], $row['cod_entidad']];
    }
    return [0, 0, 0, 0];
}
/******************************
 * * VERIFICA Y GENERA EL CUIS
 ******************************/
function verificarCodigoCuisActual($cod_ciudad, $cod_ciudad_externo, $cod_impuestos, $puntoVenta, $cod_entidad, $enlaceCon) {
    $intentos = 0;
    do {
        $sql = "SELECT cuis FROM siat_cuis WHERE cod_ciudad = $cod_ciudad AND estado = 1 ORDER BY codigo DESC LIMIT 1";
        $resp = mysqli_query($enlaceCon, $sql);
        if ($row = mysqli_fetch_assoc($resp)) {
            return $row['cuis'];
        } elseif ($intentos == 0) {
            generarCuis($cod_ciudad_externo, $cod_impuestos, $puntoVenta, $cod_entidad);
        }
        $intentos++;
    } while ($intentos < 2);

    return false;
}
/******************************
 * * VERIFICA Y GENERA EL CUFD
 ******************************/
function verificarCodigoCufdActual($cod_ciudad, $cod_ciudad_externo, $fecha, $cuis, $cod_impuestos, $puntoVenta, $cod_entidad, $enlaceCon) {
    $intentos = 0;
    do {
        $sql = "SELECT codigo, cufd, codigo_control 
                FROM siat_cufd 
                WHERE cod_ciudad = $cod_ciudad AND estado = 1 AND fecha = '$fecha' AND cuis = '$cuis' 
                ORDER BY codigo DESC LIMIT 1";
        $resp = mysqli_query($enlaceCon, $sql);
        if ($row = mysqli_fetch_assoc($resp)) {
            return $row;
        } elseif ($intentos == 0) {
            generarCufd($cod_ciudad_externo, $cod_impuestos, $puntoVenta, $cod_entidad);
        }
        $intentos++;
    } while ($intentos < 2);

    return false;
}
