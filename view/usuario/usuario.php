

<?php  
session_start();
if(!isset($_SESSION['token'])){
    header("Location: http://localhost/creditos/index.php");
    exit;
} 
require_once ("../head/head.php");
?>



<div class="container-fluid"> 


<h4 class="text-center">Lista de clientes</h4>
<!-- Trigger button for Frm_procedimientos -->

<button type="button" class="btn btn-secondary mb-3" onclick="limpiarFormulario(); modalUsuario()">Nuevo Cliente</button>

<br>
<table class="table" id="tabla-usuarios">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Identificación</th>
      <th scope="col">Nombre</th>
      <th scope="col">Dirección</th>
      <th scope="col">Telefono</th>
      <th scope="col">Calificación</th>
      <th scope="col">Observación</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

</div>
  

<!-- Modal Registrar Usuario-->
<div class="modal fade" id="modalCliente" data-bs-target="#modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Clientes</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <form >
                    <input type="number" name="id_persona" class="form-control" id="id_persona" hidden readonly >
                 
                  <div class="mb-3">
                  <label for="identificacion" class="form-label">Identificacion</label>
                  <input type="number" name="identificacion" class="form-control" id="identificacion" placeholder="Digite la identificación">
                  </div>
                  <div class="mb-3">
                  <label for="nombres" class="form-label">Nombre</label>
                  <input type="text" name="nombres" class="form-control" id="nombres" placeholder="Digite el nombre">
                </div>
                <div class="mb-3">
                      <label for="direccion" class="form-label">Direccion</label>
                      <input type="text" name="direccion" class="form-control" id="direccion" placeholder="DIgite la Dirección">
                </div>

              <div class="mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Digite el Telefono">
              </div>
            <div class="mb-3">
                    <label for="calificacion" class="form-label">Calificación</label>
                   <select class="form-select" name="calificacion" id="calificacion">
                      <option value="">Seleccione una calificación</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                    </select>
              </div>
              
 <div class="mb-3">
                    <label for="observacion" class="form-label">Observación</label>
                   <textarea class="form-control" name="observacion" id="observacion" rows="3" placeholder="Digite una observación"></textarea>
              </div>
              


      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="limpiarFormulario()">Close</button>
        <button type="button" name="btn_registrar" class="btn btn-primary" onclick="guardarUsuario()">Registrar</button>
        <button type="button" name="btn_actualizar" class="btn btn-primary" onclick="actualizarUsuario()">Actualizar</button>
        
      </div>
    </div>
  </div>
</div>








<?php   
require_once ("../head/footer.php");
?>

 <script src="../usuario/usuario.js"></script>
 <script>

  // Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
   listarUsuario();
    console.log('usuario.js cargado. DataTable y modal listos.');
});

 </script>