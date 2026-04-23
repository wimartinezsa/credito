<?php
require_once("../../controller/prestamoController.php");
require_once("../../controller/autenticacionController.php");
$controller_prestamo = new prestamoController();
$controller_autenticacion = new autenticacionController();



// Token válido, procesar solicitud

header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // 🔹 Obtener headers
    $headers = getallheaders();

    // 🔹 Validar que venga Authorization
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token no enviado"
        ]);
        exit;
    }

    // 🔹 Extraer token (Bearer XXXXX)
    $token = str_replace('Bearer ', '', $headers['Authorization']);

    // 🔹 Validar token
    $usuario = $controller_autenticacion->validarToken($token);

    if (!$usuario) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token inválido o expirado"
        ]);
        exit;
    }



    $resultado = $controller_prestamo->listaPrestamoEncargado($usuario);
    echo json_encode($resultado);
}


?>