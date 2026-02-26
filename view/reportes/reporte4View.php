
<?php   
require_once ("../head/head.php");
?>



<div class="container-fluid"> 


<h4 class="text-center">INFORME DE PRESTAMOS POR INTERVALO DE FECHAS</h4>
<hr>
<div class="row">
    <div class="col-md-6">
        <label for="ficha" class="form-label">Ficha</label>
        <input type="number" id="ficha" class="form-control">
     </div> 
       
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary" id="btn-generar-reporte" onclick="listarReporteFicha()">Generar Reporte</button>
        </div>  
</div>
<hr>

<table class="table" id="tabla-reporte">
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

<table class="table" id="tabla-cuotas">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">id_cuota</th>
      <th scope="col">Fecha</th>
       <th scope="col">Mes</th>
      <th scope="col">Valor</th>
      <th scope="col">Tipo</th>
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

 <script src="../reportes/reporte.js"></script>