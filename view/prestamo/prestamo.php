
<?php  

require_once '../../config.php'; 
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
                       <div class="col-12">
                                <div class="mb-3">
                                    <label for="fiador" class="form-label">Fiador</label>
                                    <select class="form-select" name="fiador" id="fiador">
                                            <option value="">Seleccione un fiador</option>
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
              <button class="btn btn-primary" onclick="adminNuevaCuota();">Nueva Cuota</button>
        <div class="row">
            <div class="col-12">
              <table class="table" id="tabla-cuotas">
                      <thead>
                        <tr>
                          <th>#</th>
                          
                          <th>Fecha Pago</th>
                          <th>Valor</th>
                          <th>Tipo de Pago</th>
                          <th>Estado</th>
                          <th>Admin.</th>
                         
                        </tr>
                      </thead>
                    
                      
        </table>

            </div>

        </div>

          <div class="row">
            <div class="col-12 text-center" >
              <button class="btn btn-primary" onclick="finalizarPrestamo()">Finalizar el Credito</button>
            </div>
            
        </div>
        
        
                 


      </div>
      <div class="modal-footer">
        <h4 id="valores"></h4>
      
       
      </div>
    </div>
  </div>
</div>




<!-- Pagar Cuotas-->
<div class="modal fade" id="modalPagarCuotas" data-bs-target="#modalCuotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="titleModalPagoCuota">Modificar Cuota</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="number" id="id_cuota_pago" hidden readonly>
              
        <div class="row">


         <div class="col-12">
           <div class="mb-2">
                              <label for="valor_pagado" class="form-label">Valor Pagao</label>
                              <input type="number" name="valor_pagado" class="form-control" id="valor_pagado" placeholder="Digite el numero de cuota">              
         
            </div>
            <div class="mb-2">
                              <label for="fecha_pagado" class="form-label">Fecha del Recuado</label>
                            <input type="date" name="fecha_recaudo" class="form-control" id="fecha_recaudo" placeholder="Digite la fecha del recaudo">              
            </div>

           


            <div class="mb-2">
              <button class="btn btn-primary" onclick="registrarPagoCuota()" id="btn_pagar_cuota">Registrar</button>
             
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
<div class="modal fade" id="modalAdminCuotas" data-bs-target="#modalCuotas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="titleModalAdminCuotas">Modificar Cuota</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="number" id="codigo_cuota" hidden readonly>
              
        <div class="row">


         <div class="col-12">
           <div class="mb-2">
                              <label for="nro_pago" class="form-label">Nro. Pago</label>
                              <input type="number" name="nro_pago" class="form-control" id="nro_pago" placeholder="Digite el numero de cuota">              
         
            </div>
            <div class="mb-2">
                              <label for="fecha" class="form-label">Fecha de Pago</label>
                            <input type="date" name="fecha_pago" class="form-control" id="fecha_pago" placeholder="Digite la fecha de pago">              
            </div>

            <div class="mb-2">
                              <label for="ficha" class="form-label">Valor</label>
                            <input type="number" name="valor_pago" class="form-control" id="valor_pago" placeholder="Digite el Valor a Pagar">              
            </div>

            <div class="mb-2">
                              <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                              <select class="form-select" name="tipo_pago" id="tipo_pago">
                                <option value="cuota_fija">Cuota Fija</option>
                                <option value="interes_mensual">Interes Mesual</option>
                                  <option value="capital">Capital</option>
                              </select>
            </div>


            <div class="mb-2">
              <button class="btn btn-primary" onclick="modificarCuota()" id="btn_modificar_cuota">Modificar</button>
              <button class="btn btn-primary" onclick="registarNuevaCuota()" id="btn_registrar_cuota">Registrar</button>
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

<script>
    const BASE_URL = "<?= BASE_URL?>";
</script>

<script src="<?= BASE_URL ?>view/js/peticiones.js"></script>
<script src="<?= BASE_URL ?>view/prestamo/prestamo.js"></script>