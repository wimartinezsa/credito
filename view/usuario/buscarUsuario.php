<?php

   

    require_once("../../controller/usuarioController.php");
    $controller = new usuarioController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;
    $resultado = $controller->buscarId($id);
    echo json_encode($resultado);
}
    

?>