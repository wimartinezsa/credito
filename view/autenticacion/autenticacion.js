function login(){
  
    let datos= new URLSearchParams();
    datos.append('identificacion',document.getElementById('identificacion').value);
    datos.append('password',document.getElementById('password').value);

    fetch(BASE_URL + 'view/autenticacion/login.php', {
        method: 'POST',
        body: datos
    })
    .then(response => response.json())
    .then(data => {
        console.log('Datos recibidos del servidor:', data);
      
        if (data && data.token) {
            localStorage.setItem("token", data.token);
            localStorage.setItem("usuario", JSON.stringify(data.usuario));

            alert('Login exitoso');

            window.location.href = BASE_URL + 'view/prestamo/prestamo.php';
        } else {
            alert('Credenciales incorrectas');
        }
    })
    .catch(err => alert('Error: ' + err));
}