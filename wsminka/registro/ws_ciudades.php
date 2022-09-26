<?php
// SERVICIO WEB PARA FACTURAS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {//verificamos  metodo conexion
    $datos = json_decode(file_get_contents("php://input"), true); 
    //Parametros de consulta
    $accion=NULL;
    if(isset($datos['accion'])&&isset($datos['sIdentificador'])&&isset($datos['sKey'])){//verificamos existencia de datos de conexion
        if($datos['sIdentificador']=="MinkaSw123*"&&$datos['sKey']=="rrf656nb2396k6g6x44434h56jzx5g6"){//verificamos datos de conexion
            $accion=$datos['accion']; //recibimos la accion
            // $codPersonal=$datos['codPersonal'];//recibimos el codigo personal
            $estado=0;
            $mensaje="";
            if($accion=="registrarCiudad"){//obtenemos las ciudades del cliente
                try{
                    if(isset($datos['idEmpresa'])&&isset($datos['nitEmpresa'])&&isset($datos['nombreCiudad'])&&isset($datos['codigoImpuestos'])&&isset($datos['direccionCiudad'])&&isset($datos['codigoActividadEconomica'])&&isset($datos['codigoExterno'])){
                        $idEmpresa=$datos['idEmpresa'];//recibimos el codigo personal
                        $nitEmpresa=$datos['nitEmpresa'];//recibimos el codigo personal
                        if(verificarExistenciaEmpresa($idEmpresa,$nitEmpresa)){
                            $nombreCiudad=$datos['nombreCiudad'];//recibimos el codigo personal
                            $codigoExterno=$datos['codigoExterno'];//recibimos el codigo personal
                            $codigoImpuestos=$datos['codigoImpuestos'];//recibimos el codigo personal
                            $direccionCiudad=$datos['direccionCiudad'];//recibimos el codigo personal
                            $codigoActividadEconomica=$datos['codigoActividadEconomica'];//recibimos el codigo personal
                            $lstPlanillasRetroactivo = registrarCiudad($idEmpresa,$nombreCiudad,$codigoImpuestos,$direccionCiudad,$codigoActividadEconomica,$codigoExterno);//llamamos a la funcion 
                            $totalComponentes=count($lstPlanillasRetroactivo);
                            $resultado=array(
                                "estado"=>1,
                                "mensaje"=>"Datos insertados correctamente", 
                                "lstResultado"=>$lstPlanillasRetroactivo, 
                                "totalComponentes"=>$totalComponentes
                                );
                        }else{
                            $resultado=array(
                            "estado"=>7,
                            "mensaje"=>"ERROR. IdEmpresa o nitEmpresa inexistente");
                        }
                    }else{
                        $resultado=array(
                        "estado"=>6,
                        "mensaje"=>"ERROR. Variables incompletos");
                    }
                }catch(Exception $e){
                   $resultado=array(
                    "estado"=>5,
                    "mensaje"=>"Hubo un error al momento de consultar BD");
                }
            }elseif($accion=="ObtenerDatosPersonal"){
                $DatosPersonal = ObtenerDatosPersonal($codPersonal);//llamamos a la funcion 
                $totalComponentes=count($DatosPersonal);
                $resultado=array(
                    "estado"=>true,
                    "mensaje"=>"Datos del Personal obtenida correctamente", 
                    "datosPersonal"=>$DatosPersonal, 
                    "totalComponentes"=>$totalComponentes
                    );
            }else{
                $resultado=array(
                    "estado"=>4,
                    "mensaje"=>"ERROR. No existe la Accion Solicitada.", 
                    "lstPlanillasRetroactivo"=>null, 
                    "totalComponentes"=>0
                    );
            }
        }else{
            $resultado=array(
                "estado"=>3,
                "mensaje"=>"ACCESO DENEGADO!. Credenciales Incorrectos.");
        }
    }else{
        $resultado=array(
                "estado"=>2,
                "mensaje"=>"ACCESO DENEGADO!. Usted no tiene permiso para ver este contenido.");
    }
    header('Content-type: application/json');
    echo json_encode($resultado); 
}else{
    $resultado=array(
                "estado"=>2,
                "mensaje"=>"ACCESO DENEGADO!. Usted no tiene permiso para ver este contenido.");
    header('Content-type: application/json');
    echo json_encode($resultado);
}


function registrarCiudad($idEmpresa,$nombreCiudad,$codigoImpuestos,$direccionCiudad,$codigoActividadEconomica,$codigoExterno){        
    require_once __DIR__.'/../conexionmysqli2.inc';
    $codigoCiudad=verificarExistenciaCiudadCodExterno($codigoExterno,$idEmpresa);
    if($codigoCiudad==0){
        $sql="INSERT INTO ciudades(descripcion,cod_impuestos,direccion,nombre_ciudad,siat_codigoActividad,cod_empresa,cod_externo) values('$nombreCiudad','$codigoImpuestos','$direccionCiudad','$nombreCiudad','$codigoActividadEconomica','$idEmpresa','$codigoExterno')";
        $respCons = mysqli_query($enlaceCon,$cons);
        $codigoCiudad=verificarExistenciaCiudadCodExterno($codigoExterno,$idEmpresa);
    }
    return $codigoCiudad;
}

function verificarExistenciaEmpresa($idEmpresa,$nitEmpresa){  
  require_once __DIR__.'../conexionmysqli2.inc';  
  $cons = "SELECT cod_empresa from datos_empresa where nit='$nitEmpresa' and cod_empresa='$idEmpresa'";
  $respCons = mysqli_query($enlaceCon,$cons);
  $value=false;
  while ($datCons = mysqli_fetch_array($respCons)) {
    $value=true;
  }
  return $value;
}

function verificarExistenciaCiudadCodExterno($codigoExterno,$idEmpresa){  
  require_once __DIR__.'/../conexionmysqli2.inc';  
  $cons = "SELECT codigo from ciudades where  cod_empresa='$idEmpresa' and cod_externo='$codigoExterno'";
  $respCons = mysqli_query($enlaceCon,$cons);
  $value=0;
  while ($datCons = mysqli_fetch_array($respCons)) {
    $value=$datCons['codigo'];
  }
  return $value;
}