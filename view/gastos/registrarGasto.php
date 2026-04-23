<?php

require_once("../../controller/gastoController.php");
require_once(__DIR__ . "/../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new gastoController();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$input) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Datos inválidos o vacíos"
        ]);
        exit;
    }

    $sociedad = $input['sociedad'] ?? null;
    $fecha = $input['fecha'] ?? null;
    $detalle = $input['detalle'] ?? null;
    $valor = $input['valor'] ?? null;

    // =========================
    // ⚠️ VALIDACIÓN
    // =========================
    if (!$sociedad || !$fecha || !$detalle || !$valor) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Datos incompletos"
        ]);
        exit;
    }

    // =========================
    // 💾 PROCESO
    // =========================
    $resultado = $controller->registrarGasto(
        $sociedad,
        $fecha,
        $detalle,
        $valor
    );

    // =========================
    // 📤 RESPUESTA
    // =========================
    if ($resultado) {
        echo json_encode([
            "status" => "success",
            "message" => "Gasto registrado correctamente",
            "data" => null
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No se pudo registrar el gasto"
        ]);
    }
}
?>