
<?php
require_once("../../controller/prestamoController.php");
require_once("../../controller/autenticacionController.php");

$controller_prestamo = new prestamoController();
$controller_autenticacion = new autenticacionController();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // 🔴 OBTENER HEADERS (FALTABA ESTO)
    $headers = getallheaders();

    // 🔴 NORMALIZAR (MUY IMPORTANTE)
    $headers = array_change_key_case($headers, CASE_LOWER);

    $authHeader = $headers['authorization'] ?? null;

    // 🔴 VALIDAR TOKEN
    if (!$authHeader) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token no enviado"
        ]);
        exit;
    }

    // 🔹 Extraer token
    $token = str_replace('Bearer ', '', $authHeader);

    // 🔹 Validar token
    $usuario = $controller_autenticacion->validarToken($token);

    if (!$usuario) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token inválido o expirado"
        ]);
        exit;
    }

    

    // 🔴 VALIDAR PARÁMETRO
    $id_sociedad = $_GET['id_sociedad'] ?? null;

    if (!$id_sociedad) {
        http_response_code(400);
        echo json_encode(["error" => "ID de sociedad no proporcionado"]);
        exit;
    }

    // 🔹 CONSULTA
    $resultado = $controller_prestamo->listarPrestamosId($id_sociedad);

    echo json_encode($resultado);
}
?>