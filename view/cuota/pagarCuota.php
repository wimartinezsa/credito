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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $resultado = $controller->pagarCuota(
       id_cuota_pago: $input["id_cuota_pago"]?? null,
       valor_pagado: $input["valor_pagado"]?? null,
       fecha_recaudo: $input["fecha_recaudo"]?? null
    );
    
    // =========================
// 📤 RESPUESTA FINAL
// =========================
echo json_encode($resultado);
}




    
?>