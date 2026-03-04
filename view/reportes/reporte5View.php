
<?php   
session_start();
if(!isset($_SESSION['token'])){
    header("Location: http://localhost/creditos/index.php");
    exit;
}
require_once ("../head/head.php");
?>



<div class="container-fluid"> 


<h4 class="text-center">INFORME DE CUOTAS VENCIDAS</h4>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary" id="btn-generar-reporte" onclick="reporteCuotaVencidas()">Generar Reporte</button>
        </div>  
</div>
<hr>

<h4>Listado de cuotas vencidas</h4>
<table class="table" id="tabla-reporte">
  <thead>
    <tr>
      <th scope="col">Ficha</th>
      <th scope="col">Cliente</th>
      <th scope="col">Telefono</th>
      <th scope="col">Prestamo</th>
       <th scope="col">Mes</th>
      <th scope="col">Fecha Pago</th>
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