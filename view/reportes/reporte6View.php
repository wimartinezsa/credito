
<?php   
require_once ("../head/head.php");
?>



<div class="container-fluid"> 


<h4 class="text-center">INFORME DEL CLIENTE</h4>
<hr>
<div class="row">
    <div class="col-md-6">
        <label for="identificacion" class="form-label">Identificacion</label>
        <input type="number" id="identificacion" class="form-control" placeholder="Digite el numero de identificación del cliente">
     </div> 
       
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary" id="btn-generar-reporte" onclick="reporteHistorialCliente()">Generar Reporte</button>
        </div>  
</div>
<hr>
<h4>HISTORIAL DEL CLIENTE</h4>
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


</div>
  



<?php   
require_once ("../head/footer.php");
?>

 <script src="../reportes/reporte.js"></script>