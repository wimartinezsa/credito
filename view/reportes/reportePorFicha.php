<?php

   
    require_once("../../controller/reporteController.php");

     require_once("../../controller/autenticacionController.php");
    
     $controller_autenticacion = new autenticacionController();
    $controller = new reporteController();

    header("Content-Type: application/json");

// 🔴 OBTENER HEADERS (FALTABA ESTO)
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

   $resultado = $controller->listarReporteFicha($_GET['ficha']);
    echo json_encode($resultado);
}
    

?>