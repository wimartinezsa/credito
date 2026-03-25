



let modalGasto = null;

function modalGastos(){
        const el = document.getElementById('modalGasto');
        modalGasto = new bootstrap.Modal(el, { keyboard: false });
        modalGasto.show();
}






function formatearPesos(valor) {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 2
  }).format(valor);
}




let dataTableInstance = null;

function listaGastosSociedad(id_sociedad){
    fetch(`./listarGastoSociedad.php?id_sociedad=${id_sociedad}`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        const tabla = document.getElementById("tabla-gastos");
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
        data.forEach(gasto => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${gasto.id_gasto}</td>
                <td>${gasto.sociedad}</td>
                <td>${gasto.fecha}</td>
                <td>${gasto.detalle}</td>
                <td>${formatearPesos(gasto.valor)}</td>
               <td>${gasto.estado}</td>
             
                <td>${gasto.estado==="ejecutado"?`<button class="btn btn-danger" onclick="anularGasto(${gasto.id_gasto})">Anular</button>`:'Anulado'}
                
              
                </td>
            `;
        });
        
        // Inicializar DataTable DESPUÉS de llenar los datos
        dataTableInstance = $('#tabla-gastos').DataTable({
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

function anularGasto(id) {
  


    if (confirm("¿Estás seguro de anular este gasto?")) {
        fetch(`./anularGasto.php?id=${id}`, {
            method: 'DELETE',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Gasto anulado exitosamente');

                listaGastosSociedad(document.getElementById('sociedad').value); // Refrescar la lista después de eliminar
            } else {
                alert('Error al anular: ' + data.message);
            }   
        })
        .catch(err => {
            console.error(err);
            alert('Error al anular: ' + err);
        });
    }


}


function listarSociedadesEncargados(){
    fetch(`../sociedad/listarSociedadesEncargados.php`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
       
        const select = document.getElementById("sociedad");

        // 🔹 Limpiar el select antes de llenarlo
        select.innerHTML = "";

        // 🔹 (Opcional) Agregar opción por defecto
        const optionDefault = document.createElement("option");
        optionDefault.value = "";
        optionDefault.textContent = "Seleccione una sociedad";
        select.appendChild(optionDefault);

        data.forEach(sociedad => {
            const option = document.createElement("option");    
            option.value = sociedad.id_sociedad;
            option.textContent = sociedad.sociedad;
            select.appendChild(option);
        });
    });
}


function registrarGasto(){
    const sociedad = document.getElementById("sociedad").value;
    const fecha = document.getElementById("fecha").value;
    const detalle = document.getElementById("detalle").value;
    const valor = document.getElementById("valor").value;
    
    fetch(`./registrarGasto.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `sociedad=${encodeURIComponent(sociedad)}&fecha=${encodeURIComponent(fecha)}&detalle=${encodeURIComponent(detalle)}&valor=${encodeURIComponent(valor)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Gasto registrado exitosamente');
            modalGasto.hide(); // Cerrar el modal después de registrar
           // document.getElementById("modalGasto").classList.remove("show");
            listaGastosSociedad(document.getElementById('sociedad').value); // Refrescar la lista después de registrar
        } else {
            alert('Error al registrar el gasto: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error al registrar el gasto: ' + err);
    });
}



window.anularGasto = anularGasto;
window.listaGastosSociedad = listaGastosSociedad;
window.listarSociedadesEncargados = listarSociedadesEncargados;
window.modalGasto = modalGasto;


// Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
   listarSociedadesEncargados();
    console.log('gastos.js cargado. DataTable y modal listos.');
});
