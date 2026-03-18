function login(){
  
    let datos= new URLSearchParams();
    datos.append('identificacion',document.getElementById('identificacion').value);
    datos.append('password',document.getElementById('password').value);
    fetch('./view/autenticacion/login.php', {
        method: 'POST',
        body: datos
    })
    .then(r => r.json())
    .then(data => {
       console.log('Datos recibidos del servidor:', data);
      
        if (data && data.token) {
           // console.log('token:', data.token);
            localStorage.setItem("token", data.token);
            // optionally store user info
            localStorage.setItem("usuario", JSON.stringify(data.usuario));
            alert('Login exitoso');
            // redirigir a página protegida
             window.location.href = 'http://localhost/creditos/view/prestamo/prestamo.php';
        } else {
            alert('Credenciales incorrectas');
        }
    })
    .catch(err => alert('Error: ' + err));
}
