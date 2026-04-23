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
// 🗑 ELIMINAR CUOTA (GET CON ID O POST RECOMENDADO)
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id_cuota'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "ID no proporcionado"
        ]);
        exit;
    }

    $resultado = $controller->eliminarCuota($id);

    echo json_encode([
        "status" => "success",
        "message" => "Cuota eliminada correctamente",
        "data" => $resultado
    ]);
}