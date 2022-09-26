<?php

// if(isset($_COOKIE['globalIdEntidad'])){
// 	$globalEntidad=$_COOKIE['globalIdEntidad'];
// 	switch ($globalEntidad) {
// 		case 2://Entidad prueba
				
			// $siat_nombreSistema='uniloyola v1';
			// $siat_codigoSistema='721CFFC51FA7DC9C67E0DA6';
			// $siat_tipo='PROPIO';
			// $siat_nit=315910027;
			// $siat_razonSocial='UNIVERSIDAD LOYOLA DE BOLIVIA SOCIEDAD ANONIMA';
			// $siat_tokenDelegado='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJMb3lvbGFTQTIwMTYiLCJjb2RpZ29TaXN0ZW1hIjoiNzIxQ0ZGQzUxRkE3REM5QzY3RTBEQTYiLCJuaXQiOiJINHNJQUFBQUFBQUFBRE0yTkxVME5EQXdNZ2NBc3hvd2ZBa0FBQUE9IiwiaWQiOjY2ODE1NSwiZXhwIjoxNjkyODM1MjAwLCJpYXQiOjE2NjEzOTUyMzgsIm5pdERlbGVnYWRvIjozMTU5MTAwMjcsInN1YnNpc3RlbWEiOiJTRkUifQ.qwvgqc-zTu_tOzos9_VG2-3s2mg1GwQYr1fX1Wokypuus5N4o_age-Sdpvtl2FPun-hbygZD7sfC5PqvxPLIlQ';
// 	error_reporting(E_ALL);
// ini_set('display_errors', '1');

			require dirname(__DIR__). SB_DS ."../conexionmysqli2.php";
			$consulta="select nombre_sistema,codigo_sistema,tipo_sistema,nit,razon_social,token_delegado,fecha_limite from siat_credenciales where cod_estado=1";
			$respFactura = mysqli_query($enlaceCon,$consulta);
			$dataFact = $respFactura->fetch_array(MYSQLI_ASSOC);			
			$siat_nombreSistema = $dataFact['nombre_sistema'];			
			$siat_codigoSistema=$dataFact['codigo_sistema'];
			$siat_tipo=$dataFact['tipo_sistema'];
			$siat_nit=intval($dataFact['nit']);
			$siat_razonSocial=$dataFact['razon_social'];
			$siat_tokenDelegado=$dataFact['token_delegado'];
// 		break;
// 		default:
// 			$siat_nombreSistema='';
// 			$siat_codigoSistema='';
// 			$siat_tipo='';
// 			$siat_nit=0;
// 			$siat_razonSocial='';
// 			$siat_tokenDelegado='';
// 		break;

// 	}
// }


