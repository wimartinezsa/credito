
<?php   
require_once '../../config.php'; 
require_once ("../head/head.php");
?>



<div class="container-fluid"> 


<h4 class="text-center">INFORME DE MOVIMIENTOS</h4>
<hr>
<br>

<br>
<div class="row">
  <div class="col-md-6 d-flex align-items-end">
    <select id="sociedad"  class="form-control">
    <option value="">Seleccionar Sociedad</option>
   
</select>

  </div>
    
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary" id="btn-generar-reporte" onclick="listarMovimientosPorSociedad()">Generar Reporte</button>
        </div>  
</div>
<hr>
<h4>CREDITOS NEGADOS</h4>
<table class="table" id="tabla-reporte">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Fecha</th>
      <th scope="col">Tipo</th>
      <th scope="col">Valor</th>
      <th scope="col">Caja</th>
        <th scope="col">Sociedad</th>
        <th scope="col">Detalle</th>
      
    
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>


</div>
  



<?php   
require_once ("../head/footer.php");
?>


<script>
    const BASE_URL = "<?= BASE_URL?>";
</script>

<script src="<?= BASE_URL ?>view/js/peticiones.js"></script>

 <script src="<?= BASE_URL ?>view/reportes/reporte.js"></script>

 <script>

  listarSociedades();
 </script>