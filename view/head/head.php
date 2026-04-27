<!DOCTYPE html>
<html lang="en">
<head style="bac">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
   
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



    <title>Creditos</title>

    <style>
        body {
            background-color: #f5f6f8; /* gris claro profesional */
        }
    </style>
</head>
<body>


<?php require_once '../../config.php'; ?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
            <div class="container-fluid">

          
                
              
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  
                   
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>view/prestamo/prestamo.php">Creditos</a>
                    </li>

                   <li class="nav-item">
                     <a class="nav-link" href="<?= BASE_URL ?>view/usuario/usuario.php">Clientes</a>
                    </li>

                      <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>view/gastos/gasto.php">Gastos</a>
                    </li>

 
                             <li id="sociedadNavItem" class="nav-item" style="display:none;">
                                <a class="nav-link" href="<?= BASE_URL ?>view/sociedad/sociedad.php">Sociedad</a>
                            </li>

                   <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Reportes
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= BASE_URL ?>view/reportes/reporte1View.php">Estado de la Sociedad</a></li>
            <li><a class="dropdown-item" href="<?= BASE_URL ?>view/reportes/reporte2View.php">Reporte de Gastos</a></li>
            <li><a class="dropdown-item" href="<?= BASE_URL ?>view/reportes/reporte3View.php">Reporte de Prestamos</a></li>
            <li><a class="dropdown-item" href="<?= BASE_URL ?>view/reportes/reporte4View.php">Reporte de Ficha</a></li>
             <li><a class="dropdown-item" href="<?= BASE_URL ?>view/reportes/reporte5View.php">Cuotas Vencidas</a></li>
             <li><a class="dropdown-item" href="<?= BASE_URL ?>view/reportes/reporte6View.php">Historial del Cliente</a></li>
            <li><a class="dropdown-item" href="<?= BASE_URL ?>view/reportes/reporte7View.php">Historial de Movimientos</a></li>
          </ul>
        </li>
                   
                </ul>
                <form class="d-flex align-items-center" role="search">
    <span id="nombreUsuario" class="text-white me-3"></span>
    <button class="btn btn-primary" type="button" onclick="cerrarSesion()">Cerrar</button>
</form>
                </div>
            </div>
    </nav>

</div>
<script>
function cerrarSesion() {
    // Eliminar el token del almacenamiento local
    localStorage.removeItem('token');
    // Redirigir al usuario a la página de inicio de sesión
    window.location.href = '<?= BASE_URL ?>view/login/login.php';
}
// Función para mostrar el nombre del usuario en la barra de navegación
/*
function mostrarNombreUsuario() {
    const token = localStorage.getItem('token');
    if (token) {
        // Decodificar el token para obtener la información del usuario
        const payload = JSON.parse(atob(token.split('.')[1]));
        const nombreUsuario = payload.nombre; // Asegúrate de que el token tenga un campo 'nombre'
        document.getElementById('nombreUsuario').textContent = nombreUsuario;
    }
}
*/
// Llamar a la función para mostrar el nombre del usuario al cargar la página
//mostrarNombreUsuario();

// Función para mostrar u ocultar el enlace de Sociedad según el rol del usuario
function mostrarEnlaceSociedad() {
    const rol = localStorage.getItem('rol');
    if (rol === 'admin') { // Cambia 'admin' por el rol que corresponda
        document.getElementById('sociedadNavItem').style.display = 'block';
        } else {
            document.getElementById('sociedadNavItem').style.display = 'none';
        }
    }

// Llamar a la función para mostrar u ocultar el enlace de Sociedad al cargar la página
mostrarEnlaceSociedad();


</script>