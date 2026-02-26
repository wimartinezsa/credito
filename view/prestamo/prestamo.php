
<?php   
require_once ("../head/head.php");
?>





<div class="container-fluid">
<!-- Trigger button for Frm_procedimientos -->
<button type="button" class="btn btn-secondary mb-3" onclick="modalPrestamos()">PRESTAMO</button>

<br>
<table class="table" id="tabla-prestamos">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Sociedad</th>
      <th scope="col">Cliente</th>
      <th scope="col">Tipo</th>
      <th scope="col">Fecha</th>
      <th scope="col">Tiempo</th>
      <th scope="col">V. Prestado</th>
      <th scope="col">V. Futuro</th>   
      <th scope="col">V. Pagado</th>
      <th scope="col">V. Pendiente</th>
        
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

</div>

<!-- Modal Registrar Usuario-->
<div class="modal fade" id="modalPrestamo" data-bs-target="#modalPrestamo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Prestamos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <form >
                    <input type="number" name="id_prestamo" class="form-control" id="id_prestamo" hidden readonly> 

                <div class="row">
                       <div class="col-6">
                             <div class="mb-3">
                                <label class="form-label">Sociedad</label>
                                <select class="form-select" name="sociedad" id="sociedad">
                              </select>
                            </div>
                        </div>
                        <div class="col-6">
                           <div class="mb-3">
                                <label for="ficha" class="form-label">Ficha</label>
                                <input type="number" name="ficha" class="form-control" id="ficha">
                          </div>
                        </div>
                  </div>

                    <div class="row">
                       <div class="col-6">
                           <div class="mb-3">
                              <label for="Cliente" class="form-label">Cliente</label>
                              <select class="form-select" name="cliente" id="cliente">
                                <option value="">Seleccione un cliente</option>
                              </select>
                          </div>
                            
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                          <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" id="fecha">
                          </div>
                          </div>
                          
                        </div>
                    </div>

                 

                     <div class="row">
                       <div class="col-6">
                            <div class="mb-3">
                              <label for="tiempo" class="form-label">Tiempo</label>
                              <input type="text" name="tiempo" class="form-control" id="tiempo" placeholder="Digite el tiempo  en meses">
                          </div>
                        
                        </div>
                        <div class="col-6">
                              <div class="mb-3">
                              <label for="valor_prestado" class="form-label">Valor Prestado</label>
                              <input type="number" name="valor_prestado" class="form-control" id="valor_prestado" placeholder="Digite el valor prestado">
                          </div>
                        </div>
                     
                     </div>


                

                  <div class="row">
                       <div class="col-6">
                          <div class="mb-3">
                            <label for="interes" class="form-label">Interes</label>
                              <input type="number" name="interes" class="form-control" id="interes" placeholder="Digite el interes">
                          </div>
                           
                        </div>
                        <div class="col-6">
                           <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Prestamo</label>
                            <select class="form-select" name="tipo" id="tipo">
                                    <option value="mensual">Mensual</option>
                                    <option value="financiado">Financiado</option>
                            </select>
             
                </div>
                           
                        </div>
                    </div>

                         <div class="mb-3">
                            <label for="fiador" class="form-label">Fiador</label>
                            <select class="form-select" name="fiador" id="fiador">
                                    <option value="">Seleccione un fiador</option>
                            </select>
             
                </div>


      </form>
      </div>
      <div class="modal-footer">
     
        <button type="button" name="btn_registrar" class="btn btn-primary" onclick="guardarPrestamo()">Registrar</button>
        <button type="button" name="btn_actualizar" class="btn btn-primary" onclick="actualizarPrestamo()">Actualizar</button>
        
      </div>
    </div>
  </div>
</div>




<!-- Modal Registrar Usuario-->
<div class="modal fade" id="modalCuotas" data-bs-target="#modalCuotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Pagos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="number" id="cod_prestamo" hidden readonly>
               <table class="table" id="tabla-cuotas">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Mes</th>
                          <th>Fecha</th>
                          <th>Valor</th>
                          <th>Tipo de Pago</th>
                          <th>Estado</th>
                         
                        </tr>
                      </thead>
                    
                      
               </table>
                 


      </div>
      <div class="modal-footer">
        <h4 id="valores"></h4>
      
       
      </div>
    </div>
  </div>
</div>







<?php   
require_once ("../head/footer.php");
?>

<script src="../prestamo/prestamo.js"></script>