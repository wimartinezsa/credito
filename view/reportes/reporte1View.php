

<?php  
require_once '../../config.php'; 
require_once ("../head/head.php");
?>



<div class="container-fluid"> 

<br>
<h4 class="text-center">ESTADO DE LA SOCIEDAD</h4>
<br>
<select id="sociedad" onchange="estadoSociedad()" class="form-control">
    <option value="">Seleccionar Sociedad</option>
   
</select>
<br>
<div class="container">

    <!-- flex container to align cards in a row -->
    <div id="estado-sociedad" class="d-flex flex-wrap" style="gap:1rem;"></div>
   


    


   

   






</div>






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