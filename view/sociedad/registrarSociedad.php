<?php

   

require_once("../../controller/sociedadController.php");
require_once("../../controller/autenticacionController.php");
$controller_autenticacion = new autenticacionController();

$controller = new sociedadController();

header("Content-Type: application/json");

// =========================
// 🔐 VALIDAR TOKEN
// =========================
$headers = getallheaders();
$headers = array_change_key_case($headers, CASE_LOWER);

$authHeader = $headers['authorization'] ?? null;

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
// 📦 LEER JSON (IMPORTANTE)
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
    $sociedad = $input['sociedad'] ?? null;
    $valor = $input['valor'] ?? null;

    if ($sociedad === null || trim($sociedad) === '' || $valor === null || trim((string)$valor) === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Sociedad y valor son obligatorios'
        ]);
        exit;
    }

    try {
        $resultado = $controller->registrarSociedades($sociedad, $valor);
        echo json_encode([
            'success' => true,
            'message' => $resultado
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
    

?>