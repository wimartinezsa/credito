

<?php

   

    require_once("../../controller/autenticacionController.php");
    $controller = new autenticacionController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $identificacion = isset($_POST['identificacion']) ? $_POST['identificacion'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $resultado = $controller->login($identificacion, $password);
    // $resultado is an array with token/usuario
    header('Content-Type: application/json');
    echo json_encode($resultado);
}
    

?>