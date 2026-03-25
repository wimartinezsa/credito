


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



function formatearPesos(valor) {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 2
  }).format(valor);
}



function listarPerosnasEncargados(){
          fetch(`../sociedad/listarPerosnasEncargados.php`, {
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
        const select = document.getElementById("encargado");
        select.innerHTML = "";
        const option = document.createElement("option");    
            option.value = ""
            option.textContent = "Seleccione un Socio";
            select.appendChild(option);
        data.forEach(persona => {
            const option = document.createElement("option");    
            option.value = persona.id_persona;
            option.textContent = persona.nombres;
            select.appendChild(option);
        });
    });

}


function asignarEncargadoSociedad(){
  
    let datos= new URLSearchParams();
    datos.append('id_sociedad',document.getElementById('id_sociedad_encargado').value);
    datos.append('encargado',document.getElementById('encargado').value);
    datos.append('rol',document.getElementById('rol').value);

    fetch(`./asignarEncargadoSociedad.php`, {
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
        listarEncargadosSociedadesId();
        listarTodasSociedades();
       alert(response.text());
    })
    .then(text => {
        alert(text);
        listarTodasSociedades();
         modalEncargado.hide();
       
    });


}


function listarEncargadosSociedadesId(){
   let id_sociedad_encargado=document.getElementById('id_sociedad_encargado').value;
    fetch(`./listarEncargadosSociedadesId.php?id_sociedad=${id_sociedad_encargado}`, {
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
        const tableBody = document.querySelector("#tabla_encargados tbody");
        tableBody.innerHTML = ""; // Limpiar tabla antes de llenarla
        data.forEach((sociedad, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${sociedad.id_administrador}</td>
                <td>${sociedad.nombres}</td>
                <td>${sociedad.rol}</td>
               
                <td>
                    <button class="btn btn-warning btn-sm" onclick="eliminarEncargadoSociedad(${sociedad.id_administrador})">Eliminar</button>
                  
                </td>
            `;
            tableBody.appendChild(row);
        });
    });
}




function listarTodasSociedades(){
   
    fetch(`./listarTodasSociedades.php`, {
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


function eliminarEncargadoSociedad(id_admin){

   
    fetch(`./eliminarEncargadoSociedad.php?id_admin=${id_admin}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
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
        if (data.success) {
            alert(text);
            listarEncargadosSociedadesId();
           list
        } else {
            alert("Error al eliminar el encargado: " + text);
        }
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
     .then(response =>{
        if(response.status === 401)
        {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
         }
        return response.text();
    })
    .then(data => {
        if (data.success) {
            alert("Sociedad registrada exitosamente.");
            modalSociedad.hide();
            listarTodasSociedades(); // Actualizar la lista de sociedades
        } else {
            alert("Error al registrar la sociedad: " + data.message);
        }
    });

}


function adicionarSociedad(){
    const id = document.getElementById('id_sociedad').value;
    const nombre = document.getElementById('sociedad').value;
    const valor = document.getElementById('valor').value;
    fetch(`./adicionarSociedad.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_sociedad=${encodeURIComponent(id)}&sociedad=${encodeURIComponent(nombre)}&valor=${encodeURIComponent(valor)}`
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
        console.log(data);
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