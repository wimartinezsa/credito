

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





function guardarUsuario(){
   
    let datos= new URLSearchParams();
    datos.append('identificacion',document.getElementById('identificacion').value);
    datos.append('nombres',document.getElementById('nombres').value);
    datos.append('direccion',document.getElementById('direccion').value);
    datos.append('telefono',document.getElementById('telefono').value);
    datos.append('calificacion',document.getElementById('calificacion').value);
    datos.append('observacion',document.getElementById('observacion').value);

    fetch("./registrarUsuario.php", {
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
        alert(text);
       limpiarFormulario();
      listarUsuario();
      modalCliente.hide();
    })
    .catch(err => {
        console.error(err);
        alert('Error al guardar: ' + err);
    });
}




function actualizarUsuario(){
   
    let datos= new URLSearchParams();
    let id_usuario=document.getElementById('id_persona').value;
   
    datos.append('id_persona',id_usuario);
    datos.append('identificacion',document.getElementById('identificacion').value);
    datos.append('nombres',document.getElementById('nombres').value);
    datos.append('direccion',document.getElementById('direccion').value);
    datos.append('telefono',document.getElementById('telefono').value);
    datos.append('calificacion',document.getElementById('calificacion').value);
    datos.append('observacion',document.getElementById('observacion').value);

    fetch(`./actualizarUsuario.php?id_usuario=${id_usuario}`, {
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
        alert(text);
       limpiarFormulario();
      listarUsuario();
      modalCliente.hide();
    })
    .catch(err => {
        console.error(err);
        alert('Error al guardar: ' + err);
    });
}




let dataTableInstance = null;

function listarUsuario(){
    fetch("./listarUsuario.php", {
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
                   <button class="btn btn-sm btn-danger" onclick="desactivarUsuario(${usuario.id_persona})">Desactivar</button>
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



function buscarUsuario(id_usuario){
    fetch(`./buscarUsuario.php?id_usuario=${id_usuario}`, {
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
        if(data.length>0){
            document.getElementById('identificacion').value = data[0].identificacion;
            document.getElementById('nombres').value = data[0].nombres;
            document.getElementById('direccion').value = data[0].direccion;
            document.getElementById('telefono').value = data[0].telefono;            
            document.getElementById('calificacion').value = data[0].calificacion;
            document.getElementById('observacion').value = data[0].observacion;           
            document.getElementById('id_persona').value = data[0].id_persona;
            modalUsuario();
        } else {
            alert('Usuario no encontrado');
        }
    })
    .catch(err => alert('Error: ' + err));
}



function desactivarUsuario(id_usuario){
    if(confirm('¿Deseas desactivar este registro?')){

      
        fetch(`./eliminarUsuario.php?id_usuario=${id_usuario}`, {
            method: 'DELETE'
         
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
            alert(text);
            //listar();
        })
        .catch(err => alert('Error: ' + err));
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






