

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
</head>
<body></body>

<!-- Centered login form using Bootstrap 5 utilities -->
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-sm p-4" style="max-width: 400px; width:100%;">
    <h4 class="card-title text-center mb-4">Iniciar Sesión</h4>
    <form>
      <div class="mb-3">
        <label for="identificacion" class="form-label">Login</label>
        <input type="number" class="form-control" id="identificacion" name="identificacion" placeholder="Ingrese su login">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
      </div>
      <button type="button" class="btn btn-primary w-100" onclick="login()">Ingresar</button>
    </form>
  </div>
</div>



<script src="./view/usuario/usuario.js"></script>

</body>
</html>