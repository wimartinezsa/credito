<?php

   

require_once("../../controller/sociedadController.php");
require_once("../../controller/autenticacionController.php");
$controller_autenticacion = new autenticacionController();

$controller = new sociedadController();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sociedad = $input['id_sociedad'] ?? null;
    $encargado = $input['encargado'] ?? null;
    $password = $input['password'] ?? null;
    $rol = $input['rol'] ?? null;

    if (!$id_sociedad || !$encargado || !$password || !$rol) {
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios'
        ]);
        exit;
    }

    $resultado = $controller->asignarEncargadoSociedad($id_sociedad, $encargado, $rol, $password);

    if (is_array($resultado) && isset($resultado['success'], $resultado['message'])) {
        echo json_encode($resultado);
    } else {
        echo json_encode([
            'success' => false,
            'message' => is_string($resultado) ? $resultado : 'Error al asignar encargado'
        ]);
    }
}

?>