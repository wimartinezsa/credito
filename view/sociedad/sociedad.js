


let modalSociedad = null;

function modalSociedades(){
        const el = document.getElementById('modalSociedad');
        modalSociedad = new bootstrap.Modal(el, { keyboard: false });
        document.getElementById('id_sociedad').value = '';
        document.getElementById('sociedad').value = '';
        document.getElementById('valor').value = '0';
        document.getElementById('valor').disabled = true;
        actualizarBotonesModal();
        modalSociedad.show();
}


function actualizarBotonesModal(){
     
    const id_sociedad = document.getElementById('id_sociedad').value;
    const btnRegistrar = document.querySelector('button[name="btn_registrar"]');
    const btnActualizar = document.querySelector('button[name="btn_actualizar"]');

    
     document.getElementById('valor').value = '0';
    
    if(id_sociedad && id_sociedad.trim() !== ''){
        // Estamos actualizando
        btnRegistrar.style.display = 'none';
        btnActualizar.style.display = 'block';
         document.getElementById('valor').disabled = false;
      
    } else {
        // Estamos registrando
 
        btnRegistrar.style.display = 'block';
        btnActualizar.style.display = 'none';
    }
}



function formatearPesos(valor) {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 2
  }).format(valor);
}



function listarPerosnasEncargados() {


    fetch(BASE_URL + "view/sociedad/listarPerosnasEncargados.php", {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': localStorage.getItem('token') // si estás usando token
        }
    })
    .then(response => {

        if (response.status === 401) {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
        }

        if (!response.ok) {
            throw new Error('Error en la petición');
        }

        return response.json();
    })
    .then(data => {

        if (!data) return; // 🔹 evita errores si hubo 401

        const select = document.getElementById("encargado");
        select.innerHTML = '<option value="">Seleccione un Socio</option>';

        data.forEach(persona => {
            const option = document.createElement("option");
            option.value = persona.id_persona;
            option.textContent = persona.nombres;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al cargar los encargados');
    });
}

function asignarEncargadoSociedad() {

    const datos = {
        id_sociedad: document.getElementById('id_sociedad_encargado').value,
        encargado: document.getElementById('encargado').value,
        password: document.getElementById('password').value,
        rol: "Socio"
    };

    peticionCRUD(BASE_URL + "view/sociedad/asignarEncargadoSociedad.php", 'POST', datos)
    .then(response => {

        if (!response) return; // 🔹 por si tu helper ya manejó 401

        listarTodasSociedades();
        modalEncargado.hide();

        // 🔹 si backend devuelve { success, message }
        if (response.success) {
            alert(response.message || 'Encargado asignado correctamente');
        } else {
            alert(response.message || 'No se pudo asignar el encargado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al asignar el encargado');
    });
}


function listarEncargadosSociedadesId() {

    const id_sociedad_encargado = document.getElementById('id_sociedad_encargado').value;

    if (!id_sociedad_encargado) {
        alert('Seleccione una sociedad');
        return;
    }

    fetch(BASE_URL + `view/sociedad/listarEncargadosSociedadesId.php?id_sociedad=${id_sociedad_encargado}`, {
        method: 'GET',
        headers: {
            'Authorization': localStorage.getItem('token')
        }
    })
    .then(response => {

        if (response.status === 401) {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
        }

        if (!response.ok) {
            throw new Error('Error en la petición');
        }

        return response.json();
    })
    .then(data => {

        if (!data) return; // 🔹 evita error si hubo 401

        const tableBody = document.querySelector("#tabla_encargados tbody");
        tableBody.innerHTML = "";

        data.forEach((sociedad) => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${sociedad.id_administrador}</td>
                <td>${sociedad.nombres}</td>
                <td>${sociedad.rol}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="eliminarEncargadoSociedad(${sociedad.id_administrador})">
                        Eliminar
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al listar los encargados');
    });
}




function listarTodasSociedades(){
   
 peticionConsulta(BASE_URL + `view/sociedad/listarTodasSociedades.php`, 'GET', null)
    .then(data => {
        const tableBody = document.querySelector("#tabla-sociedades tbody");
        tableBody.innerHTML = ""; // Limpiar tabla antes de llenarla
        data.forEach((sociedad, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${sociedad.sociedad}</td>
                <td>${formatearPesos(sociedad.caja)}</td>
                <td>${sociedad.administrador}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="buscarSociedad(${sociedad.id_sociedad})">Adicionar</button>
                    <button class="btn btn-primary btn-sm" onclick="asignarEncargado(${sociedad.id_sociedad})">Encargado</button>
                   
                </td>
            `;
            tableBody.appendChild(row);
        });
    });
}




 



async function asignarEncargado(id_sociedad){
   
      const el = document.getElementById('modalEncargado');
        modalEncargado = new bootstrap.Modal(el, { keyboard: false });
        document.getElementById('id_sociedad_encargado').value = id_sociedad;
       await listarEncargadosSociedadesId();
       await listarPerosnasEncargados();
        modalEncargado.show();





}



function buscarSociedad(id){
  
    fetch(BASE_URL + `view/sociedad/buscarSociedad.php?id_sociedad=${id}`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta de buscarSociedad:", data);
       
           // console.log("Sociedad encontrada:", data[0]);
            document.getElementById('id_sociedad').value = data.id_sociedad;
            document.getElementById('sociedad').value = data.sociedad;
            document.getElementById('valor').value = data.valor;
            
             const el = document.getElementById('modalSociedad');
            modalSociedad = new bootstrap.Modal(el, { keyboard: false });
             actualizarBotonesModal();
            modalSociedad.show();

         
    });
}


function eliminarEncargadoSociedad(id_admin){

peticionCRUD(BASE_URL + `view/sociedad/eliminarEncargadoSociedad.php?id_admin=${id_admin}`, 'GET', null)
     .then(data => {

           listarEncargadosSociedadesId();
           alert(text);
        });

}



function registrarSociedad(){

    const nombre = document.getElementById('sociedad').value;
    const valor = document.getElementById('valor').value;
    const datos = {
        sociedad: nombre,
        valor: valor
    };
    peticionCRUD(BASE_URL + `view/sociedad/registrarSociedad.php`, 'POST', datos)
     .then(response => {
           modalSociedad.hide();
            listarTodasSociedades(); // Actualizar la lista de sociedades
            alert(response.message);
     });




}


function adicionarSociedad(){
    const id = document.getElementById('id_sociedad').value;
    const nombre = document.getElementById('sociedad').value;
    const valor = document.getElementById('valor').value;

    const datos = {
        id_sociedad: id,
        sociedad: nombre,
        valor: valor
    };
    peticionCRUD(BASE_URL + `view/sociedad/adicionarSociedad.php`, 'POST', datos)
     .then(data => {
          
        if (data.success) {
            alert("Sociedad actualizada exitosamente.");
            modalSociedad.hide();
            listarTodasSociedades(); // Actualizar la lista de sociedades
        } else {
            alert("Error al actualizar la sociedad: " + data.message);
        }
     });


}



window.modalSociedad = modalSociedad;


// Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
listarTodasSociedades();
    console.log('sociedad.js cargado. DataTable y modal listos.');
});