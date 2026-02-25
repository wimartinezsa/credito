<?php

   

    require_once("../../controller/sociedadController.php");
    $controller = new sociedadController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $resultado = $controller->listarSociedades();
    echo json_encode($resultado);
}
    

?>