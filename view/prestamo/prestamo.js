
let modalPrestamo = null;
  //btn_registrar.style.display='block';
        //  btn_actualizar.style.display='none';
function modalPrestamos(){

         

        const el = document.getElementById('modalPrestamo');
        modalPrestamo = new bootstrap.Modal(el, { keyboard: false });
          listaClientes();
          actualizarBotonesModal();
        modalPrestamo.show();
      
}

function actualizarBotonesModal(){
     
    const id_prestamo = document.getElementById('id_prestamo').value;
    const btnRegistrar = document.querySelector('button[name="btn_registrar"]');
    const btnActualizar = document.querySelector('button[name="btn_actualizar"]');
    
    if(id_prestamo && id_prestamo.trim() !== ''){
        // Estamos actualizando
        btnRegistrar.style.display = 'none';
        btnActualizar.style.display = 'block';
    } else {
        // Estamos registrando
        btnRegistrar.style.display = 'block';
        btnActualizar.style.display = 'none';
    }
}




let modalCuotas = null;

function modalCuotasPrestamo(){
        const el = document.getElementById('modalCuotas');
        modalCuotas = new bootstrap.Modal(el, { keyboard: false });
        modalCuotas.show();
}




let dataTableInstance = null;

function listaClientes(){
    fetch("../usuario/listarUsuario.php", {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        
    
        // Llenar filas con datos
        data.forEach(usuario => {
            const select = document.getElementById("cliente");
            const option = document.createElement("option");
            option.value = usuario.id_persona;
            option.textContent = `${usuario.nombres} - ${usuario.identificacion}`;
            select.appendChild(option);
        });
        
      
         
    })
    .catch(err => {
        console.error(err);
       alert('Error al listar: ' + err);
    });
}



function listaPrestamo(){
    fetch("./listarPrestamo.php", {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        const tabla = document.getElementById("tabla-prestamos");
        if (!tabla) return;
        
        // Destruir DataTable anterior si existe (ANTES de modificar el DOM)
        if (dataTableInstance) {
            dataTableInstance.destroy();
            dataTableInstance = null;
        }
        
        // Limpiar solo el tbody, no toda la tabla
        let tbody = tabla.querySelector('tbody');
        if (!tbody) {
            tbody = document.createElement('tbody');
            tabla.appendChild(tbody);
        }
        tbody.innerHTML = "";
        
        // Llenar filas con datos
        data.forEach(prestamo => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${prestamo.ficha}</td>
                <td>${prestamo.nombres}</td>
                <td>${prestamo.fecha_prestamo}</td>
                <td>${prestamo.tiempo}</td>
                <td>${prestamo.valor_prestado}</td>
                 <td>${prestamo.futuro}</td>
                 <td>${prestamo.pagado}</td>
                 <td>${prestamo.pendiente}</td>
             
                <td>
                <button class="btn btn-sm btn-primary" onclick="buscarPrestamo(${prestamo.id_prestamo})">Actualizar</button>
                   <button class="btn btn-sm btn-success" onclick="verPagos(${prestamo.id_prestamo})">Ver Pagos</button>
                </td>
            `;
        });
        
        // Inicializar DataTable DESPUÉS de llenar los datos
        dataTableInstance = $('#tabla-usuarios').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true
        });
         
    })
    .catch(err => {
        console.error(err);
       alert('Error al listar: ' + err);
    });
}


function guardarPrestamo(){
    // Implementar lógica para guardar préstamo
    let datos= new URLSearchParams();
    datos.append('ficha',document.getElementById('ficha').value);
    datos.append('cliente',document.getElementById('cliente').value);
    datos.append('fecha',document.getElementById('fecha').value);
    datos.append('tiempo',document.getElementById('tiempo').value);
    datos.append('valor',document.getElementById('valor_prestado').value);
    datos.append('interes',document.getElementById('interes').value);
    datos.append('tipo',document.getElementById('tipo').value);

    fetch("./registrarPrestamo.php", {
        method: 'POST',
        body:datos,
    })
    .then(response => response.text())
    .then(text => {
        console.log(text);
       //limpiarFormulario();
      listaPrestamo();
      modalPrestamo.hide();
    })
    .catch(err => {
        console.error(err);
        alert('Error al guardar: ' + err);
    });
}

function verPagos(id_prestamo){
    document.getElementById("cod_prestamo").value=id_prestamo;
    listarCuotas(id_prestamo);
    modalCuotasPrestamo();
}


function buscarPrestamo(id_prestamo){
    fetch(`./buscarPrestamo.php?id_prestamo=${id_prestamo}`, {
        method: 'GET',      
    })
    .then(response => response.json())
    .then(data => {
      
        document.getElementById("id_prestamo").value=data.id_prestamo;
        document.getElementById("ficha").value=data.ficha;
        document.getElementById("cliente").value=data.persona;
        document.getElementById("fecha").value=data.fecha_prestamo;
        document.getElementById("tiempo").value=data.tiempo;
        document.getElementById("valor_prestado").value=data.valor_prestado;
        document.getElementById("interes").value=data.interes;
        document.getElementById("tipo").value=data.tipo;

       modalPrestamos();
    })
    .catch(err => {
        console.error(err);
       alert('Error al buscar: ' + err);
    });
}


function actualizarPrestamo(){
    let datos= new URLSearchParams();
    datos.append('id_prestamo',document.getElementById('id_prestamo').value);
    datos.append('ficha',document.getElementById('ficha').value);
    datos.append('cliente',document.getElementById('cliente').value);
    datos.append('fecha',document.getElementById('fecha').value);
    datos.append('tiempo',document.getElementById('tiempo').value);
    datos.append('valor',document.getElementById('valor_prestado').value);
    datos.append('interes',document.getElementById('interes').value);
    datos.append('tipo',document.getElementById('tipo').value);

    fetch("./actualizarPrestamo.php", {
        method: 'POST',
        body:datos,
    })
    .then(response => response.text())
    .then(text => {
        console.log(text);
       //limpiarFormulario();
      listaPrestamo();
      modalPrestamo.hide();
    })
    .catch(err => {
        console.error(err);
        alert('Error al actualizar: ' + err);
    });
}

function limpiarFormulario(){
    document.getElementById("id_prestamo").value="";
    document.getElementById("cliente").value="";    
    document.getElementById("fecha").value="";
    document.getElementById("tiempo").value="";
    document.getElementById("valor_prestado").value="";
    document.getElementById("interes").value="";
    document.getElementById("tipo").value="";
  
}




function listarCuotas(id_prestamo){

    fetch(`../cuota/listarCuotas.php?id_prestamo=${id_prestamo}`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {

        const tabla = document.getElementById("tabla-cuotas");
        if (!tabla) return; 

        // Destruir DataTable anterior
        if (typeof dataTableInstance !== "undefined" && dataTableInstance) {
            dataTableInstance.destroy();
            dataTableInstance = null;
        }   

        let tbody = tabla.querySelector('tbody');   
        if (!tbody) {
            tbody = document.createElement('tbody');
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";
        let valor_futuro=0;
        let valor_pagado=0;
         let valor_pendiente=0;
        data.forEach(cuota => { 
            if(cuota.estado==='pagado'){
                valor_pagado+=cuota.valor;
            }
            if(cuota.estado==='pendiente'){
                valor_pendiente+=cuota.valor;
            }
            const row = tbody.insertRow();
            valor_futuro +=cuota.valor;
            row.innerHTML = `
                <td>${cuota.id_cuota}</td>
                <td>${cuota.mes}</td>
                <td>${cuota.fecha_cuota}</td>
                <td>${cuota.valor}</td>
                <td>${cuota.tipo}</td>
                <td>
                    ${cuota.estado ==='pendiente' 
                        ? `<a onclick="pagarCuota(${cuota.id_cuota})" alt="Pagar Cuota" class="btn btn-sm btn-warning">
                             ${cuota.estado}
                           </a>`
                        : `<a  onclick="devolucionCuota(${cuota.id_cuota})" alt="Devolución Cuota" 
                             class="btn btn-sm btn-success">
                             ${cuota.estado}
                           </a>`
                    }
                </td>
            `;
        });
        document.getElementById('valores').innerHTML='V. Futuro: $'+valor_futuro+ '  /  V. Pagado: $'+valor_pagado+ '  /  V. Pendiente: $'+valor_pendiente;

        dataTableInstance = $('#tabla-cuotas').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true
        }); 
    })
    .catch(err => {
        console.error(err);
        alert('Error al listar: ' + err);
    }); 
}

function pagarCuota(id_cuota){
    if (!confirm('¿Confirma que desea pagar esta cuota?')) return;
    fetch(`../cuota/pagarCuota.php?id_cuota=${id_cuota}`, {
        method: 'GET',
    })
    .then(response => response.text())
    .then(text => {
        listaPrestamo();
      listarCuotas(document.getElementById("cod_prestamo").value);
        alert(text);
    });

 
}

function devolucionCuota(id_cuota){

if (!confirm('¿Confirma que desea hacer devolución de esta cuota?')) return;

     fetch(`../cuota/devolucionCuota.php?id_cuota=${id_cuota}`, {
        method: 'GET',
    })
    .then(response => response.text())
    .then(text => {
      listaPrestamo();
      listarCuotas(document.getElementById("cod_prestamo").value);
        alert(text);
    });
    
}
// Exponer funciones globalmente para manejadores inline
window.modalPrestamos = modalPrestamos;
window.listaPrestamo = listaPrestamo;
window.listaClientes = listaClientes;
window.guardarPrestamo = guardarPrestamo;
window.pagarCuota=pagarCuota;
window.devolucionCuota=devolucionCuota;
window.modalCuotasPrestamo = modalCuotasPrestamo;

// Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
   listaPrestamo();
  
    console.log('prestamos.js cargado. DataTable y modal listos.');
});
