<?php

if(isset($sw_correo)){
    $sw=true;
    $nombreFile="../siat_folder/Siat/temp/Facturas-XML/$cuf.pdf";
}else{
    $sw=false;
    $nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.pdf";  
}
unlink($nombreFile);	


$urlSIAT=obtenerValorConfiguracion($enlaceCon, 50);
$url = $urlSIAT."formatoFacturaOnLine.php?codVenta=".$codigoVenta;
//Get content as a string. You can get local content or download it from the web.
$downloadedFile = file_get_contents($url);
//Save content from string to .html file.
file_put_contents($nombreFile, $downloadedFile);

?>




