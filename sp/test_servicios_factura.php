<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');  

require_once("../funciones.php");
//LLAVES DE ACCESO AL WS
$sIde = "MinkaSw123*";
$sKey = "rrf656nb2396k6g6x44434h56jzx5g6";

//detalle de factura

$Objeto_detalle1 = new stdClass();
$Objeto_detalle1->codDetalle = 1;
$Objeto_detalle1->cantidadUnitaria = 1;
$Objeto_detalle1->precioUnitario = "300";
$Objeto_detalle1->descuentoProducto = 0;
// $Objeto_detalle1->monto_final = "34.8";
$Objeto_detalle1->conceptoProducto = "PAGO DE CANCHA,DEL 1 AL 3 DEL MES";  

$Objeto_detalle4 = new stdClass();
$Objeto_detalle4->codDetalle = 1;
$Objeto_detalle4->cantidadUnitaria = 1;
$Objeto_detalle4->precioUnitario = "300";
$Objeto_detalle4->descuentoProducto = 0;
// $Objeto_detalle4->monto_final = "34.8";
$Objeto_detalle4->conceptoProducto = "PAGO DE CANCHA,DEL 1 AL 3 DEL MES";
$arrayDetalle= array($Objeto_detalle1,$Objeto_detalle4);

$sucursal="1";
// $tipoTabla="1";//cod_producto
$idRecibo="28644";
$fecha="2022-11-05";
$idPersona="-28644";
$idPlan="36";
$cuota="9";
$monto_total="300";
$descuento=0;
$monto_final=$monto_total-$descuento;
$gestion="2022";
$id_usuario="1000";
$usuario="ester guardia";
$nitCliente=4868422;
$nombreFactura="CRISTIANO RONALDO";
// $NombreEstudiante="CRISTIANO RONALDO";
// $Concepto="PAGO DE CANCHA,DEL 1 AL 3 DEL MES";
$tipoPago="33"; //33 -> PAGO ONLINE
$nroTarjeta=0;
$tipoDocumento="1";
$complementoDocumento="";
// $periodoFacturado="JULIO-2022";


  // Lista de Tipos documento
$parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
   "accion"=>"generarFacturaMinka", //
   // "idEmpresa"=>2, //ID de empresa, otorgado por minkasoftware
   // "nitEmpresa"=>'10916889016', //Nit de empresa
   "sucursal"=>$sucursal,   
   "idRecibo"=>$idRecibo,
   "fecha"=>$fecha,
   "idPersona"=>$idPersona,
   "monto_total"=>$monto_total,
   "descuento"=>$descuento,
   "monto_final"=>$monto_final,
   "id_usuario"=>$id_usuario,//***
   "usuario"=>$usuario,//***
   "nitCliente"=>$nitCliente,
   "nombreFactura"=>$nombreFactura,   
   "tipoPago"=>$tipoPago,
   "nroTarjeta"=>$nroTarjeta,
   "tipoDocumento"=>$tipoDocumento,
   "complementoDocumento"=>$complementoDocumento,
   "correo"=>"bsullcamani@gmail.com",
   "items"=>$arrayDetalle
   // ,"NombreEstudiante"=>$NombreEstudiante,
   // "periodoFacturado"=>$periodoFacturado
);  
    
//formato base 64 factura
// $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
//            "accion"=>"obtenerFacturaBase64Siat",
//            "codFacturaIbno"=>31579
//        ); 

$url="http://localhost:8080/minka_siat_ibno/wsminka/ws_generarFactura.php";
//$url="https://intranet.ibnorca.org:8880/siat_ibno/wsminka/ws_generarFactura.php";
$jsons=callService($parametros, $url);
	//print_r($jsons);
  
$obj=json_decode($jsons);//decodificando json
header('Content-type: application/json');  
print_r($jsons); 

?>
