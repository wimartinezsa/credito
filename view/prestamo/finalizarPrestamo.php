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
// 🔄 FINALIZAR PRÉSTAMO
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_prestamo = $_GET['id_prestamo'] ?? null;

    if (!$id_prestamo) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "ID de préstamo no proporcionado"
        ]);
        exit;
    }

    $resultado = $controller->finalizarPrestamo($id_prestamo);

    echo json_encode($resultado);
}
?>