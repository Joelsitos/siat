<?php
// require_once '../conexion.php';
// require_once 'configModule.php';
// require_once '../styles.php';
// require_once '../functions.php';
// require_once '../layouts/bodylogin2.php';
// $dbh = new Conexion();
require("../estilos_almacenes.inc");
$desde=date("Y-m", strtotime('-1 month'));
$desde.="-01";
$hasta= date("Y-m-t", strtotime($desde));

?>

<div class="content">
  <form id="form1" class="form-horizontal" action="facturas_venta_print.php" method="POST"  target="_blank">
  <div class="container-fluid">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header <?=$colorCard;?> card-header-icon">
              <div class="card-icon">                
              </div>
              <h4 class="card-title">Impresión de facturas</h4>
            </div>
            <div class="card-body">
              <div class="row">                     
                <label class="col-sm-2 col-form-label">Desde</label>
                <div class="col-sm-4">
                  <div class="form-group">
                    <div id="div_contenedor_fechaI">                              
                      <input type="date" class="form-control" name="fecha_desde" id="fecha_desde"  value="<?=$desde?>">  
                    </div>                                    
                   </div>
                </div>
                <label class="col-sm-2 col-form-label">Hasta</label>
                <div class="col-sm-4">
                  <div class="form-group">
                    <div id="div_contenedor_fechaH">                              
                      <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" value="<?=$hasta?>">
                    </div>
                  </div>
                </div>         
              </div><!--div fechas row-->
              <div class="row">                     
                <label class="col-sm-2 col-form-label">Rango</label>
                <div class="col-sm-4">
                  <div class="form-group">
                    <div id="div_contenedor_fechaI">                              
                      <input type="text" class="form-control" name="numero_rango" id="numero_rango"  placeholder="1-10">  
                    </div>                                    
                   </div>
                </div>
                      
              </div><!--div fechas row-->
              
          </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success">Generar</button>
      </div>
          </div>    
    </div>         
  </div>
  </form>
</div>

