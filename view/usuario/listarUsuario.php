<?php

   

    require_once("../../controller/usuarioController.php");
    $controller = new usuarioController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $resultado = $controller->listarTodos();
    echo json_encode($resultado);
}
    

?>