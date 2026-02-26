<?php

   

    require_once("../../controller/gastoController.php");
    $controller = new gastoController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $resultado = $controller->listarGastos();
    echo json_encode($resultado);
}
    

?>