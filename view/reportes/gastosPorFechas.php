<?php

require_once("../../controller/reporteController.php");
require_once("../../controller/autenticacionController.php");

header("Content-Type: application/json");

$controller = new reporteController();
$authController = new autenticacionController();

// ==========================
// OBTENER HEADERS (COMPATIBLE)
// ==========================
$headers = [];

if (function_exists('getallheaders')) {
    $headers = getallheaders();
} else {
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) === 'HTTP_') {
            $headers[strtolower(str_replace('_', '-', substr($name, 5)))] = $value;
        }
    }
}

$headers = array_change_key_case($headers, CASE_LOWER);

// ==========================
// VALIDAR TOKEN
// ==========================
$authHeader = $headers['authorization'] ?? null;

if (!$authHeader) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Token no enviado"
    ]);
    exit;
}

// Validar formato Bearer correctamente
if (!preg_match('/^Bearer\s(\S+)$/i', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Formato de token inválido"
    ]);
    exit;
}

$token = $matches[1];

// 🔥 ERROR CORREGIDO: variable mal nombrada
$usuario = $authController->validarToken($token);

if (!$usuario) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Token inválido o expirado"
    ]);
    exit;
}

// ==========================
// VALIDAR MÉTODO
// ==========================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido"
    ]);
    exit;
}

// ==========================
// RECIBIR DATOS (JSON + POST)
// ==========================
$input = json_decode(file_get_contents("php://input"), true);

$fecha_inicio = $input['fecha_inicio'] ?? $_POST['fecha_inicio'] ?? null;
$fecha_fin    = $input['fecha_fin'] ?? $_POST['fecha_fin'] ?? null;
$sociedad     = $input['sociedad'] ?? $_POST['sociedad'] ?? null;

// ==========================
// VALIDACIONES
// ==========================
if (!$fecha_inicio || !$fecha_fin || !$sociedad) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan parámetros"
    ]);
    exit;
}

// Validar formato de fechas
if (!strtotime($fecha_inicio) || !strtotime($fecha_fin)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Formato de fecha inválido"
    ]);
    exit;
}

// Validar sociedad
if (!is_numeric($sociedad)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Sociedad inválida"
    ]);
    exit;
}

// ==========================
// EJECUTAR LÓGICA
// ==========================
try {

    $resultado = $controller->listarGastosPorFechas(
        $fecha_inicio,
        $fecha_fin,
        $sociedad
    );

    echo json_encode($resultado);
    exit;

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error interno del servidor",
        "detalle" => $e->getMessage() // quitar en producción
    ]);
}
?>