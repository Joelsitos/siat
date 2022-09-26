<?php 
// error_reporting(E_ALL);
    // ini_set('display_errors', '1');  

require_once("../funciones.php");
//LLAVES DE ACCESO AL WS
$sIde = "MinkaSw123*";
$sKey = "rrf656nb2396k6g6x44434h56jzx5g6";

  //VERIFICACION CUFD
 $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
         "accion"=>"verificarCUFDEmpresa", //
         "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
         "nitEmpresa"=>'315910027', //nit  de empresa
         "codSucursal"=>'0' //COD SUCURSAL
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
	
	
      
  
?>
