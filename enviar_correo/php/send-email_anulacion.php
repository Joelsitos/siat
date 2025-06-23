<?php
require 'PHPMailer/send.php';
// require("../../funciones.php");

function envio_facturaanulada($idproveedor,$proveedor,$nro_correlativo,$cuf,$nitCliente,$sucursalCliente,$estado_siatCliente,$fechaCliente,$correosProveedor,$enlaceCon){
  $email = "";//cc de correo
  // $contact_message = trim($_POST['message']);
  $mail_username="IBNORCA SIAT";//Correo electronico emisor
  $mail_userpassword="";// contraseña correo emisor
  require_once("funciones.php");
  // $idproveedor=$_POST['idproveedor'];
  // $proveedor=$_POST['$proveedor'];
  // $nro_correlativo=$_POST['nro_correlativo'];
  // $cuf=$_POST['cuf'];

  $urlDir=obtenerValorConfiguracion($enlaceCon,46);
  // echo "aqui";
  // $correosProveedor=obtenerCorreosListaCliente($idproveedor);
  //$correosProveedor = "davidhuarina25@gmail.com,bsullcamani@gmail.com";
  if($correosProveedor<>""){
    $mail_addAddress=$correosProveedor;
    // if($email!=""){
    //   $mail_addAddress.=",".$email;  
    // }
    //$mail_addAddress="dhuarina@farmaciasbolivia.com.bo,asd";//correo electronico destino

    $template="enviar_correo/php/PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
    /*Inicio captura de datos enviados por $_POST para enviar el correo */
    $mail_setFromEmail=$mail_username;
    $mail_setFromName=$mail_username;
    $titulo_pedido_email="Anulación de Factura Nro: ".$nro_correlativo;
    $txt_message="Estimado Cliente:<br>\n<br>\n La factura Nro: ".$nro_correlativo." fue Anulada.<br>\n Gracias por su atención."; 

    $mail_subject=$titulo_pedido_email; //el subject del mensaje

      $datosCabecera['cuf']=$cuf;
      
      /*$datosCabecera['nombre_cliente']="<li>Cliente: ".$proveedor."</li>";
      if($idproveedor==146){
        $datosCabecera['nombre_cliente']="";
      } */
      $datosCabecera['nombre_cliente']="";    
      $datosCabecera['nro_factura']=$nro_correlativo;
      $datosCabecera['nit']=$nitCliente;
      
      $datosCabecera['sucursal']=$sucursalCliente; 
      $datosCabecera['estado_siat']=$estado_siatCliente;        
      $datosCabecera['fecha']=$fechaCliente;

    $flag=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0,$datosCabecera,$urlDir,$enlaceCon);
    if($flag!=0){//se envio correctamente
      return 1;
    }else{//error al enviar el correo
      return 2;
    }
  }else{
    return 0;//sin correo
  }
}

function envio_factura($codigoFac,$correosProveedor,$enlaceCon){
  $rutaArchivo="";
  $rutaArchivoCSV="";
  $fechaActual=date("Y-m-d H:m:s");

  $mail_userpassword="";// contraseña correo emisor
  $sqlDir="select valor_configuracion from configuraciones where id_configuracion=46";
  $respDir=mysqli_query($enlaceCon,$sqlDir);
  // $urlDir=mysqli_result($respDir,0,0);
  $datValidar=mysqli_fetch_array($respDir);   
  $urlDir=$datValidar[0];

    $template="../enviar_correo/php/PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
  
    $sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, ' ' as nombre_cliente, s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,s.siat_cuf,s.siat_complemento,(SELECT nombre_tipopago from tipos_pago where cod_tipopago=s.cod_tipopago) as nombre_pago,s.siat_fechaemision,s.siat_codigotipoemision,s.siat_codigoPuntoVenta,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda,s.nit,
    (SELECT nombre_ciudad from ciudades where cod_ciudad=(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen))as nombre_ciudad,s.siat_codigotipodocumentoidentidad,s.siat_estado_facturacion, s.razon_social
        from `salida_almacenes` s, `tipos_docs` t
        where s.`cod_salida_almacenes` in ($codigoFac) and
        s.`cod_tipo_doc`=t.`codigo`";
        // echo $sqlDatosVenta;
    $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
    $datosCabecera=[];
    $nombreCliente="";
    while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
      $nombreCliente=$datDatosVenta[2];
      $datosCabecera['cuf']=$datDatosVenta['siat_cuf'];
      $datosCabecera['nombre_cliente']="<li>Razón Social: ".$datDatosVenta['razon_social']."</li>";

      $datosCabecera['nro_factura']=$datDatosVenta[3];
      if($datDatosVenta['siat_codigotipodocumentoidentidad']==5){
        $datosCabecera['nit']=$datDatosVenta['nit'];  
      }else{
        $datosCabecera['nit']=$datDatosVenta['nit']." ".$datDatosVenta['siat_complemento'];
      }
      $datosCabecera['sucursal']=$datDatosVenta['nombre_ciudad']; 
      $datosCabecera['estado_siat']=$datDatosVenta['siat_estado_facturacion'];        
      $datosCabecera['fecha']=date("d/m/Y",strtotime($datDatosVenta['siat_fechaemision']));
    }
    $mail_addAddress=$correosProveedor;

    $titulo_pedido_email="IBNORCA SIAT"; //Factura Nro: ".$datosCabecera['nro_factura'];
    $txt_message="Estimado Cliente: "."<br>\n<br>\n
      Adjuntamos la factura Nro: ".$datosCabecera['nro_factura'].".";
    $txt_message.="<br>\n<br>\n
      Gracias."; //Con CUF: ".$cuf."<br>\n
    $mail_subject=$titulo_pedido_email; //el subject del mensaje
    $mail_setFromEmail="";
    $mail_setFromName="";

    $flag=sendemailFiles($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0,$rutaArchivo,$rutaArchivoCSV,$datosCabecera,$urlDir,1,$enlaceCon);
    // echo "aqui";
    if($flag!=0){//se envio correctamente
      return 1;
    }else{//error al enviar el correo
      return 2;
    }
}



function envio_factura_token($codigoFac,$correosProveedor,$enlaceCon){
  $rutaArchivo="";
  $rutaArchivoCSV="";
  $fechaActual=date("Y-m-d H:m:s");

  $mail_userpassword="";// contraseña correo emisor
  $sqlDir="select valor_configuracion from configuraciones where id_configuracion=46";
  $respDir=mysqli_query($enlaceCon,$sqlDir);
  // $urlDir=mysqli_result($respDir,0,0);
  $datValidar=mysqli_fetch_array($respDir);   
  $urlDir=$datValidar[0];

  $sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, ' ' as nombre_cliente, s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,s.siat_cuf,s.siat_complemento,(SELECT nombre_tipopago from tipos_pago where cod_tipopago=s.cod_tipopago) as nombre_pago,s.siat_fechaemision,s.siat_codigotipoemision,s.siat_codigoPuntoVenta,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda,s.nit,
    (SELECT nombre_ciudad from ciudades where cod_ciudad=(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen))as nombre_ciudad,s.siat_codigotipodocumentoidentidad,s.siat_estado_facturacion, s.razon_social
        from `salida_almacenes` s, `tipos_docs` t
        where s.`cod_salida_almacenes` in ($codigoFac) and
        s.`cod_tipo_doc`=t.`codigo`";
        // echo $sqlDatosVenta;
  $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
  $datosCabecera=[];
  $nombreCliente="";
  while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
    $nombreCliente=$datDatosVenta[2];
    $datosCabecera['cuf']=$datDatosVenta['siat_cuf'];
    $datosCabecera['nombre_cliente']="<li>Razón Social: ".$datDatosVenta['razon_social']."</li>";
    $datosCabecera['nro_factura']=$datDatosVenta[3];
    if($datDatosVenta['siat_codigotipodocumentoidentidad']==5){
      $datosCabecera['nit']=$datDatosVenta['nit'];  
    }else{
      $datosCabecera['nit']=$datDatosVenta['nit']." ".$datDatosVenta['siat_complemento'];
    }
    $datosCabecera['sucursal']=$datDatosVenta['nombre_ciudad']; 
    $datosCabecera['estado_siat']=$datDatosVenta['siat_estado_facturacion'];        
    $datosCabecera['fecha']=date("d/m/Y",strtotime($datDatosVenta['siat_fechaemision']));
  }
  $mail_addAddress=$correosProveedor;

  // Separamos los correos por coma
  $listaCorreos = explode(",", $mail_addAddress);
  // Obtenemos el primer correo
  $primerCorreo = trim($listaCorreos[0]);
  // Obtenemos los demás correos (si existen)
  $otrosCorreos = '';
  if (count($listaCorreos) > 1) {
      $otrosCorreos = implode(";", array_map('trim', array_slice($listaCorreos, 1)));
  }

  $titulo_pedido_email="IBNORCA SIAT";
  $txt_message="Estimado Cliente: "."<br>\n<br>\n
    Adjuntamos la factura Nro: ".$datosCabecera['nro_factura'].".";
  $txt_message.="<br>\n<br>\n
    Gracias."; //Con CUF: ".$cuf."<br>\n
  $mail_subject=$titulo_pedido_email; //el subject del mensaje
  $mail_setFromEmail="";
  $mail_setFromName="";


  /*AQUI ENVIAR CORREO*/
  $sIde = "ifinanciero";
  $sKey = "ce94a8dabdf0b112eafa27a5aa475751";

  // $rutaArchivo="c:/xampp/htdocs/ifinanciero/boletas20032023/";
  // $nombreArchivo="facturademo.pdf";
  $rutaArchivo="c:/xampp/htdocs/siat_ibno/siat_folder/Siat/temp/Facturas-XML/";
  $nombreArchivo=$datosCabecera['cuf'].".pdf";
  $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
            "accion"=>"EnviarCorreoCtaIbnoredFinanciero", 
            "NombreEnvia"=>"Facturacion IBNORCA", 
            "CorreoDestino"=>"$primerCorreo",
            "CorreoCopia" => "$otrosCorreos",
            "NombreDestino"=>"Cliente IBNORCA",
            "Asunto"=>"Envío de Factura ".$titulo_pedido_email,
            "Body"=>$txt_message,
            "RutaArchivo"=>$rutaArchivo,
            "NombreArchivo"=> $nombreArchivo
            );
  
    $datos=json_encode($parametros);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://ibnored.ibnorca.org/wsibno/correo/ws-correotoken.php"); // produccion
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $remote_server_output = curl_exec ($ch);
    curl_close ($ch);
    
    // imprimir en formato JSON
    header('Content-type: application/json');   
    //print_r($remote_server_output);   
    $respuestaEnvioCorreo = json_decode($remote_server_output, true);
    $estadoEnvioCorreo = $respuestaEnvioCorreo['estado'];
    if ($estado) {
        return 1;
    } else {
        return 2;
    }

    // // $flag=sendemailFiles($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0,$rutaArchivo,$rutaArchivoCSV,$datosCabecera,$urlDir,1,$enlaceCon);
    // // // echo "aqui";
    // if($flag!=0){//se envio correctamente
    //   return 1;
    // }else{//error al enviar el correo
    //   return 2;
    // }
}







function envio_factura_token_solo($codigoFac,$correosProveedor,$enlaceCon,$nombrePDF){
  $rutaArchivo="";
  $rutaArchivoCSV="";
  $fechaActual=date("Y-m-d H:m:s");

  $mail_userpassword="";// contraseña correo emisor
  $sqlDir="select valor_configuracion from configuraciones where id_configuracion=46";
  $respDir=mysqli_query($enlaceCon,$sqlDir);
  // $urlDir=mysqli_result($respDir,0,0);
  $datValidar=mysqli_fetch_array($respDir);   
  $urlDir=$datValidar[0];

  $sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, ' ' as nombre_cliente, s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,s.siat_cuf,s.siat_complemento,(SELECT nombre_tipopago from tipos_pago where cod_tipopago=s.cod_tipopago) as nombre_pago,s.siat_fechaemision,s.siat_codigotipoemision,s.siat_codigoPuntoVenta,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda,s.nit,
    (SELECT nombre_ciudad from ciudades where cod_ciudad=(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen))as nombre_ciudad,s.siat_codigotipodocumentoidentidad,s.siat_estado_facturacion, s.razon_social
        from `salida_almacenes` s, `tipos_docs` t
        where s.`cod_salida_almacenes` in ($codigoFac) and
        s.`cod_tipo_doc`=t.`codigo`";
        // echo $sqlDatosVenta;
  $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
  $datosCabecera=[];
  $nombreCliente="";
  while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
    $nombreCliente=$datDatosVenta[2];
    $datosCabecera['cuf']=$datDatosVenta['siat_cuf'];
    $datosCabecera['nombre_cliente']="<li>Razón Social: ".$datDatosVenta['razon_social']."</li>";
    $datosCabecera['nro_factura']=$datDatosVenta[3];
    if($datDatosVenta['siat_codigotipodocumentoidentidad']==5){
      $datosCabecera['nit']=$datDatosVenta['nit'];  
    }else{
      $datosCabecera['nit']=$datDatosVenta['nit']." ".$datDatosVenta['siat_complemento'];
    }
    $datosCabecera['sucursal']=$datDatosVenta['nombre_ciudad']; 
    $datosCabecera['estado_siat']=$datDatosVenta['siat_estado_facturacion'];        
    $datosCabecera['fecha']=date("d/m/Y",strtotime($datDatosVenta['siat_fechaemision']));
  }
  $mail_addAddress=$correosProveedor;

  // Separamos los correos por coma
  $listaCorreos = explode(",", $mail_addAddress);
  // Obtenemos el primer correo
  $primerCorreo = trim($listaCorreos[0]);
  // Obtenemos los demás correos (si existen)
  $otrosCorreos = '';
  if (count($listaCorreos) > 1) {
      $otrosCorreos = implode(";", array_map('trim', array_slice($listaCorreos, 1)));
  }

  $titulo_pedido_email="IBNORCA SIAT";
  $txt_message="Estimado Cliente: "."<br>\n<br>\n
    Adjuntamos la factura Nro: ".$datosCabecera['nro_factura'].".";
  $txt_message.="<br>\n<br>\n
    Gracias."; //Con CUF: ".$cuf."<br>\n
  $mail_subject=$titulo_pedido_email; //el subject del mensaje
  $mail_setFromEmail="";
  $mail_setFromName="";


  /*AQUI ENVIAR CORREO*/
  $sIde = "ifinanciero";
  $sKey = "ce94a8dabdf0b112eafa27a5aa475751";

  // $rutaArchivo="c:/xampp/htdocs/ifinanciero/boletas20032023/";
  // $nombreArchivo="facturademo.pdf";
  $rutaArchivo="c:/xampp/htdocs/siat_ibno/facturas_email/";
  $nombreArchivo=$datosCabecera['cuf'].".pdf";
  //$nombreArchivo=$nombrePDF;
  $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
            "accion"=>"EnviarCorreoCtaIbnoredFinanciero", 
            "NombreEnvia"=>"Facturacion IBNORCA", 
            "CorreoDestino"=>"$primerCorreo",
            "CorreoCopia" => "$otrosCorreos",
            "NombreDestino"=>"Cliente IBNORCA",
            "Asunto"=>"Envío de Factura ".$titulo_pedido_email,
            "Body"=>$txt_message,
            "RutaArchivo"=>$rutaArchivo,
            "NombreArchivo"=> $nombreArchivo
            );
  
    $datos=json_encode($parametros);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://ibnored.ibnorca.org/wsibno/correo/ws-correotoken.php"); // produccion
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $remote_server_output = curl_exec ($ch);
    curl_close ($ch);
    
    // imprimir en formato JSON
    header('Content-type: application/json');   
    //print_r($remote_server_output);   
    $respuestaEnvioCorreo = json_decode($remote_server_output, true);
    $estadoEnvioCorreo = $respuestaEnvioCorreo['estado'];
    if ($estado) {
        return 1;
    } else {
        return 2;
    }
}
