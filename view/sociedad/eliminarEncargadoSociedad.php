<?php

   

    require_once("../../controller/sociedadController.php");
    require_once("../../controller/autenticacionController.php");
    $controller_autenticacion = new autenticacionController();
    $controller = new sociedadController();
    header("Content-Type: application/json");
    $headers = getallheaders();

    // 🔴 NORMALIZAR (MUY IMPORTANTE)
    $headers = array_change_key_case($headers, CASE_LOWER);

    $authHeader = $headers['authorization'] ?? null;

    // 🔴 VALIDAR TOKEN
    if (!$authHeader) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token no enviado"
        ]);
        exit;
    }

    // 🔹 Extraer token
    $token = str_replace('Bearer ', '', $authHeader);

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
    


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_admin = $_GET['id_admin'] ?? null;
    if (!$id_admin) {
        http_response_code(400);
        echo json_encode(["error" => "ID  no proporcionado"]);
        exit;
    }

    $resultado = $controller->eliminarEncargadoSociedad($id_admin);
    echo json_encode($resultado);
}
    




?>