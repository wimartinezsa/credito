<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
   
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>



    <title>Creditos</title>
</head>
<body>



<div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">

          
                
              
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  
                   
                    <li class="nav-item">
                        <a class="nav-link" href="/creditos/view/prestamo/prestamo.php">Prestamos</a>
                    </li>

                   <li class="nav-item">
                    <a class="nav-link" href="/creditos/view/usuario/usuario.php">Clientes</a>
                    </li>

                      <li class="nav-item">
                    <a class="nav-link" href="/creditos/view/gastos/gasto.php">Gastos</a>
                    </li>

                       <li class="nav-item">
                    <a class="nav-link" href="/creditos/view/sociedad/sociedad.php">Sociedad</a>
                    </li>

                   <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Reportes
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/creditos/view/reportes/reporte1View.php">Estado de la Sociedad</a></li>
            <li><a class="dropdown-item" href="/creditos/view/reportes/reporte2View.php">Reporte de Gastos</a></li>
            <li><a class="dropdown-item" href="/creditos/view/reportes/reporte3View.php">Reporte de Prestamos</a></li>
            <li><a class="dropdown-item" href="/creditos/view/reportes/reporte4View.php">Reporte de Ficha</a></li>
             <li><a class="dropdown-item" href="/creditos/view/reportes/reporte5View.php">Cuotas Vencidas</a></li>
             <li><a class="dropdown-item" href="/creditos/view/reportes/reporte6View.php">Historial del Cliente</a></li>
            <li><a class="dropdown-item" href="/creditos/view/reportes/reporte7View.php">Creditos Negados</a></li>
          </ul>
        </li>
                   
                </ul>
                <form class="d-flex" role="search">
                    
                    <button class="btn btn-outline-success" type="button" onclick="cerrarSesion()">Cerrar</button>
                </form>
                </div>
            </div>
        </nav>

</div>