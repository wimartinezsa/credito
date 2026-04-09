<?php

  

    require_once("../../controller/sociedadController.php");
    require_once("../../controller/autenticacionController.php");
    $controller_autenticacion = new autenticacionController();
    $controller = new sociedadController();


    session_start();
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





if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    $resultado = $controller->listarSociedadesEncargados();
    echo json_encode($resultado);
}
    

?>