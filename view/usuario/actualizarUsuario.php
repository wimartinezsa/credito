<?php

require_once("../../controller/usuarioController.php");
require_once("../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new usuarioController();

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

// =========================
// 🔄 CAPTURA DE DATOS
// =========================
$id = $_GET['id_usuario'] ?? null;

$ident = $input['identificacion'] ?? null;
$nombre = $input['nombres'] ?? null;
$direccion = $input['direccion'] ?? null;
$telefono = $input['telefono'] ?? null;
$calificacion = $input['calificacion'] ?? null;
$observacion = $input['observacion'] ?? null;

// =========================
// ⚠️ VALIDACIONES
// =========================
if (!$id) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Falta el id del usuario"
    ]);
    exit;
}

if (!$ident || !$nombre) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Datos obligatorios incompletos"
    ]);
    exit;
}

// =========================
// 🔄 PROCESO
// =========================
$result = $controller->actualizarUsuario(
    $id,
    $ident,
    $nombre,
    $direccion,
    $telefono,
    $calificacion,
    $observacion
);

// =========================
// 📤 RESPUESTA ESTÁNDAR
// =========================
echo json_encode([
    "status" => "success",
    "message" => "Usuario actualizado correctamente",
    "data" => $result
]);