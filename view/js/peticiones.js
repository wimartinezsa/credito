

function peticionConsulta(url, method, body = null) {
    const token = localStorage.getItem("token");
   

    // 🔴 Validar token antes de enviar
    if (!token) {
        alert("Sesión no iniciada");
        window.location.href = "../../index.php";
        return Promise.reject(new Error("Token no encontrado"));
    }

    return fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${token}`
        },
        body: body ? JSON.stringify(body) : null
    })
    .then(async response => {

        const data = await response.json();

        // 🔴 Manejo real de 401
        if (response.status === 401) {
            alert(data.message || "Sesión expirada");
            localStorage.removeItem("token");
            window.location.href = "../../index.php";
            throw new Error("No autorizado");
        }

        if (!response.ok) {
            throw new Error(data.message || "Error en la petición");
        }

        return data; // ✅ SIEMPRE retorna JSON válido
    })
    .catch(error => {
        console.error("Error en fetch:", error);
        throw error;
    });
}






async function peticionCRUD(url, metodo, data = null) {

    const token = localStorage.getItem("token");

    try {

        const response = await fetch(url, {
            method: metodo,
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: data ? JSON.stringify(data) : null
        });

        // 🔴 manejo de sesión expirada
        if (response.status === 401) {
            alert('Sesión expirada. Por favor, inicie sesión nuevamente.');
            window.location.href = '../../index.php';
            return null;
        }

        // 🔴 convertir respuesta a JSON
        const result = await response.json();

        return result;

    } catch (err) {
        console.error("Error en peticionCRUD:", err);
        alert('Error al procesar la petición: ' + err.message);
        return null;
    }
}