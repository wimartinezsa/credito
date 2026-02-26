



let modalGasto = null;

function modalGastos(){
        const el = document.getElementById('modalGasto');
        modalGasto = new bootstrap.Modal(el, { keyboard: false });
        modalGasto.show();
}









let dataTableInstance = null;
function listaGastos(){
    fetch("./listarGasto.php", {
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
                <td>${gasto.valor}</td>
               
             
                <td>
                <button class="btn btn-danger" onclick="eliminarGasto(${gasto.id_gasto})">Eliminar</button>
              
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

function eliminarGasto(id) {
  


    if (confirm("¿Estás seguro de eliminar este gasto?")) {
        fetch(`./eliminarGasto.php?id=${id}`, {
            method: 'DELETE',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Gasto eliminado exitosamente');
                listaGastos(); // Refrescar la lista después de eliminar
            } else {
                alert('Error al eliminar: ' + data.message);
            }   
        })
        .catch(err => {
            console.error(err);
            alert('Error al eliminar: ' + err);
        });
    }


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
            listaGastos(); // Refrescar la lista después de registrar
        } else {
            alert('Error al registrar el gasto: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error al registrar el gasto: ' + err);
    });
}



window.eliminarGasto = eliminarGasto;
window.listaGastos = listaGastos;
window.listarSociedades = listarSociedades;
window.modalGasto = modalGasto;


// Cargar lista cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function(){
   listaGastos();
   listarSociedades();
    console.log('gastos.js cargado. DataTable y modal listos.');
});
