
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
        document.getElementById("codigo_prestamo").value=id_prestado;
         listarTipoGarantia();
         listarGarantiasPrestamo(id_prestado);
        modalGarantia.show();
}




let modalModificarCuotas= null;


function modalModificarCuota(id_cuota,nro_cuota,fecha_pago,valor,tipo){
        const el = document.getElementById('modalAdminCuotas');
        modalAdminCuotas = new bootstrap.Modal(el, { keyboard: false });
        document.getElementById("codigo_cuota").value=id_cuota;
        document.getElementById("nro_pago").value=nro_cuota;
        document.getElementById("valor_pago").value=valor;
        document.getElementById("tipo_pago").value=tipo;
        document.getElementById("fecha_pago").value=fecha_pago;
        document.getElementById("titleModalAdminCuotas").innerHTML="Modificar Cuota";

        
         btn_modificar_cuota.style.display = 'block';
        btn_registrar_cuota.style.display = 'none';
        modalAdminCuotas.show();
}







let dataTableInstance = null;

async function listaClientes() {

    const selectCliente = document.getElementById("cliente");
    const selectFiador = document.getElementById("fiador");

    // Limpiar opciones existentes
    selectCliente.innerHTML = "";
    selectFiador.innerHTML = "";

    // Opción por defecto
    const optionDefault1 = document.createElement("option");
    optionDefault1.value = "";
    optionDefault1.disabled = true;
    optionDefault1.selected = true;
    optionDefault1.textContent = "Seleccione un cliente";
    selectCliente.appendChild(optionDefault1);

    const optionDefault2 = optionDefault1.cloneNode(true);
    selectFiador.appendChild(optionDefault2);

    await peticionConsulta(BASE_URL + "view/usuario/listarUsuario.php","GET")
    .then(data => {

         data.forEach(usuario => {

            const option = document.createElement("option");
            option.value = usuario.id_persona;
            option.textContent = `${usuario.nombres} - ${usuario.identificacion}`;
            selectCliente.appendChild(option);
            selectFiador.appendChild(option.cloneNode(true));
        });

    });

}


async function modificarCuota() {

    let datos = {
        codigo_cuota: document.getElementById('codigo_cuota').value,
        nro_pago: document.getElementById('nro_pago').value,
        fecha_pago: document.getElementById('fecha_pago').value,
        valor_pago: document.getElementById('valor_pago').value,
        tipo_pago: document.getElementById('tipo_pago').value
    };

    try {

        const response = await peticionCRUD(
            BASE_URL + "view/cuota/actualizarCuota.php",
            'POST',
            datos
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            modalAdminCuotas.hide();

            listarCuotas(document.getElementById("cod_prestamo").value);

        } else {
            alert(response?.message || "Error al actualizar cuota");
        }

    } catch (error) {
        console.error(error);
        alert("Error al actualizar cuota");
    }
}


function adminregistrarPagoCuota(Id_cuota,valor){

        const el = document.getElementById('modalPagarCuotas');
        modalPagarCuotas = new bootstrap.Modal(el, { keyboard: false });
        document.getElementById("id_cuota_pago").value=Id_cuota;
        document.getElementById("valor_pagado").value=valor;
       

        document.getElementById("titleModalPagoCuota").innerHTML="Registrar Pago de la cuota";




        modalPagarCuotas.show();

}



function adminNuevaCuota(){

      const el = document.getElementById('modalAdminCuotas');
        modalAdminCuotas = new bootstrap.Modal(el, { keyboard: false });
        document.getElementById("codigo_cuota").value="";
        document.getElementById("nro_pago").value="";
        document.getElementById("valor_pago").value="";
        document.getElementById("tipo_pago").value="";
        document.getElementById("fecha_pago").value="";

        document.getElementById("titleModalAdminCuotas").innerHTML="Nueva Cuota";

        btn_modificar_cuota.style.display = 'none';
        btn_registrar_cuota.style.display = 'block';



        modalAdminCuotas.show();



}



async function registarNuevaCuota() {

    let datos = {
        id_prestamo: document.getElementById("cod_prestamo").value,
        nro_pago: document.getElementById('nro_pago').value,
        fecha_pago: document.getElementById('fecha_pago').value,
        valor_pago: document.getElementById('valor_pago').value,
        tipo_pago: document.getElementById('tipo_pago').value
    };

    try {

        const response = await peticionCRUD(
            BASE_URL + "view/cuota/nuevaCuota.php",
            'POST',
            datos
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            modalAdminCuotas.hide();

            listarCuotas(document.getElementById("cod_prestamo").value);

        } 
        else if (response?.status === "error") {

            alert(response.message);

        } 
        else {

            alert("Respuesta inesperada del servidor");

        }

    } catch (error) {
        console.error("ERROR:", error);
        alert("Error al registrar cuota");
    }
}





 async function guardarPrestamo() {

    // 🔥 CREAR OBJETO JSON DIRECTO
    let datos = {
        sociedad: document.getElementById('sociedad').value,
        ficha: document.getElementById('ficha').value,
        cliente: document.getElementById('cliente').value,
        fecha: document.getElementById('fecha').value,
        tiempo: document.getElementById('tiempo').value,
        valor: document.getElementById('valor_prestado').value,
        interes: document.getElementById('interes').value,
        tipo: document.getElementById('tipo').value,
        fiador: document.getElementById('fiador').value,
        estado: "aprobado"
    };

 

    try {

        const response = await peticionCRUD(
            BASE_URL + "view/prestamo/registrarPrestamo.php",
            'POST',
            datos
        );

       

        alert(response.message || "Préstamo guardado correctamente");

        listarPrestamosId(document.getElementById('lista_sociedades').value);
        disponibilidadSociedad(document.getElementById("lista_sociedades").value);
        modalPrestamo.hide();

    } catch (error) {
        console.error(error);
        alert("Error al guardar préstamo");
    }
}




async function verPagos(id_prestamo){
    document.getElementById("cod_prestamo").value=id_prestamo;
  
  await listarCuotas(id_prestamo);
    modalCuotasPrestamo();
}



async function finalizarPrestamo() {

    if (!confirm('¿Confirma que desea finalizar el crédito?')) return;

    const id_prestamo = document.getElementById("cod_prestamo").value;

    try {

        const respuesta = await peticionCRUD(
            BASE_URL + `view/prestamo/finalizarPrestamo.php?id_prestamo=${id_prestamo}`,
            "GET",
            null
        );

        console.log("RESPUESTA BACKEND:", respuesta);

        // =========================
        // VALIDACIÓN ROBUSTA
        // =========================
        if (respuesta?.status === "success") {

            alert(respuesta.message);

            modalCuotas.hide();

            listarPrestamosId(
                document.getElementById('lista_sociedades').value
            );

        } 
        else if (respuesta?.status === "error") {

            alert(respuesta.message);

        } 
        else {

            // fallback por si backend viejo responde string
            alert("Respuesta inesperada del servidor");

        }

    } catch (error) {
        console.error("ERROR FRONT:", error);
        alert("Error al finalizar el préstamo");
    }
}

async function buscarPrestamo(id_prestamo){
    document.getElementById("id_prestamo").value="";
    await  listarSociedades();
    await listaClientes();

    const data = await peticionConsulta(BASE_URL +`view/prestamo/buscarPrestamo.php?id_prestamo=${id_prestamo}`,"GET");

  
      
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
   
}


async function actualizarPrestamo() {

    let datos = {
        id_prestamo: document.getElementById('id_prestamo').value,
        sociedad: document.getElementById('sociedad').value,
        ficha: document.getElementById('ficha').value,
        cliente: document.getElementById('cliente').value,
        fecha: document.getElementById('fecha').value,
        tiempo: document.getElementById('tiempo').value,
        valor: document.getElementById('valor_prestado').value,
        interes: document.getElementById('interes').value,
        tipo: document.getElementById('tipo').value,
        fiador: document.getElementById('fiador').value,
        estado: "aprobado"
    };

    try {

        const response = await peticionCRUD(
            BASE_URL + "view/prestamo/actualizarPrestamo.php",
            'PUT',
            datos
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            await listarPrestamosId(
                document.getElementById('lista_sociedades').value
            );

            modalPrestamo.hide();

        } else {
            alert(response?.message || "Error al actualizar préstamo");
        }

    } catch (error) {
        console.error(error);
        alert("Error en la actualización");
    }
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




async function listarCuotas(id_prestamo){

   const data = await peticionConsulta(BASE_URL + `view/cuota/listarCuotas.php?id_prestamo=${id_prestamo}`,"GET")
   .then(data => {

    const tabla = document.getElementById("tabla-cuotas");
        if (!tabla) return; 

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
                <td>${cuota.nro_cuota}</td>
                <td>${cuota.fecha_pago}</td>
                <td>${formatearPesos(cuota.valor)}</td>
                <td>${cuota.tipo}</td>
                <td>${cuota.estado}</td>
                <td>
                    ${cuota.estado ==='pendiente' 
                        ? `<a onclick="adminregistrarPagoCuota(${cuota.id_cuota},${cuota.valor})" alt="Pagar Cuota" class="btn btn-sm btn-warning">Pagar </a>
                             
                           <a  onclick="modalModificarCuota(${cuota.id_cuota},${cuota.nro_cuota},'${cuota.fecha_pago}',${cuota.valor},'${cuota.tipo}')" alt="Modificar Cuota" 
                             class="btn btn-sm btn-success">
                             Modificar
                           </a>

                            <a  onclick="eliminarCuota(${cuota.id_cuota})" alt="Eliminar Cuota" 
                             class="btn btn-sm btn-danger">
                             Eliminar
                           </a>

                           `
                        : `<a  onclick="devolucionCuota(${cuota.id_cuota},${cuota.valor})" alt="Devolución Cuota" 
                             class="btn btn-sm btn-success">
                             Devolución
                           </a>`
                    }
                </td>
            `;
        });
        document.getElementById('valores').innerHTML='V. Futuro: '+formatearPesos(valor_futuro)+ '  /  V. Pagado: '+formatearPesos(valor_pagado)+ '  /  V. Pendiente: '+formatearPesos(valor_pendiente);

  


   });

 
   

    

}



async function eliminarCuota(id_cuota) {

    if (!confirm('¿Confirma que desea eliminar la cuota?')) return;

    try {

        const response = await peticionCRUD(
            BASE_URL + `view/cuota/eliminarCuota.php?id_cuota=${id_cuota}`,
            'GET',
            null
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            listarCuotas(document.getElementById("cod_prestamo").value);

        } else {
            alert(response?.message || "Error al eliminar cuota");
        }

    } catch (error) {
        console.error(error);
        alert("Error al eliminar cuota");
    }
}



function registrarPagoCuota() {
    if (!confirm('¿Confirma que desea registrar el pago de la cuota?')) return;

    let id_cuota_pago = document.getElementById("id_cuota_pago").value;
    let valor_pagado = document.getElementById('valor_pagado').value;
    let fecha_recaudo = document.getElementById('fecha_recaudo').value;

    // ✅ Validación básica
    if (!id_cuota_pago || !valor_pagado || !fecha_recaudo) {
        alert("Todos los campos son obligatorios");
        return;
    }



    let datos={
    id_cuota_pago: id_cuota_pago,
    valor_pagado: valor_pagado,
    fecha_recaudo: fecha_recaudo
    };



    peticionCRUD(BASE_URL + "view/cuota/pagarCuota.php", 'POST', datos)
        .then(text => {
             if (!text) return;
        modalPagarCuotas.hide();
        alert(text);
        listarCuotas(document.getElementById("cod_prestamo").value);
        disponibilidadSociedad(document.getElementById("lista_sociedades").value);
        });

}


async function devolucionCuota(id_cuota, valor_cuota) {

    if (!confirm('¿Confirma que desea hacer devolución de esta cuota?')) return;

    try {

        const response = await peticionCRUD(
            BASE_URL + `view/cuota/devolucionCuota.php?id_cuota=${id_cuota}&valor_cuota=${valor_cuota}`,
            'GET',
            null
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            listarCuotas(document.getElementById("cod_prestamo").value);
            disponibilidadSociedad(document.getElementById("lista_sociedades").value);

        } else {
            alert(response?.message || "Error en la devolución");
        }

    } catch (error) {
        console.error(error);
        alert("Error al procesar la devolución");
    }
}



async function listarSociedadesEncargados() {

    const token = localStorage.getItem("token");

    const data = await peticionConsulta(
        BASE_URL + `view/sociedad/listarSociedadesEncargados.php`,
        "GET",
        null
    );

    console.log("DATA:", data);

    if (!data || !Array.isArray(data)) {
        console.error("La respuesta no es un arreglo:", data);
        return;
    }

    const select = document.getElementById("lista_sociedades");
    select.innerHTML = "";

    const option = document.createElement("option");    
    option.disabled = true;
    option.selected = true;
    option.textContent = "Seleccione una Sociedad";
    select.appendChild(option);

    data.forEach(sociedad => {
        const option = document.createElement("option");    
        option.value = sociedad.id_sociedad;
        option.textContent = sociedad.sociedad;
        select.appendChild(option);
    });
}




function cerrarSesion(){
    localStorage.clear(); // 🔥 limpiar sesión
    window.location.href = "../../index.php";
}



async function listarSociedades(){

  const data=  await peticionConsulta(BASE_URL + `view/sociedad/listarSociedadesEncargados.php`,"GET")
  
        // ✅ Renderizar
        const select = document.getElementById("sociedad");
        select.innerHTML = "";

        data.forEach(sociedad => {
            const option = document.createElement("option");    
            option.value = sociedad.id_sociedad;
            option.textContent = sociedad.sociedad;
            select.appendChild(option);
        });
   
}






function formatearPesos(valor) {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 2
  }).format(valor);
}


async function listarPrestamosId(id_sociedad) {
    try {

        const data = await peticionConsulta(
            BASE_URL + `view/prestamo/listarPrestamosId.php?id_sociedad=${id_sociedad}`,
            "GET"
        );

        console.log("DATA:", data);

        if (!data || !Array.isArray(data)) {
            console.warn("Datos inválidos:", data);
            return;
        }

        const tabla = document.getElementById("tabla-prestamos");
        if (!tabla) return;

        // =========================
        // DESTRUIR DATATABLE
        // =========================
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

        // =========================
        // LLENAR TABLA
        // =========================
        data.forEach(prestamo => {

            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${prestamo.id_prestamo}</td>
                <td>${prestamo.nombres}</td>
                <td>${prestamo.tipo}</td>
                <td>${prestamo.fecha_prestamo}</td>
                <td>${prestamo.tiempo}</td>
                <td>${formatearPesos(prestamo.valor_prestado)}</td>
                <td>${prestamo.interes}</td>
                <td>${formatearPesos(prestamo.futuro ?? 0)}</td>
                <td>${formatearPesos(prestamo.pagado ?? 0)}</td>
                <td>${formatearPesos(prestamo.pendiente ?? 0)}</td>
                <td>${prestamo.estado}</td>
                <td>
                    ${prestamo.estado !== 'finalizado' ? `
                        <button class="btn btn-sm btn-primary" onclick="buscarPrestamo(${prestamo.id_prestamo})">Actualizar</button>
                        <button class="btn btn-sm btn-success" onclick="verPagos(${prestamo.id_prestamo})">Pagos</button>
                        <button class="btn btn-sm btn-danger" onclick="modalAdminGarantia(${prestamo.id_prestamo})">Garantía</button>
                    ` : ""}
                </td>
            `;
        });

        // =========================
        // INICIALIZAR DATATABLE
        // =========================
        dataTableInstance = $('#tabla-prestamos').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true
        });

    } catch (err) {
        console.error("Error real:", err);
        alert('Error al listar: ' + err.message);
    }
}


async function disponibilidadSociedad(id_sociedad){
  
const data=  await peticionConsulta(BASE_URL + `view/prestamo/disponibleSociedad.php?id_sociedad=${id_sociedad}`,"GET")
{
    if (!data) return;
    document.getElementById("disponible").innerHTML= data.caja!= null? "Disponible ="+formatearPesos(data.caja):"Disponible = 0";
   
}
    

}







window.subirGarantia = function(){

      let formData = new FormData();
  
   formData.append('codigo_prestamo', document.getElementById("codigo_prestamo").value);
   formData.append('tipo', document.getElementById("tipo_garantia").value);
   
    let archivo = document.getElementById("archivo").files[0];
    formData.append('archivo', archivo);

    fetch(BASE_URL + "view/garantia/subirGarantia.php",{
        method:"POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {

        listarGarantiasPrestamo(document.getElementById("codigo_prestamo").value);
        alert(data);

    })
    .catch(error => console.error(error));

};

function listarTipoGarantia(){

    peticionConsulta(BASE_URL + `view/garantia/listarTipoGarantia.php`,"GET")
    .then(data => {
         const select = document.getElementById("tipo_garantia");
        select.innerHTML = "";
        const option = document.createElement("option");    
            option.value = ""
            option.textContent = "Seleccione un tipo de garantía";
            select.appendChild(option);
        data.forEach(tipo => {
            const option = document.createElement("option");    
            option.value = tipo.id_tipo_garantia;
            option.textContent = tipo.nombre_tipo;
            select.appendChild(option);
        });
  
    });

 
}

function listarGarantiasPrestamo(id_prestamo){

peticionConsulta(BASE_URL + `view/garantia/listarGarantiasPrestamo.php?id_prestamo=${id_prestamo}`,"GET")
    .then(response =>{

        const tabla = document.getElementById("tabla-garantias");
        let tbody = tabla.querySelector('tbody');
        if (!tbody) {
            tbody = document.createElement('tbody');
            tabla.appendChild(tbody);
        }
        tbody.innerHTML = "";
        data.forEach(garantia => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${garantia.id_garantia}</td>
                <td>${garantia.nombre_tipo}</td>
                <td>
                <a href="${garantia.ruta}" class="btn btn-primary" target="_blank">Ver</a>
                <a onclick="eliminarGarantia(${garantia.id_garantia});" class="btn btn-danger">Eliminar</a>
                </td>
            `;
        });
     });

}


function eliminarGarantia(id_garantia){
    if (!confirm('¿Eliminar esta garantía?')) return;

peticionCRUD(BASE_URL + `view/garantia/eliminarGarantia.php?id_garantia=${id_garantia}`, 'GET', null)
    .then(text => {
        listarGarantiasPrestamo(document.getElementById("codigo_prestamo").value);
        alert(text);
    });
}

window.eliminarGarantia = eliminarGarantia;

// Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
    listarSociedadesEncargados();

 
   // Asignar evento al botón de subir garantía
   const button = document.getElementById('btnSubirGarantia');
   if (button) {
       button.addEventListener('click', window.subirGarantia);
       button.removeAttribute('onclick');
   }

});
  
