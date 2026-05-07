

<?php

  
    require_once("../../controller/autenticacionController.php");
    $controller = new autenticacionController();


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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
  
    $resultado = $controller->eliminarToken( $token);
    // $resultado is an array with token/usuario
    header('Content-Type: application/json');
    echo json_encode($resultado);
}
    

?>