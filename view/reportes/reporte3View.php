
<?php   
require_once '../../config.php'; 
require_once ("../head/head.php");
?>


<div class="container-fluid"> 


<h4 class="text-center">INFORME DE PRESTAMOS POR INTERVALO DE FECHAS</h4>
<hr>

<div class="row">
      <div class="col-md-12">
        <select id="sociedad" onchange="estadoSociedad()" class="form-control">
        <option value="">Seleccionar Sociedad</option>
    </select>
      </div>
</div>



<div class="row">
    <div class="col-md-4">
        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
        <input type="date" id="fecha_inicio" class="form-control">
     </div> 
      <div class="col-md-4">
        <label for="fecha_fin" class="form-label">Fecha Fin</label>
        <input type="date" id="fecha_fin" class="form-control">
      </div>  
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary" id="btn-generar-reporte" onclick="listarPrestamosPorFechas()">Generar Reporte</button>
        </div>  
</div>
<hr>

<table class="table" id="tabla-reporte2">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Sociedad</th>
      <th scope="col">Persona</th>
      <th scope="col">Ficha</th>
      <th scope="col">Fecha</th>
        <th scope="col">Tipo</th>
        <th scope="col">Interes</th>
        <th scope="col">Tiempo</th>
        <th scope="col">Valor Prestado</th>
        <th scope="col">Valor Futuro</th>
        <th scope="col">Valor Pagado</th>
        <th scope="col">Valor Pendiente</th>
        <th scope="col">Estado</th>
        

     
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<h4 id="total-gastos" class="text-center"></h4>

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