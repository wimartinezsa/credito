<?php

require_once("../../controller/prestamoController.php");
require_once("../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new prestamoController();

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
// 📦 LEER JSON DEL FRONTEND
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
// 🚀 REGISTRAR PRÉSTAMO
// =========================
$resultado = $controller->registrarPrestamo(
    sociedad: $input["sociedad"] ?? null,
    ficha: $input["ficha"] ?? null,
    cliente: $input["cliente"] ?? null,
    fecha: $input["fecha"] ?? null,
    tiempo: $input["tiempo"] ?? null,
    valor: $input["valor"] ?? null,
    interes: $input["interes"] ?? null,
    tipo: $input["tipo"] ?? null,
    fiador: $input["fiador"] ?? null,
    estado: $input["estado"] ?? null
);

// =========================
// 📤 RESPUESTA FINAL
// =========================
echo json_encode($resultado);

?>