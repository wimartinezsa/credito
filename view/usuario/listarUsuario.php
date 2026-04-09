<?php
session_start();

require_once("../../controller/usuarioController.php");
require_once("../../controller/autenticacionController.php");

$controller_autenticacion = new autenticacionController();
$controller = new usuarioController();

// 🔐 VALIDAR SESIÓN
if (!isset($_SESSION["token"])) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Sesión no iniciada"
    ]);
    exit;
}

// 🔐 VALIDAR TOKEN
$usuario = $controller_autenticacion->validarToken($_SESSION["token"]);

if (!$usuario) {
    session_destroy(); // 🔥 destruir sesión inválida

    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Token inválido o expirado"
    ]);
    exit;
}

// ✅ PETICIÓN
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $resultado = $controller->listarTodos();
    echo json_encode($resultado);
}