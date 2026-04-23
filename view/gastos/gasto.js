



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

    peticionConsulta(
        BASE_URL + `view/gastos/listarGastoSociedad.php?id_sociedad=${id_sociedad}`,
        "GET"
    )
    .then(response => {

        console.log("RESPUESTA BACKEND:", response);

        // 🔴 Validar respuesta
        if (!response) {
            alert("Sin respuesta del servidor");
            return;
        }

        if (response.status && response.status !== "success") {
            alert(response.message || "Error al cargar gastos");
            return;
        }

        // 🔥 Soporta backend con o sin estándar
        const data = response.data || response;

        if (!Array.isArray(data)) {
            console.warn("No es arreglo:", data);
            return;
        }

        const tabla = document.getElementById("tabla-gastos");
        if (!tabla) return;

        // 🔴 Destruir DataTable
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

        // 🔥 Llenar tabla
        data.forEach(gasto => {
            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${gasto.id_gasto}</td>
                <td>${gasto.sociedad}</td>
                <td>${gasto.fecha}</td>
                <td>${gasto.detalle}</td>
                <td>${formatearPesos(gasto.valor)}</td>
                <td>${gasto.estado}</td>
                <td>
                    ${
                        gasto.estado === "ejecutado"
                        ? `<button class="btn btn-danger" onclick="anularGasto(${gasto.id_gasto})">Anular</button>`
                        : 'Anulado'
                    }
                </td>
            `;
        });

    })
    .catch(error => {
        console.error("ERROR REAL:", error);
        alert("Error al cargar gastos");
    });
}




function anularGasto(id) {

    if (!confirm("¿Estás seguro de anular este gasto?")) return;

    peticionCRUD(
        BASE_URL + `view/gastos/anularGasto.php?id=${id}`,
        "DELETE",
        null
    )
    .then(response => {

        console.log("RESPUESTA BACKEND:", response);

        if (!response) {
            alert("Sin respuesta del servidor");
            return;
        }

        // 🔥 SOPORTA AMBOS FORMATOS
        const ok = response.status === "success" || response.success;

        if (ok) {

            alert(response.message || response.success);

            listaGastosSociedad(
                document.getElementById('sociedad').value
            );

        } else {
            alert(response.message || "Error al anular gasto");
        }

    })
    .catch(error => {
        console.error("ERROR REAL:", error);
        alert("Error al anular gasto");
    });
}

function listarSociedadesEncargados(){

    peticionConsulta(BASE_URL + `view/sociedad/listarSociedadesEncargados.php`, "GET")
    .then(response => {

        if (response?.status !== "success") {
            alert(response?.message || "Error al cargar sociedades");
            return;
        }

        const data = response.data;

        const select = document.getElementById("sociedad");
        if (!select) return;

        select.innerHTML = "";

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

    })
    .catch(error => {
        console.error(error);
        alert("Error al cargar sociedades");
    });
}



async function registrarGasto(){

    let datos = {
        sociedad: document.getElementById("sociedad").value,
        fecha: document.getElementById("fecha").value,
        detalle: document.getElementById("detalle").value,
        valor: document.getElementById("valor").value
    };

    try {

        const response = await peticionCRUD(
            BASE_URL + `view/gastos/registrarGasto.php`,
            "POST",
            datos
        );

        console.log("RESPUESTA BACKEND:", response);

        if (!response) {
            alert("Sin respuesta del servidor");
            return;
        }

        if (response.status === "success") {

            alert(response.message);

            modalGasto.hide();

            listaGastosSociedad(
                document.getElementById('sociedad').value
            );

        } else {
            alert(response.message || "Error al registrar el gasto");
        }

    } catch (error) {
        console.error("ERROR REAL:", error);
        alert("Error al registrar el gasto");
    }
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
