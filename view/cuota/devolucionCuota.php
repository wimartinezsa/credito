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
// 🔄 PROCESO DE DEVOLUCIÓN
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_cuota = $_GET['id_cuota'] ?? null;
    $valor_cuota = $_GET['valor_cuota'] ?? null;

    // VALIDACIONES
    if (!$id_cuota || $id_cuota <= 0) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "ID de cuota inválido"
        ]);
        exit;
    }

    if (!$valor_cuota || $valor_cuota <= 0) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Valor de cuota inválido"
        ]);
        exit;
    }

    // EJECUCIÓN
    $resultado = $controller->devolucionCuota($id_cuota, $valor_cuota);

    echo json_encode([
        "status" => "success",
        "message" => "Operación realizada correctamente",
        "data" => $resultado
    ]);
}