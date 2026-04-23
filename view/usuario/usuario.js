

let modalCliente = null;

function modalUsuario(){
        const el = document.getElementById('modalCliente');
        modalCliente = new bootstrap.Modal(el, { keyboard: false });
        actualizarBotonesModal();
        modalCliente.show();
}

function actualizarBotonesModal(){
    let id_persona = document.getElementById('id_persona').value;
    const btnRegistrar = document.querySelector('button[name="btn_registrar"]');
    const btnActualizar = document.querySelector('button[name="btn_actualizar"]');
    
    if(id_persona && id_persona.trim() !== ''){
        // Estamos actualizando
        btnRegistrar.style.display = 'none';
        btnActualizar.style.display = 'block';
    } else {
        // Estamos registrando
        btnRegistrar.style.display = 'block';
        btnActualizar.style.display = 'none';
    }
}





async function guardarUsuario() {

    let datos = {
        identificacion: document.getElementById('identificacion').value,
        nombres: document.getElementById('nombres').value,
        direccion: document.getElementById('direccion').value,
        telefono: document.getElementById('telefono').value,
        calificacion: document.getElementById('calificacion').value,
        observacion: document.getElementById('observacion').value
    };

    try {

        const response = await peticionCRUD(
            BASE_URL + "view/usuario/registrarUsuario.php",
            'POST',
            datos
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            limpiarFormulario();
            listarUsuario();
            modalCliente.hide();

        } 
        else if (response?.status === "error") {

            alert(response.message);

        } 
        else {

            alert("Respuesta inesperada del servidor");

        }

    } catch (error) {
        console.error("ERROR:", error);
        alert("Error al guardar usuario");
    }
}



async function actualizarUsuario() {

    let datos = {
        id_persona: document.getElementById('id_persona').value,
        identificacion: document.getElementById('identificacion').value,
        nombres: document.getElementById('nombres').value,
        direccion: document.getElementById('direccion').value,
        telefono: document.getElementById('telefono').value,
        calificacion: document.getElementById('calificacion').value,
        observacion: document.getElementById('observacion').value
    };

    try {

        const response = await peticionCRUD(
            BASE_URL + `view/usuario/actualizarUsuario.php?id_usuario=${datos.id_persona}`,
            'POST',
            datos
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            limpiarFormulario();
            listarUsuario();
            modalCliente.hide();

        } 
        else if (response?.status === "error") {

            alert(response.message);

        } 
        else {

            alert("Respuesta inesperada del servidor");

        }

    } catch (error) {
        console.error("ERROR:", error);
        alert("Error al actualizar usuario");
    }
}




let dataTableInstance = null;

function listarUsuario(){

    peticionConsulta(BASE_URL +"view/usuario/listarUsuario.php", 'GET')
        .then(data => {
         
             const tabla = document.getElementById("tabla-usuarios");
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
        data.forEach(usuario => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${usuario.id_persona}</td>
                <td>${usuario.identificacion}</td>
                <td>${usuario.nombres}</td>
                <td>${usuario.direccion}</td>
                <td>${usuario.telefono}</td>
                <td>${usuario.calificacion || '-'}</td>
                <td>${usuario.observacion || '-'}</td>
                <td>
                <button class="btn btn-sm btn-primary" onclick="buscarUsuario(${usuario.id_persona})">Actualizar</button>
                
                </td>
            `;

            //   <button class="btn btn-sm btn-danger" onclick="desactivarUsuario(${usuario.id_persona})">Desactivar</button>
        
        });
        // Inicializar DataTable DESPUÉS de llenar los datos
        dataTableInstance = $('#tabla-usuarios').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true
        });
        
        });

}



function buscarUsuario(id_usuario){

peticionConsulta(BASE_URL + `view/usuario/buscarUsuario.php?id_usuario=${id_usuario}`, 'GET')
     .then(data => {
        
        if(data.length>0){
            document.getElementById('identificacion').value = data[0].identificacion;
            document.getElementById('nombres').value = data[0].nombres;
            document.getElementById('direccion').value = data[0].direccion;
            document.getElementById('telefono').value = data[0].telefono;            
            document.getElementById('calificacion').value = data[0].calificacion;
            document.getElementById('observacion').value = data[0].observacion;           
            document.getElementById('id_persona').value = data[0].id_persona;
            modalUsuario();
        }
           
        });

    
}



async function desactivarUsuario(id_usuario) {

    if (!confirm('¿Deseas desactivar este registro?')) return;

    try {

        const response = await peticionCRUD(
            BASE_URL + `view/usuario/eliminarUsuario.php?id_usuario=${id_usuario}`,
            'DELETE'
        );

        console.log("RESPUESTA BACKEND:", response);

        if (response?.status === "success") {

            alert(response.message);

            listarUsuario(); // 🔥 refrescar tabla

        } 
        else if (response?.status === "error") {

            alert(response.message);

        } 
        else {

            alert("Respuesta inesperada del servidor");

        }

    } catch (error) {
        console.error("ERROR:", error);
        alert("Error al desactivar usuario");
    }
}

function limpiarFormulario(){
    document.getElementById('identificacion').value="";
    document.getElementById('nombres').value="";
    document.getElementById('direccion').value="";
    document.getElementById('telefono').value="";
    document.getElementById('calificacion').value="";
    document.getElementById('observacion').value="";
    document.getElementById('id_persona').value="";
}



// Exponer funciones globalmente para manejadores inline
window.modalUsuario = modalUsuario;
window.buscarUsuario=buscarUsuario;
window.guardarUsuario = guardarUsuario;
window.actualizarUsuario = actualizarUsuario;
window.desactivarUsuario = desactivarUsuario;
window.limpiarFormulario = limpiarFormulario;






