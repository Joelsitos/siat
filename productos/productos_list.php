<script type="text/javascript">
 function activar_input_salida_almacen(index){
  var check=document.getElementById("factura_seleccionada"+index);
  if(check.checked){
    document.getElementById("factura_seleccionada_s"+index).value=1;
  }else{
    document.getElementById("factura_seleccionada_s"+index).value=0;
  }
}
</script>

<?php //ESTADO FINALIZADO
require("../conexionmysqli.inc");
require("../estilos_almacenes.inc");
$globalIdEntidad=$_COOKIE['globalIdEntidad'];
?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-icon">
            <div class="card-icon">
              <i class="material-icons">account_balance</i>
            </div>
            <h4 class="card-title"><b>MIS PRODUCTOS</b></h4>
          </div>
          <div class="card-body">  
            <div class="table-responsive">
              <table id="tablePaginatorHeaderFooter" class="table table-bordered table-condensed table-striped " style="width:100%">
                <thead>                  
                  <tr class='bg-dark text-white'>
                    <th>Codigo</th>
                    <th>Descripción</th>
                    <th>Cod. Producto(SIAT)</th>
                    <th>Unidad Producto(SIAT)</th>
                    <th></th>
                  </tr>                                  
                </thead>
                <tbody>
                  <?php
                  $index=0;
                  $cod_tipoEmision=2;//tipo emision OFFLINE
                   $sql="SELECT codigo_material,descripcion_material,siat_codigoProducto,siat_unidadProducto from productos where cod_entidad=$globalIdEntidad and estado=1";
                  // echo $sql;
                  $resp=mysqli_query($enlaceCon,$sql);
                  while($row=mysqli_fetch_array($resp)){                     
                    $codigo_material=$row['codigo_material'];
                    $descripcion_material=$row['descripcion_material'];
                    $siat_codigoProducto=$row['siat_codigoProducto'];
                    $siat_unidadProducto=$row['siat_unidadProducto'];                    
                      $index++;
                      ?>
                    <tr>                      
                      <td class="text-center small"><?=$codigo_material;?></td>
                      <td class="text-left small"><?=$descripcion_material;?></td>
                      <td class="text-left small"><?=$siat_codigoProducto;?></td>
                      <td class="text-center small"><?=$siat_unidadProducto;?></td>                      
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
          <div class="card-footer">
            <button class="btn btn-sm btn-success">Sincronizar</button>
          </div>          
        </div>
      </div>
    </div>  
  </div>
</div>

<script type="text/javascript">
    function valida(f) {
        var ok = true;
            // Swal.fire("Informativo!","PROCESANDO..", "warning");
            $(".cargar-ajax").removeClass("d-none");
            $("#texto_ajax_titulo").html("Procesando datos..");
        return ok;
    }
</script>