<?php   
require_once ("../head/head.php");
?>




<div class="container-fluid">

<h4 class="text-center">Lista de Gastos</h4>
<!-- Trigger button for Frm_procedimientos -->
<button type="button" class="btn btn-secondary mb-3" onclick="modalGastos()">Nuevo Gasto</button>

<br>
<table class="table" id="tabla-gastos">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Sociedad</th>
      <th scope="col">Fecha</th>
      <th scope="col">Detalle</th>
      <th scope="col">Valor</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

</div>

<!-- Modal Registrar Usuario-->
<div class="modal fade" id="modalGasto" data-bs-target="#modalGasto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Gastos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <form >
                    <input type="number" name="id_gasto" class="form-control" id="id_gasto" hidden readonly> 

                            <div class="mb-3">
                                <label class="form-label">Sociedad</label>
                                <select class="form-select" name="sociedad" id="sociedad">
                              </select>
                            </div>

                        <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control" id="fecha">
                          </div>

                          <div class="mb-3">
                              <label for="detalle" class="form-label">Detalle</label>
                              <input type="text" name="detalle" class="form-control" id="detalle" placeholder="Digite el detalle del gasto">
                          </div>

                           <div class="mb-3">
                              <label for="valor" class="form-label">Valor</label>
                              <input type="number" name="valor" class="form-control" id="valor" placeholder="Digite el valor del gasto">
                          </div>
      </form>
      </div>
      <div class="modal-footer">
     
        <button type="button" name="btn_registrar" class="btn btn-primary" onclick="registrarGasto()">Registrar</button>
 
      </div>
    </div>
  </div>
</div>








<?php   
require_once ("../head/footer.php");
?>

<script src="../gastos/gasto.js"></script>