<?php

$enlaceCon=mysqli_connect("lpsit.ibnorca.org","ingresobd","ingresoibno","pei","4606");

if (mysqli_connect_errno())
{
    echo "Error en la conexión: " . mysqli_connect_error();
}

mysqli_set_charset($enlaceCon,"utf8");

if (!function_exists('mysqli_result')) {
    function mysqli_result($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}
?>
