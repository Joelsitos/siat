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
$globalEntidad=$_COOKIE['globalIdEntidad'];
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
            <h4 class="card-title"><b>MIS SUCURSALES</b></h4>            
          </div>
          <div class="card-body">  
            <div class="table-responsive">
              <table id="tablePaginatorHeaderFooter" class="table table-bordered table-condensed table-striped " style="width:100%">
                <thead>                  
                    <tr class='bg-dark text-white'>
                      <th>Codigo</th>
                      <th>Sucursal</th>
                      <th>Dirección</th>
                      <th>Cod Impuestos<br></th>
                      <th>Actividad</th>
                      <th></th></tr>
                </thead>
                <tbody>
                  <?php
                  $index=0;
                  $cod_tipoEmision=2;//tipo emision OFFLINE
                   $sql="SELECT c.cod_impuestos,c.cod_ciudad,c.descripcion,c.direccion,c.siat_codigoActividad from ciudades c where c.cod_entidad=$globalEntidad";
                  // echo $sql;
                  $resp=mysqli_query($enlaceCon,$sql);
                  while($row=mysqli_fetch_array($resp)){ 
                    // echo "***";
                    $cod_ciudad=$row['cod_ciudad'];
                    $descripcion=$row['descripcion'];
                    $direccion=$row['direccion'];
                    $cod_impuestos=$row['cod_impuestos'];
                    $siat_codigoActividad=$row['siat_codigoActividad'];
                    $index++;
                      ?>
                    <tr>
                      <!-- <td class="td-actions text-right">                      
                        <input type="checkbox"  data-toggle="toggle" title="Seleccionar" id="input_seleccionado<?=$index?>" name="input_seleccionado<?=$index?>" >
                      </td> -->
                      <td class="text-center small"><?=$cod_ciudad;?></td>
                      <td class="text-left small"><?=$descripcion;?></td>
                      <td class="text-left small"><?=$direccion;?></td>
                      <td class="text-center small"><?=$cod_impuestos;?></td>
                      <td class="text-left small"><?=$siat_codigoActividad;?></td>
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