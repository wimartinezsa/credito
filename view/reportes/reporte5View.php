
<?php   
require_once ("../head/head.php");
?>



<div class="container-fluid"> 


<h4 class="text-center">INFORME DE PRESTAMOS POR FICHA</h4>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary" id="btn-generar-reporte" onclick="reporteCuotaVencidas()">Generar Reporte</button>
        </div>  
</div>
<hr>

<h4>Listado de cuotas vencidas</h4>
<table class="table" id="tabla-cuotas">
  <thead>
    <tr>
      <th scope="col">#</th>
       <th scope="col">Mes</th>
      <th scope="col">Fecha</th>
      <th scope="col">Valor</th>
      <th scope="col">Tipo</th>
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