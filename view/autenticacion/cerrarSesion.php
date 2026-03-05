

<?php

   

    require_once("../../controller/autenticacionController.php");
    $controller = new autenticacionController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;
    $resultado = $controller->eliminarToken( $token);
    // $resultado is an array with token/usuario
    header('Content-Type: application/json');
    echo json_encode($resultado);
}
    

?>