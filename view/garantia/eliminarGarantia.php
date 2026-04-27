

<?php
require_once("../../controller/garantiaController.php");
require_once("../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new garantiaController();

header("Content-Type: application/json");

// ==========================
// VALIDAR TOKEN
// ==========================
$headers = function_exists('getallheaders') ? getallheaders() : [];
$headers = array_change_key_case($headers, CASE_LOWER);

$authHeader = $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? null;

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
// MÉTODO POST
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ==========================
    // LEER JSON
    // ==========================
    $input = json_decode(file_get_contents("php://input"), true);

    $id_garantia = $input['id_garantia'] ?? $_POST['id_garantia'] ?? null;

    // ==========================
    // VALIDACIÓN
    // ==========================
    if (!$id_garantia) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Falta id_garantia"
        ]);
        exit;
    }

    if (!is_numeric($id_garantia)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "ID inválido"
        ]);
        exit;
    }

    try {

        $resultado = $controller->eliminarGarantia($id_garantia);

        echo json_encode([
            "status" => "ok",
            "message" => "Garantía eliminada correctamente",
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

} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido"
    ]);
}
?>