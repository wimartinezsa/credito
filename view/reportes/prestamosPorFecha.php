<?php

require_once("../../controller/reporteController.php");
require_once("../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new reporteController();

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


// ==========================
// VALIDAR DATOS
// ==========================
$fecha_inicio = $input['fecha_inicio'] ?? null;
$fecha_fin    = $input['fecha_fin'] ?? null;
$sociedad     = $input['sociedad'] ?? null;

if (!$fecha_inicio || !$fecha_fin || !$sociedad) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan parámetros"
    ]);
    exit;
}

// ==========================
// EJECUTAR
// ==========================
try {

    $resultado = $controller->listarPrestamosPorFechas(
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
        "message" => "Error interno",
        "detalle" => $e->getMessage()
    ]);
}