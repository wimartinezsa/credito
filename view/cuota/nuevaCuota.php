<?php


    require_once("../../controller/cuotaController.php");
    require_once("../../controller/autenticacionController.php");
    $controller_autenticacion = new autenticacionController();
    $controller = new cuotaController();
    
    
    header("Content-Type: application/json");

// =========================
// 🔐 VALIDAR TOKEN
// =========================
$headers = getallheaders();
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

$token = str_replace('Bearer ', '', $authHeader);

$usuario = $controller_autenticacion->validarToken($token);

if (!$usuario) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Token inválido o expirado"
    ]);
    exit;
}

// =========================
// 📦 LEER JSON
// =========================
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Datos inválidos o vacíos"
    ]);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $resultado = $controller->nuevaCuota(
        $input["id_prestamo"]??null,
        $input["nro_pago"]??null,
        $input["fecha_pago"]??null, 
        $input["valor_pago"]??null, 
        $input["tipo_pago"]??null
    );
    echo json_encode($resultado);
}


    
?>