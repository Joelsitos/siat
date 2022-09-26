<?php 
// error_reporting(E_ALL);
    // ini_set('display_errors', '1');  

require_once("../funciones.php");
//LLAVES DE ACCESO AL WS
$sIde = "MinkaSw123*";
$sKey = "rrf656nb2396k6g6x44434h56jzx5g6";

//verificar conexion
  // $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
  //           "accion"=>"verificarComunicacion", //Nuevo contacto de empresa
  //           "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
  //           "nitEmpresa"=>'10916889016' //nit  de empresa
  //         );

  //Lista de Tipos documento
	// $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
 //           "accion"=>"sincronizarParametricaTipoDocumentoIdentidad", //
 //           "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
 //           "nitEmpresa"=>'10916889016' //nit  de empresa
 //       );  


//VERIFICACION CUFD
//  $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
//        "accion"=>"verificarCUFDEmpresa", //
//       "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
//      "nitEmpresa"=>'10916889016', //nit  de empresa
//      "codSucursal"=>'1' //COD SUCURSAL
//    );  

//Obtener Cufd
$parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
            "accion"=>"obtenerCufdMinka", //Nuevo contacto de empresa
            "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
            "nitEmpresa"=>'10916889016', //nit  de empresa
            "codSucursal"=>'1' //codigo de agencia
          );
    
	$url="http://localhost:8080/minka_siat/wsminka/ws_operaciones.php";
	$jsons=callService($parametros, $url);
  $obj=json_decode($jsons);//decodificando json
  header('Content-type: application/json');  
  print_r($jsons); 
  



  /*if(isset($obj->estado))
    $estadoX=$obj->estado;
  else $estadoX=0;
  if(isset($obj->mensaje))
    $mensajeX=$obj->mensaje;
  else $mensajeX="";
  echo "estado: ".$estadoX." mensaje: ".$mensajeX;

  if($estadoX && isset($obj->lista)){
    echo "<br> DETALLE : <br>";
    foreach ($obj->lista as $listaX) {    
      $codigoX=$listaX->codigo;  
      $codigoClasificadorX=$listaX->codigoClasificador;
      $descripcionX=$listaX->descripcion;
      echo "codigo: ".$codigoX." Cod Clasificador: ".$codigoClasificadorX." Descr:".$descripcionX."<br>";
    }  
  }*/



      
  
?>
