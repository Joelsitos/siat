<?php 
// error_reporting(E_ALL);
    // ini_set('display_errors', '1');  

require_once("../funciones.php");
//LLAVES DE ACCESO AL WS
$sIde = "MinkaSw123*";
$sKey = "rrf656nb2396k6g6x44434h56jzx5g6";

///verificar conexion
  // $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
  //           "accion"=>"verificarComunicacion", //Nuevo contacto de empresa
  //           "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
  //           "nitEmpresa"=>'10916889016' //nit  de empresa
  //         );  

  //Lista de Tipos de Pago

  // $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
  //           "accion"=>"sincronizarParametricaTipoMetodoPago", //
  //           "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
  //           "nitEmpresa"=>'10916889016' //nit  de empresa
  //         );

 //  //Lista de Tipos documento
	// $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
 //           "accion"=>"sincronizarParametricaTipoDocumentoIdentidad", //
 //           "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
 //           "nitEmpresa"=>'10916889016' //nit  de empresa
 //       );  


  //VERIFICACION CUFD
 $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
         "accion"=>"verificarCUFDEmpresa", //
         "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
         "nitEmpresa"=>'10916889016', //nit  de empresa
         "codSucursal"=>'1' //COD SUCURSAL
       );  

    
	$url="http://localhost:8090/minka_siat/wsminka/ws_operaciones.php";
	$parametros=json_encode($parametros);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $remote_server_output = curl_exec ($ch);
  curl_close ($ch);
	
  print_r($remote_server_output);
	
	
/*$obj=json_decode($remote_server_output);//decodificando json

if(isset($obj->estado))
  $estadoX=$obj->estado;
else $estadoX=0;
if(isset($obj->mensaje))
  $mensajeX=$obj->mensaje;
else $mensajeX="";
echo "estado: ".$estadoX." mensaje: ".$mensajeX;

if($estadoX){
  echo "<br> DETALLE : <br>";
  foreach ($obj->lista as $listaX) {    
    $codigoX=$listaX->codigo;  
    $codigoClasificadorX=$listaX->codigoClasificador;
    $descripcionX=$listaX->descripcion;

    echo "codigo: ".$codigoX." Cod Clasificador: ".$codigoClasificadorX." Descr:".$descripcionX."<br>";
  }  
}
*/


      
  
?>
