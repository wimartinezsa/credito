







let dataTableInstance = null;



function formatearPesos(valor) {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 2
  }).format(valor);
}



// Reporte numero 1

async function listarSociedades(){

    try {

        const response = await peticionConsulta(
            BASE_URL + "view/sociedad/listarSociedadesEncargados.php",
            'GET'
        );

        console.log("RESPUESTA BACKEND:", response);

        if (!response) {
            alert("Sin respuesta del servidor");
            return;
        }

        if (response.status !== "success") {
            alert(response.message || "Error al cargar sociedades");
            return;
        }

        const data = response.data;

        const select = document.getElementById('sociedad');
        if (!select) return;

        select.innerHTML = '<option value="">Seleccionar Sociedad</option>';

        data.forEach(sociedad => {
            const option = document.createElement('option');
            option.value = sociedad.id_sociedad;
            option.textContent = sociedad.sociedad;
            select.appendChild(option);
        });

    } catch (error) {
        console.error("ERROR REAL:", error);
        alert("Error al cargar sociedades");
    }
}





function estadoSociedad(){

    const idSociedad = document.getElementById('sociedad').value;
    if (!idSociedad) {
        alert('Por favor, seleccione una sociedad');
        return;
    }

    peticionConsulta(BASE_URL + `view/reportes/estadoSociedad.php?id_sociedad=${idSociedad}`, 'GET')
    .then(data => {
        
        let  estadosDiv = '';
      
        estadosDiv += `
                 <div class="card text-bg-light mb-3" style="max-width: 18rem; border-success">
                <div class="card-header">SOCIEDAD: ${data[0].sociedad}</div>
                <div class="card-body">
                    <h5 class="card-title">Valor Inicial</h5>
                    <p class="card-text">Total: $${data[0].inicial}</p>
                </div>
                </div>
            `;
           

        
                 estadosDiv += `
                    <div class="card text-bg-light mb-3" style="max-width: 18rem; border-success">
              
                        <div class="card-header">SOCIEDAD: ${data[0].sociedad}</div>
                        <div class="card-body">
                            <h5 class="card-title">Valor Prestado</h5>
                            <p class="card-text">Total: $${data[0].prestado}</p>
                        </div>
                    </div>
            `;
           


   estadosDiv += `
                    <div class="card text-bg-light mb-3" style="max-width: 18rem; border-success">
              
                        <div class="card-header">SOCIEDAD: ${data[0].sociedad}</div>
                        <div class="card-body">
                            <h5 class="card-title">Valor Futuro</h5>
                            <p class="card-text">Total: $${data[0].futuro}</p>
                        </div>
                    </div>
            `;

            estadosDiv += `
                    <div class="card text-bg-light mb-3" style="max-width: 18rem; border-success">
              
                        <div class="card-header">SOCIEDAD: ${data[0].sociedad}</div>
                        <div class="card-body">
                            <h5 class="card-title">Valor Recaudado</h5>
                            <p class="card-text">Total: $${data[0].recaudado}</p>
                        </div>
                    </div>
            `;


estadosDiv += `
                    <div class="card text-bg-light mb-3" style="max-width: 18rem; border-success">
              
                        <div class="card-header" >SOCIEDAD:${data[0].sociedad}</div>
                        <div class="card-body">
                            <h5 class="card-title">Valor Pendiente</h5>
                            <p class="card-text">Total: $${data[0].pendiente}</p>
                        </div>
                    </div>
            `;



estadosDiv += `
                    <div class="card text-bg-light mb-3" style="max-width: 18rem; border-success">
              
                        <div class="card-header" >SOCIEDAD:${data[0].sociedad}</div>
                        <div class="card-body">
                            <h5 class="card-title">Valor Gastado</h5>
                            <p class="card-text">Total: $${data[0].gastos}</p>
                        </div>
                    </div>
            `;


            estadosDiv += `
                    <div class="card text-bg-light mb-3" style="max-width: 18rem; border-success">
              
                        <div class="card-header" >SOCIEDAD:${data[0].sociedad}</div>
                        <div class="card-body">
                            <h5 class="card-title">Valor Disponible</h5>
                            <p class="card-text">Total: $${data[0].disponible}</p>
                        </div>
                    </div>
            `;

          
            document.getElementById('estado-sociedad').innerHTML = estadosDiv;

    });

    
}



// Reporte numero 2
function gastoPorFecha(){

    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const sociedad = document.getElementById('sociedad').value;

   

    let datos = {
        fecha_inicio: document.getElementById('fecha_inicio').value,
        fecha_fin: document.getElementById('fecha_fin').value,
        sociedad: document.getElementById('sociedad').value
    };

    peticionConsulta(BASE_URL + "view/reportes/gastosPorFechas.php", 'POST', datos)
    .then(data => {

         console.log(data);
        const tabla = document.getElementById("tabla-reporte2");
        if (!tabla) return;

        if (dataTableInstance) {
            dataTableInstance.destroy();
            dataTableInstance = null;
        }

        let tbody = tabla.querySelector('tbody');
        if (!tbody) {
            tbody = document.createElement('tbody');
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";
    let totalGastos = 0;
        data.forEach(usuario => {
            const row = tbody.insertRow();
            totalGastos += parseFloat(usuario.valor);
            row.innerHTML = `
                <td>${usuario.id_gasto}</td>
                <td>${usuario.sociedad}</td>
                <td>${usuario.fecha}</td>
                <td>${usuario.detalle}</td>
                <td>${formatearPesos(usuario.valor)}</td>
            `;
        });

       document.getElementById("total-gastos").innerHTML = "Total Gastos: $" + totalGastos.toFixed(2);

       dataTableInstance = $('#tabla-reporte2').DataTable({
    pageLength: 10,
    searching: true,
    ordering: true,
    paging: true,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            text: 'Exportar a Excel',
            title: 'Reporte_Gastos',
            exportOptions: {
                columns: ':visible'
            }
        }
    ]
});

    });


    
}


// ==========================
// Reporte #3 - Préstamos por fechas
// ==========================
async function listarPrestamosPorFechas() {

    try {
        // ==========================
        // CAPTURA DE DATOS
        // ==========================
        const fechaInicio = document.getElementById('fecha_inicio').value.trim();
        const fechaFin    = document.getElementById('fecha_fin').value.trim();
        const sociedad    = document.getElementById('sociedad').value.trim();

        // ==========================
        // VALIDACIÓN
        // ==========================
        if (!fechaInicio || !fechaFin || !sociedad) {
            alert("Debe completar todos los campos");
            return;
        }

        const datos = {
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin,
            sociedad: sociedad
        };

        // ==========================
        // PETICIÓN
        // ==========================
        const response = await fetch(BASE_URL + "view/reportes/prestamosPorFecha.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${localStorage.getItem("token") || ""}`
            },
            body: JSON.stringify(datos)
        });

        // ==========================
        // MANEJO HTTP
        // ==========================
        if (response.status === 401) {
            alert("Sesión expirada. Inicie sesión nuevamente.");
            window.location.href = "../../index.php";
            return;
        }

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        // ==========================
        // VALIDAR RESPUESTA API
        // ==========================
        if (!data || data.status === "error") {
            alert(data?.message || "Error al procesar la información");
            return;
        }

        // ==========================
        // TABLA
        // ==========================
        const tabla = document.getElementById("tabla-reporte2");
        if (!tabla) return;

        // ==========================
        // DESTRUIR DATATABLE
        // ==========================
        if ($.fn.DataTable.isDataTable('#tabla-reporte2')) {
            $('#tabla-reporte2').DataTable().destroy();
        }

        let tbody = tabla.querySelector('tbody');
        if (!tbody) {
            tbody = document.createElement('tbody');
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";

        let totalPrestamos = 0;

        data.forEach(item => {

            const valorPrestado = Number(item.valor_prestado) || 0;
            const futuro        = Number(item.futuro) || 0;
            const pagado        = Number(item.pagado) || 0;
            const pendiente     = item.pendiente == null ? 0 : Number(item.pendiente);

            totalPrestamos += valorPrestado;

            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${item.id_prestamo}</td>
                <td>${item.sociedad}</td>
                <td>${item.nombres}</td>
                <td>${item.ficha}</td>
                <td>${item.fecha_prestamo}</td>
                <td>${item.tipo}</td>
                <td>${item.interes}</td>
                <td>${item.tiempo}</td>
                <td>${formatearPesos(valorPrestado)}</td>
                <td>${formatearPesos(futuro)}</td>
                <td>${formatearPesos(pagado)}</td>
                <td>${formatearPesos(pendiente)}</td>
                <td>${item.estado}</td>
            `;
        });

        // ==========================
        // TOTAL
        // ==========================
        const totalElement = document.getElementById("total-gastos");
        if (totalElement) {
            totalElement.innerHTML = `Total Préstamos: $${totalPrestamos.toFixed(2)}`;
        }

        // ==========================
        // DATATABLE
        // ==========================
        $('#tabla-reporte2').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true, // 🔥 clave para evitar errores
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Reporte_Prestamos',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

    } catch (error) {
        console.error("Error:", error);
        alert("Error al listar préstamos por fechas");
    }
}

// Reporte numero 4
async function reporteFicha(){
const ficha = document.getElementById('ficha').value; 

  await  listarReporteFicha(ficha);
  await  listarReporteCuotas(ficha);
}


// ==========================
// Reporte cuotas por ficha
// ==========================
async function listarReporteCuotas(ficha) {

    try {
        // ==========================
        // VALIDACIÓN
        // ==========================
        if (!ficha) {
            alert("Debe seleccionar una ficha");
            return;
        }

        const params = new URLSearchParams({ ficha });

        // ==========================
        // PETICIÓN
        // ==========================
        const response = await fetch(
            BASE_URL + "view/reportes/reporteCuotas.php?" + params.toString(),
            {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("token") || ""}`
                }
            }
        );

        // ==========================
        // MANEJO HTTP
        // ==========================
        if (response.status === 401) {
            alert("Sesión expirada");
            window.location.href = "../../index.php";
            return;
        }

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        // ==========================
        // VALIDAR RESPUESTA API
        // ==========================
        if (!data || data.status === "error") {
            alert(data?.message || "Error al obtener cuotas");
            return;
        }

        const tabla = document.getElementById("tabla-cuotas");
        if (!tabla) return;

        // ==========================
        // DESTRUIR DATATABLE
        // ==========================
        if ($.fn.DataTable.isDataTable('#tabla-cuotas')) {
            $('#tabla-cuotas').DataTable().destroy();
        }

        let tbody = tabla.querySelector("tbody");
        if (!tbody) {
            tbody = document.createElement("tbody");
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";

        let valor_futuro = 0;
        let valor_pagado = 0;
        let valor_pendiente = 0;

        // ==========================
        // RENDER
        // ==========================
        data.forEach(item => {

            const valor = Number(item.valor) || 0;

            valor_futuro += valor;

            if (item.estado === "pagado") {
                valor_pagado += valor;
            }

            if (item.estado === "pendiente") {
                valor_pendiente += valor;
            }

            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${item.id_cuota}</td>
                <td>${item.nro_cuota}</td>
                <td>${item.fecha_pago}</td>
                <td>${formatearPesos(valor)}</td>
                <td>${item.tipo}</td>
                <td>${item.estado}</td>
            `;
        });

        // ==========================
        // (Opcional) Mostrar totales
        // ==========================
        console.log({
            futuro: valor_futuro,
            pagado: valor_pagado,
            pendiente: valor_pendiente
        });

        // ==========================
        // DATATABLE
        // ==========================
        $('#tabla-cuotas').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true
        });

    } catch (error) {
        console.error("Error:", error);
        alert("Error al listar cuotas");
    }
}


async function listarReporteFicha(fichaId) {

    try {
        // ==========================
        // VALIDACIÓN
        // ==========================
        if (!fichaId) {
            alert("Debe seleccionar una ficha");
            return;
        }

        const params = new URLSearchParams({ ficha: fichaId });

        // ==========================
        // PETICIÓN
        // ==========================
        const response = await fetch(
            BASE_URL + "view/reportes/reportePorFicha.php?" + params.toString(),
            {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("token") || ""}`
                }
            }
        );

        // ==========================
        // MANEJO HTTP
        // ==========================
        if (response.status === 401) {
            alert("Sesión expirada");
            window.location.href = "../../index.php";
            return;
        }

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        // ==========================
        // VALIDAR RESPUESTA
        // ==========================
        if (!data || data.status === "error") {
            alert(data?.message || "Error al obtener datos");
            return;
        }

        const tabla = document.getElementById("tabla-reporte");
        if (!tabla) return;

        // ==========================
        // DESTRUIR DATATABLE
        // ==========================
        if ($.fn.DataTable.isDataTable('#tabla-reporte')) {
            $('#tabla-reporte').DataTable().destroy();
        }

        let tbody = tabla.querySelector("tbody");
        if (!tbody) {
            tbody = document.createElement("tbody");
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";

        // ==========================
        // RENDER
        // ==========================
        data.forEach(item => {

            const valorPrestado = Number(item.valor_prestado) || 0;
            const futuro        = Number(item.futuro) || 0;
            const pagado        = Number(item.pagado) || 0;
            const pendiente     = item.pendiente == null ? 0 : Number(item.pendiente);

            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${item.id_prestamo}</td>
                <td>${item.sociedad}</td>
                <td>${item.nombres}</td>
                <td>${item.ficha}</td>
                <td>${item.fecha_prestamo}</td>
                <td>${item.tipo}</td>
                <td>${item.interes}</td>
                <td>${item.tiempo}</td>
                <td>${formatearPesos(valorPrestado)}</td>
                <td>${formatearPesos(futuro)}</td>
                <td>${formatearPesos(pagado)}</td>
                <td>${formatearPesos(pendiente)}</td>
                <td>${item.estado}</td>
            `;
        });

        // ==========================
        // DATATABLE
        // ==========================
        $('#tabla-reporte').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true
        });

    } catch (error) {
        console.error("Error:", error);
        alert("Error al listar reporte por ficha");
    }
}

// Reporte numero 5
// ==========================
// Reporte #5 - Cuotas vencidas
// ==========================
async function reporteCuotaVencidas() {

    try {
        // ==========================
        // CAPTURA Y VALIDACIÓN
        // ==========================
        const sociedadInput = document.getElementById('sociedad');
        if (!sociedadInput) {
            console.warn("Elemento #sociedad no existe");
            return;
        }

        const sociedad = sociedadInput.value.trim();

        if (!sociedad) {
            alert("Debe seleccionar una sociedad");
            return;
        }

        const params = new URLSearchParams({ sociedad });

        // ==========================
        // PETICIÓN
        // ==========================
        const response = await fetch(
            `${BASE_URL}view/reportes/cuotaVencida.php?${params.toString()}`,
            {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("token") || ""}`
                }
            }
        );

        // ==========================
        // MANEJO HTTP
        // ==========================
        if (response.status === 401) {
            alert("Sesión expirada. Inicie sesión nuevamente.");
            window.location.href = "../../index.php";
            return;
        }

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        // ==========================
        // VALIDAR RESPUESTA API
        // ==========================
        if (!data || data.status === "error") {
            alert(data?.message || "Error al obtener cuotas vencidas");
            return;
        }

        // 👉 soporte por si backend devuelve { data: [...] }
        const lista = Array.isArray(data) ? data : data.data;

        if (!Array.isArray(lista)) {
            throw new Error("Formato de respuesta inválido");
        }

        const tabla = document.getElementById("tabla-reporte");
        if (!tabla) {
            console.warn("Tabla no encontrada");
            return;
        }

        // ==========================
        // DESTRUIR DATATABLE
        // ==========================
        if ($.fn.DataTable.isDataTable('#tabla-reporte')) {
            $('#tabla-reporte').DataTable().destroy();
        }

        let tbody = tabla.querySelector('tbody');
        if (!tbody) {
            tbody = document.createElement('tbody');
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";

        // ==========================
        // RENDER
        // ==========================
        lista.forEach(item => {

            const valorPrestado = Number(item.valor_prestado) || 0;
            const valorCuota    = Number(item.valor) || 0;

            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${item.ficha || ''}</td>
                <td>${item.nombres || ''}</td>
                <td>${item.telefono || ''}</td>
                <td>${formatearPesos(valorPrestado)}</td>
                <td>${item.nro_cuota || ''}</td>
                <td>${item.fecha_pago || ''}</td>
                <td>${formatearPesos(valorCuota)}</td>
                <td>${item.tipo || ''}</td>
                <td>${item.estado || ''}</td>
            `;
        });

        // ==========================
        // DATATABLE
        // ==========================
        $('#tabla-reporte').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Reporte_Cuotas_Vencidas',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

    } catch (error) {
        console.error("Error en reporteCuotaVencidas:", error);
        alert("Error al listar cuotas vencidas");
    }
}

// ==========================
// Reporte historial cliente
// ==========================
async function reporteHistorialCliente() {

    try {
        // ==========================
        // CAPTURA Y VALIDACIÓN
        // ==========================
        const input = document.getElementById("identificacion");

        if (!input) {
            console.warn("Campo #identificacion no encontrado");
            return;
        }

        const identificacion = input.value.trim();

        if (!identificacion) {
            alert("Debe ingresar una identificación");
            return;
        }

        const params = new URLSearchParams({ identificacion });

        // ==========================
        // PETICIÓN
        // ==========================
        const response = await fetch(
            `${BASE_URL}view/reportes/historialCliente.php?${params.toString()}`,
            {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("token") || ""}`
                }
            }
        );

        // ==========================
        // MANEJO HTTP
        // ==========================
        if (response.status === 401) {
            alert("Sesión expirada");
            window.location.href = "../../index.php";
            return;
        }

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        // ==========================
        // VALIDAR RESPUESTA API
        // ==========================
        if (!data || data.status === "error") {
            alert(data?.message || "Error al obtener historial");
            return;
        }

        // Soporte para { data: [...] }
        const lista = Array.isArray(data) ? data : data.data;

        if (!Array.isArray(lista)) {
            throw new Error("Formato de respuesta inválido");
        }

        const tabla = document.getElementById("tabla-reporte");
        if (!tabla) return;

        // ==========================
        // DESTRUIR DATATABLE
        // ==========================
        if ($.fn.DataTable.isDataTable('#tabla-reporte')) {
            $('#tabla-reporte').DataTable().destroy();
        }

        let tbody = tabla.querySelector("tbody");
        if (!tbody) {
            tbody = document.createElement("tbody");
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";

        // ==========================
        // RENDER
        // ==========================
        lista.forEach(item => {

            const valorPrestado = Number(item.valor_prestado) || 0;
            const futuro        = Number(item.futuro) || 0;
            const pagado        = Number(item.pagado) || 0;
            const pendiente     = item.pendiente == null ? 0 : Number(item.pendiente);

            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${item.id_prestamo || ''}</td>
                <td>${item.sociedad || ''}</td>
                <td>${item.nombres || ''}</td>
                <td>${item.ficha || ''}</td>
                <td>${item.fecha_prestamo || ''}</td>
                <td>${item.tipo || ''}</td>
                <td>${item.interes || ''}</td>
                <td>${item.tiempo || ''}</td>
                <td>${formatearPesos(valorPrestado)}</td>
                <td>${formatearPesos(futuro)}</td>
                <td>${formatearPesos(pagado)}</td>
                <td>${formatearPesos(pendiente)}</td>
                <td>${item.estado || ''}</td>
            `;
        });

        // ==========================
        // DATATABLE
        // ==========================
        $('#tabla-reporte').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Reporte_Historial_Cliente',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

    } catch (error) {
        console.error("Error en historial cliente:", error);
        alert("Error al obtener historial del cliente");
    }
}


// ==========================
// Movimientos por sociedad
// ==========================
async function listarMovimientosPorSociedad() {

    try {
        // ==========================
        // CAPTURA Y VALIDACIÓN
        // ==========================
        const select = document.getElementById('sociedad');

        if (!select) {
            console.warn("Elemento #sociedad no existe");
            return;
        }

        const idSociedad = select.value.trim();

        if (!idSociedad) {
            alert('Por favor, seleccione una sociedad');
            return;
        }

        const params = new URLSearchParams({ id_sociedad: idSociedad });

        // ==========================
        // PETICIÓN
        // ==========================
        const response = await fetch(
            `${BASE_URL}view/reportes/listarMovimientosPorSociedad.php?${params.toString()}`,
            {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("token") || ""}`
                }
            }
        );

        // ==========================
        // MANEJO HTTP
        // ==========================
        if (response.status === 401) {
            alert("Sesión expirada");
            window.location.href = "../../index.php";
            return;
        }

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        // ==========================
        // VALIDAR RESPUESTA API
        // ==========================
        if (!data || data.status === "error") {
            alert(data?.message || "Error al obtener movimientos");
            return;
        }

        // Soporte flexible (array o {data: []})
        const lista = Array.isArray(data) ? data : data.data;

        if (!Array.isArray(lista)) {
            throw new Error("Formato de respuesta inválido");
        }

        const tabla = document.getElementById("tabla-reporte");
        if (!tabla) return;

        // ==========================
        // DESTRUIR DATATABLE
        // ==========================
        if ($.fn.DataTable.isDataTable('#tabla-reporte')) {
            $('#tabla-reporte').DataTable().destroy();
        }

        let tbody = tabla.querySelector('tbody');
        if (!tbody) {
            tbody = document.createElement('tbody');
            tabla.appendChild(tbody);
        }

        tbody.innerHTML = "";

        // ==========================
        // RENDER
        // ==========================
        lista.forEach(item => {

            const valor = Number(item.valor) || 0;
            const caja  = Number(item.caja) || 0;

            const row = tbody.insertRow();

            row.innerHTML = `
                <td>${item.id_movimiento || ''}</td>
                <td>${item.fecha || ''}</td>
                <td>${item.tipo || ''}</td>
                <td>${formatearPesos(valor)}</td>
                <td>${formatearPesos(caja)}</td>
                <td>${item.sociedad || ''}</td>
                <td>${item.detalle || ''}</td>
            `;
        });

        // ==========================
        // DATATABLE
        // ==========================
        $('#tabla-reporte').DataTable({
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Reporte_Movimientos',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

    } catch (error) {
        console.error("Error en listarMovimientosPorSociedad:", error);
        alert('Error al listar movimientos');
    }
}



window.gastoPorFecha = gastoPorFecha;
window.reporteCuotaVencidas = reporteCuotaVencidas;
window.reporteFicha = reporteFicha;




