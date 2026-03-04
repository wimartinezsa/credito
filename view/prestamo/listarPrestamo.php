<?php

   if(isset($headers['Authorization'])){
    $token = str_replace("Bearer ", "", $headers['Authorization']);
    echo "Token recibido: " . $token; // Para depuración
}

    require_once("../../controller/prestamoController.php");
    $controller = new prestamoController();







if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $resultado = $controller->listarTodos();
    echo json_encode($resultado);
}
    

?>