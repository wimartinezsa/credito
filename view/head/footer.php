   
   </div>

   
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
   <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
   
<!-- Buttons Extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<!-- JSZip (requerido para Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Botón Excel -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
   
   


  

</body>
<script>

document.addEventListener("DOMContentLoaded", function () {
    const nombre = localStorage.getItem("usuario");

    if (nombre) {
        document.getElementById("nombreUsuario").textContent = "👤 " + nombre;
    } else {
        document.getElementById("nombreUsuario").textContent = "Invitado";
    }
});




   function cerrarSesion() {
      // opcional alert de depuración
      // alert('cerrando sesión');
    fetch("../autenticacion/cerrarSesion.php", {
        method: 'GET',
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Sesión cerrada:', data);
        // redirigir de forma absoluta al inicio del proyecto
        window.location.href = '/Creditos/index.php';
    })
    .catch(error => {
        console.error('Error al cerrar sesión:', error);
    });

}</script>
</html>


