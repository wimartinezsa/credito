<?php

require_once(__DIR__ . "/../../controller/sociedadController.php");
require_once(__DIR__ . "/../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new sociedadController();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // 🔹 Obtener headers
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

    // 🔥 CONSULTA
    $resultado = $controller->listarSociedadesEncargados($usuario);

    // 🔥 RESPUESTA ESTÁNDAR
    echo json_encode([
        "status" => "success",
        "message" => "Sociedades cargadas correctamente",
        "data" => $resultado
    ]);
}
?>