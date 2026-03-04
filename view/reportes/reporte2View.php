
<?php   
session_start();
if(!isset($_SESSION['token'])){
    header("Location: http://localhost/creditos/index.php");
    exit;
}
require_once ("../head/head.php");
?>



<div class="container-fluid"> 


<h4 class="text-center">INFORME DETALLADO DE GASTOS POR INTERVALO DE FECHAS</h4>
<hr>
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
            <button class="btn btn-primary" id="btn-generar-reporte" onclick="gastoPorFecha()">Generar Reporte</button>
        </div>  
</div>
<hr>

<table class="table" id="tabla-reporte2">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Sociedad</th>
      <th scope="col">Fecha</th>
      <th scope="col">Detalle</th>
      <th scope="col">Valor</th>
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

 <script src="../reportes/reporte.js"></script>