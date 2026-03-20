
<?php  


require_once ("../head/head.php");
?>


<div class="container-fluid">

<h4 class="text-center">Administrar Creditos</h4>
<!-- Trigger button for Frm_procedimientos -->

<div class="row text-center m-3">
        <div class="col-3">
               <select class="form-select" name="sociedad" id="lista_sociedades" onchange="listarPrestamosId(this.value);disponibilidadSociedad(this.value);">
               </select>
         </div>
        <div class="col-3">
                   <button type="button" class="btn btn-secondary mb-3" onclick="modalPrestamos()">Nuevo Credito</button>

       </div>   

        <div class="col-6">
          <h4 id="disponible">Disponiblidad: </h4>
        </div> 
 </div>


<br>
<table class="table" id="tabla-prestamos">
  <thead>
    <tr>
      <th scope="col">#</th>
     
      <th scope="col">Cliente</th>
      <th scope="col">Tipo</th>
      <th scope="col">Fecha</th>
      <th scope="col">Tiempo</th>
      <th scope="col">V. Prestado</th>
         <th scope="col">Interes</th>
      <th scope="col">V. Futuro</th>   
      <th scope="col">V. Pagado</th>
      <th scope="col">V. Pendiente</th>
      <th scope="col">Estado</th>
        
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

</div>

<!-- Modal Registrar Usuario-->
<div class="modal fade" id="modalPrestamo" data-bs-target="#modalPrestamo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Creditos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <form >
                    <input type="number" name="id_prestamo" class="form-control" id="id_prestamo" hidden readonly> 

                <div class="row">



                <div class="col-6">
                           <div class="mb-3">
                            <label for="ficha" class="form-label">Sociedad</label>
                              <select class="form-select" name="sociedad" id="sociedad">
                              </select>
                        </div>
                </div>


                        <div class="col-6">
                           <div class="mb-3">
                                <label for="ficha" class="form-label">Ficha</label>
                                <input type="number" name="ficha" class="form-control" id="ficha" placeholder="Digite el codigo de">
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
                            <label for="fecha" class="form-label">Fecha </label>
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
                            <label for="tipo" class="form-label">Tipo de Credito</label>
                            <select class="form-select" name="tipo" id="tipo">
                                    <option value="" selected disabled>Seleccione el tipo de credito</option>
                                    <option value="mensual">Mensual</option>
                                    <option value="financiado">Financiado</option>
                            </select>
             
                </div>
                           
                        </div>
                    </div>


              <div class="row">
                       <div class="col-6">
                                <div class="mb-3">
                                    <label for="fiador" class="form-label">Fiador</label>
                                    <select class="form-select" name="fiador" id="fiador">
                                            <option value="">Seleccione un fiador</option>
                                    </select>
                                </div>
                        </div>

                         <div class="col-6">
                            <div class="mb-3">
                              <label for="estados" class="form-label">Estado del Credito</label>
                              <select id="estado" class="form-select">
                                  <option value="aprobado">Aprobado</option>
                                  <option value="negado">Negado</option>
                                  <option value="finalizado">Finalizado</option>
                              </select>
                          </div>
                      </div>
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




<!-- Modal Cuotas-->
<div class="modal fade" id="modalCuotas" data-bs-target="#modalCuotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Pagos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="number" id="cod_prestamo" hidden readonly>
              
        <div class="row">

            <div class="col-8">
              <table class="table" id="tabla-cuotas">
                      <thead>
                        <tr>
                          <th>#</th>
                          
                          <th>Fecha Pago</th>
                          <th>Valor</th>
                          <th>Tipo de Pago</th>
                          <th>Estado</th>
                         
                        </tr>
                      </thead>
                    
                      
        </table>

            </div>

         <div class="col-4">
           <div class="mb-2">
                              <label for="ficha" class="form-label">Mes</label>
                              <select class="form-select" name="sociedad" id="sociedad">
                              </select>
            </div>

            <div class="mb-2">
                              <label for="ficha" class="form-label">Valor</label>
                            <input type="number" name="valor_cuota" class="form-control" id="valor_cuota" placeholder="Digite el Valor a Pagar">              
            </div>

            <div class="mb-2">
                              <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                              <select class="form-select" name="tipo_pago" id="tipo_pago">
                              </select>
            </div>


            <div class="mb-2">
                              <button class="btn btn-primary">Registrar</button>
            </div>
                              
          
         </div>


        </div>
        
        
                 


      </div>
      <div class="modal-footer">
        <h4 id="valores"></h4>
      
       
      </div>
    </div>
  </div>
</div>


<!-- Modal Cuotas-->
<div class="modal fade" id="modalGarantia" data-bs-target="#modalCuotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Administrar Documentos de Garantía</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="number" id="codigo_prestamo" hidden readonly>
        <div class="row">
          <div class="col-4">
            <select class="form-select" name="tipo" id="tipo_garantia"></select>
        </div>
        <div class="col-5">
            <input type="file" id="archivo" name="archivo" accept="application/pdf" required>
        </div>
        <div class="col-3">
           <button type="button" class="btn btn-primary" id="btnSubirGarantia" onclick="subirGarantia()">Subir documento</button>
        </div>
       
          </div>
        </div>


               <table class="table" id="tabla-garantias">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>tipo</th>
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

<script src="/Creditos/view/prestamo/prestamo.js?v=2"></script>