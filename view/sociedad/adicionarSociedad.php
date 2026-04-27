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

$authHeader = $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? null;

if (!$authHeader) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Token no enviado"
    ]);
    exit;
}

$token = str_replace('Bearer ', '', $authHeader);
$usuario = $controller_autenticacion->validarToken($token);

if (!$usuario) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Token inválido o expirado"
    ]);
    exit;
}

// =========================
// 📦 VALIDAR MÉTODO
// =========================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
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
        "success" => false,
        "message" => "Datos inválidos o vacíos"
    ]);
    exit;
}

// =========================
// 📌 VALIDACIONES
// =========================
$sociedad = $input['sociedad'] ?? null;
$valor = $input['valor'] ?? null;
$id = $input['id_sociedad'] ?? null;

if (!$sociedad) {
    echo json_encode([
        "success" => false,
        "message" => "El nombre de la sociedad es requerido"
    ]);
    exit;
}

if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "El ID de la sociedad es requerido"
    ]);
    exit;
}

if (!$valor) {
    echo json_encode([
        "success" => false,
        "message" => "El valor de la sociedad es requerido"
    ]);
    exit;
}

// =========================
// 🚀 PROCESO
// =========================
$resultado = $controller->adicionarSociedad($id, $sociedad, $valor);

if ($resultado) {
    echo json_encode([
        "success" => true,
        "message" => "Sociedad actualizada correctamente"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error al actualizar la sociedad"
    ]);
}