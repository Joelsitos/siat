<?php

require "conexionmysqli2.inc";
require "funciones.php";
require_once "siat_folder/funciones_siat.php";  


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = json_decode(file_get_contents("php://input"), true);    
    $accion=NULL;
    if(isset($datos['accion'])&&isset($datos['sIdentificador'])&&isset($datos['sKey'])){
        if($datos['sIdentificador']=="MinkaSw123*"&&$datos['sKey']=="rrf656nb2396k6g6x44434h56jzx5g6"){
            $accion=$datos['accion']; //recibimos la accion
            $estado=false;
            $mensaje="";
            $lista=array();
            if($accion=="accionDescargarArchivos"){
                $codSalida=$datos['codVenta'];
                    
                /*AQUI VIENE EL CODIGO*/
                $sqlDatosVenta="select s.siat_cuf from `salida_almacenes` s where s.`cod_salida_almacenes`='$codSalida'";
                $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
                $cuf="";
                while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
                    $cuf=$datDatosVenta['siat_cuf'];
                }
                $rutaArchivoDescargar="siat_folder/Siat/temp/Facturas-XML/";

                $nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.pdf";  
                $nombreFile2="siat_folder/Siat/temp/Facturas-XML/$cuf.xml"; 

                $nameOnlyFile1=$cuf.".pdf";
                $nameOnlyFile2= $cuf.".xml";

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
                /*FIN CODIGO*/


                $resultado=array(
                            "estado"=>true,
                            "mensaje"=>"Correcto",
                            "banderaArchivos"=>$banderaArchivos, 
                            "nameOnlyFile1"=>"$nameOnlyFile1", 
                            "nameOnlyFile2"=>"$nameOnlyFile2",
                            "rutaArchivoDescargar"=>"$rutaArchivoDescargar"
                            );
            }else{
                $resultado=array("estado"=>false, 
                                "mensaje"=>"Error: Parametros incorrectos o sin datos!");
            }
        }else{
            $resultado=array("estado"=>false, 
                                "mensaje"=>"Error: No tiene acceso al WS!");
        }        
    }else{
        $resultado=array("estado"=>false, 
                                "mensaje"=>"Error: No tiene acceso al WS!");
    } 
    header('Content-type: application/json');
    echo json_encode($resultado);     
}else{
    $resp=array("estado"=>false, 
                "mensaje"=>"No tiene acceso al WS");
    header('Content-type: application/json');
    echo json_encode($resp);
}

?>




