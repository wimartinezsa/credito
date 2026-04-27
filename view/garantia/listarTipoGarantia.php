

<?php
require_once("../../controller/garantiaController.php");
require_once("../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new garantiaController();

header("Content-Type: application/json");

// ==========================
// OBTENER HEADERS
// ==========================
$headers = function_exists('getallheaders') ? getallheaders() : [];
$headers = array_change_key_case($headers, CASE_LOWER);

$authHeader = $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? null;

// ==========================
// VALIDAR TOKEN
// ==========================
if (!$authHeader) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Token no enviado"
    ]);
    exit;
}

$token = preg_replace('/^Bearer\s/i', '', $authHeader);

$usuario = $controller_autenticacion->validarToken($token);

if (!$usuario) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Token inválido o expirado"
    ]);
    exit;
}

// ==========================
// MÉTODO GET
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
        $resultado = $controller->listarTipoGarantia();

        echo json_encode([
            "status" => "ok",
            "data" => $resultado
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Error interno",
            "detalle" => $e->getMessage()
        ]);
    }
}
?>