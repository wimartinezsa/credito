







let dataTableInstance = null;

// Reporte numero 1

function listarSociedades(){
    

    fetch("../sociedad/listarSociedad.php", {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        const select = document.getElementById('sociedad');
        select.innerHTML = '<option value="">Seleccionar Sociedad</option>';
        data.forEach(sociedad => {
            const option = document.createElement('option');
            option.value = sociedad.id_sociedad;
            option.textContent = sociedad.sociedad;
            select.appendChild(option);
        });
    })
    .catch(err => {
        console.error(err);
        alert('Error al listar: ' + err);
    });
    
}


function estadoSociedad(){

    const idSociedad = document.getElementById('sociedad').value;
    if (!idSociedad) {
        alert('Por favor, seleccione una sociedad');
        return;
    }
    fetch(`./estadoSociedad.php?id_sociedad=${idSociedad}`, {
        method: 'GET'
    })
    .then(response => response.json())
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



          
            document.getElementById('estado-sociedad').innerHTML = estadosDiv;
       
    });
}



// Reporte numero 2
function gastoPorFecha(){

    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;

    const params = new URLSearchParams({
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin
    });

    fetch("./gastosPorFechas.php?" + params.toString(), {
        method: 'GET'
    })
    .then(response => response.json())
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
                <td>${usuario.valor}</td>
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

    })
    .catch(err => {
        console.error(err);
        alert('Error al listar: ' + err);
    });
}


// Reporte numero 3
function listarPrestamosPorFechas(){

    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;

    const params = new URLSearchParams({
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin
    });

    fetch("./prestamosPorFecha.php?" + params.toString(), {
        method: 'GET'
    })
    .then(response => response.json())
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
    let totalPrestamos = 0;
    //p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres
        data.forEach(usuario => {
            const row = tbody.insertRow();
            totalPrestamos += parseFloat(usuario.valor_prestado);
            row.innerHTML = `
                <td>${usuario.id_prestamo}</td>
                <td>${usuario.sociedad}</td>
                 <td>${usuario.nombres}</td>
                <td>${usuario.ficha}</td>
                <td>${usuario.fecha_prestamo}</td>
                 <td>${usuario.tipo}</td>
                <td>${usuario.interes}</td>
                <td>${usuario.tiempo}</td>
                <td>${usuario.valor_prestado}</td>
                <td>${usuario.futuro}</td>
                <td>${usuario.pagado}</td>
                <td>${usuario.pendiente===null ? 0 : usuario.pendiente}</td>
                <td>${usuario.estado}</td>
               

            `;
        });

       document.getElementById("total-gastos").innerHTML = "Total Prestamos: $" + totalPrestamos.toFixed(2);

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
            title: 'Reporte_Prestamos',
            exportOptions: {
                columns: ':visible'
            }
        }
    ]
});

    })
    .catch(err => {
        console.error(err);
        alert('Error al listar prestamos por fechas: ' + err);
    });


}

// Reporte numero 4
async function reporteFicha(){
const ficha = document.getElementById('ficha').value; 

  await  listarReporteFicha(ficha);
  await  listarReporteCuotas(ficha);
}


function listarReporteCuotas(ficha){

    fetch(`./reporteCuotas.php?ficha=${ficha}`, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {

        const tabla = document.getElementById("tabla-cuotas");
        if (!tabla) return; 

        // Destruir DataTable anterior
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
                <td>${cuota.id_cuota}</td>
                <td>${cuota.mes}</td>
                <td>${cuota.fecha_cuota}</td>
                <td>${cuota.valor}</td>
                <td>${cuota.tipo}</td>
               <td>${cuota.estado}</td>
            `;
        });
       
        dataTableInstance = $('#tabla-cuotas').DataTable({
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


function listarReporteFicha(ficha){

    const params = new URLSearchParams({
        ficha: ficha
    });

    fetch("./reportePorFicha.php?" + params.toString(), {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
     
        const tabla = document.getElementById("tabla-reporte");
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
   
    //p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres
        data.forEach(ficha => {
            const row = tbody.insertRow();
         
            row.innerHTML = `
                <td>${ficha.id_prestamo}</td>
                <td>${ficha.sociedad}</td>
                 <td>${ficha.nombres}</td>
                <td>${ficha.ficha}</td>
                <td>${ficha.fecha_prestamo}</td>
                 <td>${ficha.tipo}</td>
                <td>${ficha.interes}</td>
                <td>${ficha.tiempo}</td>
                <td>${ficha.valor_prestado}</td>
                <td>${ficha.futuro}</td>
                <td>${ficha.pagado}</td>
                <td>${ficha.pendiente===null ? 0 : ficha.pendiente}</td>
                <td>${ficha.estado}</td>
               

            `;
        });

    

       dataTableInstance = $('#tabla-reporte').DataTable({
    pageLength: 10,
    searching: true,
    ordering: true,
    paging: true,
});

    })
    .catch(err => {
        console.error(err);
        alert('Error al listar prestamos por fechas: ' + err);
    });



}

// Reporte numero 5
function reporteCuotaVencidas(){

    fetch("./cuotaVencida.php", {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
       
        console.log(data);
        const tabla = document.getElementById("tabla-reporte");
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
   
    //p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres
        data.forEach(cuota => {
            const row = tbody.insertRow();
         
            row.innerHTML = `
                <td>${cuota.ficha}</td>
                <td>${cuota.nombres}</td>
                  <td>${cuota.telefono}</td>
                <td>${cuota.valor_prestado}</td>
                <td>${cuota.mes}</td>
                <td>${cuota.fecha_cuota}</td>
                <td>${cuota.valor}</td>
                <td>${cuota.tipo}</td>
                <td>${cuota.estado}</td>
            `;
        });

    

       dataTableInstance = $('#tabla-reporte').DataTable({
    pageLength: 10,
    searching: true,
    ordering: true,
    paging: true,
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

    })
    .catch(err => {
        console.error(err);
        alert('Error al listar cuotas vencidas: ' + err);
    });

}



function reporteHistorialCliente(){

    const identificacion = document.getElementById("identificacion").value;

    const params = new URLSearchParams({
        identificacion: identificacion
    });

    fetch("./historialCliente.php?" + params.toString(), {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
       
        console.log(data);
        const tabla = document.getElementById("tabla-reporte");
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
   
    //p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres
        data.forEach(ficha => {
            const row = tbody.insertRow();
         
                row.innerHTML = `
                <td>${ficha.id_prestamo}</td>
                <td>${ficha.sociedad}</td>
                 <td>${ficha.nombres}</td>
                <td>${ficha.ficha}</td>
                <td>${ficha.fecha_prestamo}</td>
                 <td>${ficha.tipo}</td>
                <td>${ficha.interes}</td>
                <td>${ficha.tiempo}</td>
                <td>${ficha.valor_prestado}</td>
                <td>${ficha.futuro}</td>
                <td>${ficha.pagado}</td>
                <td>${ficha.pendiente===null ? 0 : ficha.pendiente}</td>
                <td>${ficha.estado}</td>
            `;


        });

    

       dataTableInstance = $('#tabla-reporte').DataTable({
    pageLength: 10,
    searching: true,
    ordering: true,
    paging: true,
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

    })
    .catch(err => {
        console.error(err);
        alert('Error al listar cuotas vencidas: ' + err);
    });

}



function reporteCreditoNegado(){

  

    fetch("./creditoNegado.php", {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
       
        const tabla = document.getElementById("tabla-reporte");
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
   
    //p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres
        data.forEach(prestamo => {
            const row = tbody.insertRow();
         
                row.innerHTML = `
                <td>${prestamo.id_prestamo}</td>
                <td>${prestamo.sociedad}</td>
                 <td>${prestamo.nombres}</td>
                <td>${prestamo.ficha}</td>
                <td>${prestamo.fecha_prestamo}</td>
                 <td>${prestamo.tipo}</td>
                <td>${prestamo.interes}</td>
                <td>${prestamo.tiempo}</td>
                <td>${prestamo.valor_prestado}</td>
                <td>${prestamo.estado}</td>
            `;


        });

    

       dataTableInstance = $('#tabla-reporte').DataTable({
    pageLength: 10,
    searching: true,
    ordering: true,
    paging: true,
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

    })
    .catch(err => {
        console.error(err);
        alert('Error al listar cuotas vencidas: ' + err);
    });

}



window.gastoPorFecha = gastoPorFecha;
window.reporteCuotaVencidas = reporteCuotaVencidas;
window.reporteFicha = reporteFicha;




