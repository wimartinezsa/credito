<?php

   

    require_once("../../controller/prestamoController.php");
    require_once("../../controller/autenticacionController.php");
    $controller_autenticacion = new autenticacionController();
    $controller = new prestamoController();

    session_start();
    if(isset($_SESSION["token"])){
            $usuario = $controller_autenticacion->validarToken($_SESSION['token']);
            if (!json_encode($usuario) && !strlen(json_encode($usuario)) > 0) {
        // echo json_encode($usuario );
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Token inválido o expirado']);
        exit;
    }
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Token no proporcionado']);
        exit;
    }



if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_prestamo = $_GET['id_prestamo'] ?? null;
    if (!$id_prestamo) {
        http_response_code(400);
        echo json_encode(["error" => "ID de préstamo no proporcionado"]);
        exit;
    }

    $resultado = $controller->buscarPrestamo($id_prestamo);
    echo json_encode($resultado);
}
    

?>