


let modalSociedad = null;

function modalSociedades(){
        const el = document.getElementById('modalSociedad');
        modalSociedad = new bootstrap.Modal(el, { keyboard: false });
        document.getElementById('id_sociedad').value = '';
        document.getElementById('sociedad').value = '';
        document.getElementById('valor').value = '';
        actualizarBotonesModal();
        modalSociedad.show();
}


function actualizarBotonesModal(){
     
    const id_sociedad = document.getElementById('id_sociedad').value;
    const btnRegistrar = document.querySelector('button[name="btn_registrar"]');
    const btnActualizar = document.querySelector('button[name="btn_actualizar"]');
    
    if(id_sociedad && id_sociedad.trim() !== ''){
        // Estamos actualizando
        btnRegistrar.style.display = 'none';
        btnActualizar.style.display = 'block';
      
    } else {
        // Estamos registrando
 
        btnRegistrar.style.display = 'block';
        btnActualizar.style.display = 'none';
    }
}



function listarSociedades(){
    fetch(`./listarSociedad.php`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector("#tabla-sociedades tbody");
        tableBody.innerHTML = ""; // Limpiar tabla antes de llenarla
        data.forEach((sociedad, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${sociedad.sociedad}</td>
                <td>${sociedad.valor}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="buscarSociedad(${sociedad.id_sociedad})">Editar</button>
        
                </td>
            `;
            tableBody.appendChild(row);
        });
    });
}


function buscarSociedad(id){
  
    fetch(`./buscarSociedad.php?id_sociedad=${id}`, {
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


function registrarSociedad(){
    const nombre = document.getElementById('sociedad').value;
    const valor = document.getElementById('valor').value;
    fetch(`./registrarSociedad.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `sociedad=${encodeURIComponent(nombre)}&valor=${encodeURIComponent(valor)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Sociedad registrada exitosamente.");
            modalSociedad.hide();
            listarSociedades(); // Actualizar la lista de sociedades
        } else {
            alert("Error al registrar la sociedad: " + data.message);
        }
    });
}


function actualizarSociedad(){
    const id = document.getElementById('id_sociedad').value;
    const nombre = document.getElementById('sociedad').value;
    const valor = document.getElementById('valor').value;
    fetch(`./actualizarSociedad.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_sociedad=${encodeURIComponent(id)}&sociedad=${encodeURIComponent(nombre)}&valor=${encodeURIComponent(valor)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Sociedad actualizada exitosamente.");
            modalSociedad.hide();
            listarSociedades(); // Actualizar la lista de sociedades
        } else {
            alert("Error al actualizar la sociedad: " + data.message);
        }
    });
}



window.modalSociedad = modalSociedad;


// Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
listarSociedades();
    console.log('sociedad.js cargado. DataTable y modal listos.');
});