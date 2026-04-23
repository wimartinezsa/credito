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
// 📦 LEER JSON (CLAVE)
// =========================
$input = json_decode(file_get_contents("php://input"), true);

// =========================
// 🟢 POST → CREAR USUARIO
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$input) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Datos inválidos o vacíos"
        ]);
        exit;
    }

    $identificacion = $input["identificacion"] ?? null;
    $nombres        = $input["nombres"] ?? null;
    $direccion      = $input["direccion"] ?? null;
    $telefono       = $input["telefono"] ?? null;
    $calificacion   = $input["calificacion"] ?? null;
    $observacion    = $input["observacion"] ?? null;

    // =========================
    // VALIDACIONES
    // =========================
    if (!$identificacion || !$nombres) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Datos obligatorios incompletos"
        ]);
        exit;
    }

    $resultado = $controller->insertar(
        identificacion: $identificacion,
        nombres: $nombres,
        direccion: $direccion,
        telefono: $telefono,
        calificacion: $calificacion,
        observacion: $observacion
    );

    echo json_encode([
        "status" => "success",
        "message" => "Usuario registrado correctamente",
        "data" => $resultado
    ]);
}

// =========================
// 🔵 GET → LISTAR USUARIOS
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $resultado = $controller->listarTodos();

    echo json_encode([
        "status" => "success",
        "message" => "Listado de usuarios",
        "data" => $resultado
    ]);
}