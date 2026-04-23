<?php

   

    require_once("../../controller/gastoController.php");
    require_once(__DIR__ . "/../../controller/autenticacionController.php");
    $controller_autenticacion = new autenticacionController();
    $controller = new gastoController();

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



if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id !== null) {
        $resultado = $controller->anularGasto($id);
        echo json_encode(['success' => $resultado]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    }
}
    

?>