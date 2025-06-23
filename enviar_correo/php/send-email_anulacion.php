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

    // $template="../enviar_correo/php/PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
    $template="../enviar_correo/php/PHPMailer/email_template_factura.html";
  
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
      // $datosCabecera['nombre_cliente']="<li>Razón Social: ".$datDatosVenta['razon_social']."</li>";
      $datosCabecera['nombre_cliente']=$datDatosVenta['razon_social'];

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
    $txt_message=""; //Con CUF: ".$cuf."<br>\n
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
    // $datosCabecera['nombre_cliente']="<li>Razón Social: ".$datDatosVenta['razon_social']."</li>";
    $datosCabecera['nombre_cliente']=$datDatosVenta['razon_social'];
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
  $codigo_nit_cliente = $datosCabecera['nit'];
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

//   $txt_message="Estimado Cliente: "."<br>\n<br>\n
//     Adjuntamos la factura Nro: ".$datosCabecera['nro_factura'].".";
//   $txt_message.="<br>\n<br>\n
//     Gracias."; //Con CUF: ".$cuf."<br>\n

    // * CONTENIDO DE CORREO SIAT
    // Mensaje principal al inicio del correo
    $txt_message = "Estimado cliente,<br>
    A continuación, podrá visualizar el detalle de la factura.<br>";

    // Mensaje de pie de página, irá debajo del detalle de la factura
    $txt_message_footer = "Adjuntamos la representación gráfica en formato PDF y el archivo XML de su factura electrónica.<br>";
   $template = dirname(__DIR__) . "/php/PHPMailer/email_template_factura.html";
      // Cargar y reemplazar plantilla
    $message = file_get_contents($template);
    $message = str_replace('{{titulo_men}}', $titulo_pedido_email, $message);
    $message = str_replace('{{message}}', $txt_message, $message);
    $message = str_replace('{{message_footer}}', $txt_message_footer, $message);

    // Botón para verificación (solo si estado_siat == 1)
    $botonEnvio = '<a href="' . $urlDir . '/consulta/QR?nit={{codigo_nit_gerente}}&cuf={{codigo_cuf}}&numero={{codigo_factura}}&t=2" 
        style="text-decoration:none;display:inline-block;color:#ffffff;background-color: #2563eb;border-radius:20px;
        width:auto;border:1px solid #2563eb;padding:5px 40px;font-family:Arial,sans-serif;text-align:center;"
        target="_blank"><span style="font-size: 16px; line-height: 2;">Verificar Factura</span></a>';

    if ($datosCabecera['estado_siat'] == 1) {
        $message = str_replace('{{boton_verificar}}', $botonEnvio, $message);
    } else {
        $message = str_replace('{{boton_verificar}}', '', $message);
    }
    
    // Obtener NIT del gerente desde configuración
    $sqlConf = "SELECT valor FROM configuracion_facturas WHERE id = 9 LIMIT 1";
    $respConf = mysqli_query($enlaceCon, $sqlConf);
    $nitTxt = mysqli_result($respConf, 0, 0);

    // Datos de la factura
    $reemplazos = [
        '{{codigo_cuf}}'          => $datosCabecera['cuf'],
        '{{codigo_cliente}}'      => $datosCabecera['nombre_cliente'],
        '{{codigo_nit}}'          => $datosCabecera['nit'],
        '{{codigo_sucursal}}'     => $datosCabecera['sucursal'],
        '{{codigo_fecha}}'        => $datosCabecera['fecha'],
        '{{codigo_factura}}'      => $datosCabecera['nro_factura'],
        '{{codigo_nit_gerente}}'  => $nitTxt,
        '{{anio_gestion}}'        => date('Y'),
    ];
    foreach ($reemplazos as $clave => $valor) {
        $message = str_replace($clave, $valor, $message);
    }

    $mail_subject=$titulo_pedido_email; //el subject del mensaje
    $mail_setFromEmail="";
    $mail_setFromName="";


    /*AQUI ENVIAR CORREO*/
    $sIde = "ifinanciero";
    $sKey = "ce94a8dabdf0b112eafa27a5aa475751";

    // $rutaArchivo="c:/xampp/htdocs/ifinanciero/boletas20032023/";
    // $nombreArchivo="facturademo.pdf";
    // * ARCHIVO XML
    $rutaArchivo    = "c:/xampp/htdocs/siat_ibno/siat_folder/Siat/temp/Facturas-XML/";
    $nombreArchivo  = $datosCabecera['cuf'].".xml";
    // * ARCHIVO PDF
    $rutaArchivo1   = "c:/xampp/htdocs/siat_ibno/siat_folder/Siat/temp/Facturas-XML/";
    $nombreArchivo1 = $datosCabecera['cuf'].".pdf";
    $parametros=array("sIdentificador"=>$sIde, "sKey"=>$sKey, 
            "accion"        => "EnviarCorreoCtaFacturacion", 
            "NombreEnvia"   => "Facturacion IBNORCA", 
            "CorreoDestino" => "$primerCorreo",
            // "CorreoCopia"   =>  "$otrosCorreos",
            // "CorreoDestino" => "roalmirandadark@gmail.com",
            "CorreoCopia"   =>  "roalmollericona@gmail.com;marco.luna@ibnorca.org",
            "NombreDestino" => "Cliente IBNORCA",
            "Asunto"        => "Envío de Factura ".$titulo_pedido_email,
            "Body"          => $message,
            "RutaArchivo"   => $rutaArchivo,
            "NombreArchivo" => $nombreArchivo,
            "RutaArchivo1"  => $rutaArchivo1,
            "NombreArchivo1"=> $nombreArchivo1,
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
    print_r($respuestaEnvioCorreo);
    $estadoEnvioCorreo = $respuestaEnvioCorreo['estado'];
    if ($estadoEnvioCorreo) {
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
