<?php

   

    require_once("../../controller/gastoController.php");
    $controller = new gastoController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_sociedad = $_GET['id_sociedad'] ?? null;
    $resultado = $controller->listarGastoSociedad($id_sociedad);
    echo json_encode($resultado);
}
    

?>