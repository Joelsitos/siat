<?php 

require_once("../funciones.php");
//LLAVES DE ACCESO AL WS
$sIde = "MinkaSw123*";
$sKey = "rrf656nb2396k6g6x44434h56jzx5g6";


  //Lista de Tipos documento
	$parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
           "accion"=>"sincronizarParametricaTipoDocumentoIdentidad", //
           "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
           "nitEmpresa"=>'1020745020' //nit  de empresa
       );      

  //$url="http://localhost:8090/minka_siat_ibno/wsminka/ws_operaciones.php";
    
  $url="https://intranet.ibnorca.org:8880/siat_ibno/wsminka/ws_operaciones.php";
	
  $jsons=callService($parametros, $url);
  $obj=json_decode($jsons);//decodificando json
  header('Content-type: application/json');  
  print_r($jsons); 
      
  
?>
