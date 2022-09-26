<script type="text/javascript">
 
</script>

<?php //ESTADO FINALIZADO
require("../conexionmysqli.inc");
require("../estilos_almacenes.inc");
$globalEntidad=$_COOKIE['globalIdEntidad'];
?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-icon">
            <div class="card-icon">              
              <i class="material-icons">account_balance_wallet</i>
            </div>
            <h4 class="card-title"><b>MIS CREDENCIALES</b></h4>            
          </div>
          <div class="card-body">  
            <div class="table-responsive">
              <table id="tablePaginatorHeaderFooter" class="table table-bordered table-condensed table-striped " style="width:100%">
                <thead>                  
                    <tr class='bg-dark text-white'>
                      <th>-</th>
                      <th>Nombre Sis</th>
                      <th>Codigo Sis</th>
                      <th>Nit</th>
                      <th>Razon Social</th>
                      <!-- <th>Token</th> -->
                      <th>Fecha Limite</th>
                      <th>Estado</th>
                      <th></th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                  $index=0;
                  $cod_tipoEmision=2;//tipo emision OFFLINE
                   $sql="SELECT id,nombre_sistema,codigo_sistema,tipo_sistema,nit,razon_social,token_delegado,fecha_limite,cod_estado from siat_credenciales where cod_entidad=$globalEntidad
                    order by fecha_limite";
                  // echo $sql;
                  $resp=mysqli_query($enlaceCon,$sql);
                  while($row=mysqli_fetch_array($resp)){ 
                    // echo "***";
                    $id=$row['id'];
                    $nombre_sistema=$row['nombre_sistema'];
                    $codigo_sistema=$row['codigo_sistema'];
                    $tipo_sistema=$row['tipo_sistema'];
                    $nit=$row['nit'];
                    $razon_social=$row['razon_social'];
                    $token_delegado=$row['token_delegado'];
                    $fecha_limite=$row['fecha_limite'];
                    $cod_estado=$row['cod_estado'];
                    switch ($cod_estado) {
                      case 1://activo
                      $nombre_estado="Activo";
                      $label="style='color:green;'";
                      break;
                      case 2://registro       
                      $nombre_estado="Registrado";                 
                      $label="style='color:blue;'";
                      break;
                      case 3://vencido          
                      $nombre_estado="Inactivo";              
                      $label="style='color:red;'";
                      break;
                    }                    
                    $index++;
                      ?>
                    <tr>                      
                      <td class="text-center small"><?=$index;?></td>
                      <td class="text-left small"><?=$nombre_sistema;?>(<?=$tipo_sistema?>)</td>
                      <td class="text-left small"><?=$codigo_sistema;?></td>
                      <td class="text-center small"><?=$nit;?></td>
                      <td class="text-left small"><?=$razon_social;?></td>
                      <!-- <td class="text-left small"><?=$token_delegado;?></td> -->
                      <td class="text-left small"><?=$fecha_limite;?></td>
                      <td class="text-left small" <?=$label?>><b><?=$nombre_estado;?></b></td>
                      <td class="td-actions">                        
                        <a href='#' class="btn btn-warning btn-sm"><i class="material-icons">edit</i>Editar</a>
                        <a href='#' class="btn btn-danger btn-sm"><i class="material-icons">delete</i>Borrar</a>
                      </td>
                    </tr>
                    <?php   
                    
                  }?>
                </tbody>
              </table>              
            </div>
          </div>
          <div class="card-footer ">           
            <button class="btn btn-sm btn-success" onClick="location.href='credenciales_from.php?codigo=0'">Nuevo</button>
          </div>
        </div>
      </div>
    </div>  
  </div>
</div>

