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




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $resultado = $controller->registrarPrestamo(
        sociedad: $_POST["sociedad"],
        ficha: $_POST["ficha"],
        cliente: $_POST["cliente"], 
        fecha: $_POST["fecha"], 
        tiempo: $_POST["tiempo"], 
        valor: $_POST["valor"],
        interes: $_POST["interes"],
        tipo: $_POST["tipo"],
        fiador: $_POST["fiador"],
        estado: $_POST["estado"]
    );
    echo $resultado;
}

 

?>