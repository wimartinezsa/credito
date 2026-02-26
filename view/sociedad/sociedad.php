<?php   
require_once ("../head/head.php");
?>



<div class="container-fluid">


<!-- Trigger button for Frm_procedimientos -->
<button type="button" class="btn btn-secondary mb-3" onclick="modalSociedades()">SOCIEDAD</button>

<br>
<table class="table" id="tabla-sociedades">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Sociedad</th>
      <th scope="col">Valor</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>



</div>

<!-- Modal Registrar Usuario-->
<div class="modal fade" id="modalSociedad" data-bs-target="#modalSociedad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Sociedades</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <form >
                    <input type="number" name="id_sociedad" class="form-control" id="id_sociedad" hidden readonly > 

                            <div class="mb-3">
                                <label class="form-label">Sociedad</label>
                                <input type="text" name="sociedad" class="form-control" id="sociedad" placeholder="Digite el nombre de la sociedad"> 
                           
                            </div>

                            <div class="mb-3">
                                <label for="valor" class="form-label">Valor</label>
                                <input type="number" name="valor" class="form-control" id="valor" placeholder="Digite el valor de la sociedad"> 
                            </div>

      </form>
      </div>
      <div class="modal-footer">
     
        <button type="button" name="btn_registrar" class="btn btn-primary" onclick="registrarSociedad()">Registrar</button>
        <button type="button" name="btn_actualizar" class="btn btn-success" onclick="actualizarSociedad()">Actualizar</button>
 
      </div>
    </div>
  </div>
</div>








<?php   
require_once ("../head/footer.php");
?>

<script src="../sociedad/sociedad.js"></script>