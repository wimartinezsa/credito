
let modalPrestamo = null;

function modalPrestamos(){
        const el = document.getElementById('modalPrestamo');
        modalPrestamo = new bootstrap.Modal(el, { keyboard: false });
        limpiarFormulario();
        document.getElementById("id_prestamo").value="";
        listaClientes();
        listarSociedades();
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

let modalGarantia = null;

function modalAdminGarantia(id_prestado){
        const el = document.getElementById('modalGarantia');
        modalGarantia = new bootstrap.Modal(el, { keyboard: false });
        modalGarantia.show();
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
            const selectCliente = document.getElementById("cliente");
            const selectFiador = document.getElementById("fiador");
            const option = document.createElement("option");
            option.value = usuario.id_persona;
            option.textContent = `${usuario.nombres} - ${usuario.identificacion}`;
            selectCliente.appendChild(option);
            selectFiador.appendChild(option.cloneNode(true));
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
    .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.json();
    })
    .then(data => {
       
        if (!data) return;
        
      
        
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
                <td>${prestamo.sociedad}</td>
                <td>${prestamo.nombres}</td>
                <td>${prestamo.tipo}</td>
                <td>${prestamo.fecha_prestamo}</td>
                <td>${prestamo.tiempo}</td>
                <td>${prestamo.valor_prestado}</td>
                 <td>${prestamo.futuro}</td>
                 <td>${prestamo.pagado}</td>
                 <td>${prestamo.pendiente}</td>
             
                <td>
                <button class="btn btn-sm btn-primary" onclick="buscarPrestamo(${prestamo.id_prestamo})">Actualizar</button>
                <button class="btn btn-sm btn-success" onclick="verPagos(${prestamo.id_prestamo})">Ver Pagos</button>
                <button class="btn btn-sm btn-danger" onclick="modalAdminGarantia(${prestamo.id_prestamo})">Ver Garantía</button>
                
                </td>
            `;
        });
        
        // Inicializar DataTable DESPUÉS de llenar los datos
        dataTableInstance = $('#tabla-prestamos').DataTable({
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
    datos.append('sociedad',document.getElementById('sociedad').value);
    datos.append('ficha',document.getElementById('ficha').value);
    datos.append('cliente',document.getElementById('cliente').value);
    datos.append('fecha',document.getElementById('fecha').value);
    datos.append('tiempo',document.getElementById('tiempo').value);
    datos.append('valor',document.getElementById('valor_prestado').value);
    datos.append('interes',document.getElementById('interes').value);
    datos.append('tipo',document.getElementById('tipo').value);
    datos.append('fiador',document.getElementById('fiador').value);

    fetch("./registrarPrestamo.php", {
        method: 'POST',
        body:datos,
    })
     .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.text();
    })
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


async function buscarPrestamo(id_prestamo){
    document.getElementById("id_prestamo").value="";
   await  listarSociedades();
    await listaClientes();
    fetch(`./buscarPrestamo.php?id_prestamo=${id_prestamo}`, {
        method: 'GET',      
    })
    .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.json();
    })
    .then(data =>  {
      
        document.getElementById("id_prestamo").value=data.id_prestamo;
        document.getElementById("sociedad").value=data.sociedad;
        document.getElementById("ficha").value=data.ficha;
        document.getElementById("cliente").value=data.persona;
        document.getElementById("fecha").value=data.fecha_prestamo;
        document.getElementById("tiempo").value=data.tiempo;
        document.getElementById("valor_prestado").value=data.valor_prestado;
        document.getElementById("interes").value=data.interes;
        document.getElementById("tipo").value=data.tipo;
        document.getElementById("fiador").value=data.fiador;

        const el = document.getElementById('modalPrestamo');
        modalPrestamo = new bootstrap.Modal(el, { keyboard: false });
       

        actualizarBotonesModal();
        modalPrestamo.show();
    })
    .catch(err => {
        console.error(err);
       alert('Error al buscar: ' + err);
    });
}


function actualizarPrestamo(){
    let datos= new URLSearchParams();
    datos.append('id_prestamo',document.getElementById('id_prestamo').value);
    datos.append('sociedad',document.getElementById('sociedad').value);
    datos.append('ficha',document.getElementById('ficha').value);
    datos.append('cliente',document.getElementById('cliente').value);
    datos.append('fecha',document.getElementById('fecha').value);
    datos.append('tiempo',document.getElementById('tiempo').value);
    datos.append('valor',document.getElementById('valor_prestado').value);
    datos.append('interes',document.getElementById('interes').value);
    datos.append('tipo',document.getElementById('tipo').value);
    datos.append('fiador',document.getElementById('fiador').value);

    fetch("./actualizarPrestamo.php", {
        method: 'POST',
        body:datos,
    })
    .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.text();
    })
    .then(text => {
       // console.log(text);
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
    document.getElementById("ficha").value="";
    document.getElementById("sociedad").value="";
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
     .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.json();
    })
    .then(data => {
        // early checks
        if (!data) return;
        if (!Array.isArray(data)) {
            console.warn('Respuesta inesperada de listarCuotas.php', data);
            return;
        }

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
     .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.text();
    })
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
     .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.text();
    })
    .then(text => {
      listaPrestamo();
      listarCuotas(document.getElementById("cod_prestamo").value);
        alert(text);
    });
    
}


function listarSociedades(){
    fetch(`../sociedad/listarSociedad.php`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
       
        const select = document.getElementById("sociedad");

        // 🔹 Limpiar el select antes de llenarlo
        select.innerHTML = "";

        // 🔹 (Opcional) Agregar opción por defecto
        //const optionDefault = document.createElement("option");
        //optionDefault.value = "";
        //optionDefault.textContent = "Seleccione una sociedad";
        //select.appendChild(optionDefault);

        data.forEach(sociedad => {
            const option = document.createElement("option");    
            option.value = sociedad.id_sociedad;
            option.textContent = sociedad.sociedad;
            select.appendChild(option);
        });
    });
}




// Exponer funciones globalmente para manejadores inline
window.modalPrestamos = modalPrestamos;
window.listaPrestamo = listaPrestamo;
window.listaClientes = listaClientes;
window.listarSociedades = listarSociedades;
window.limpiarFormulario = limpiarFormulario;
window.guardarPrestamo = guardarPrestamo;
window.pagarCuota=pagarCuota;
window.devolucionCuota=devolucionCuota;
window.modalCuotasPrestamo = modalCuotasPrestamo;

// Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
   listaPrestamo();
 
  
    console.log('prestamos.js cargado. DataTable y modal listos.');
});
