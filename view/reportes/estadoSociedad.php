<?php

   
    require_once("../../controller/reporteController.php");
    $controller = new reporteController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   $resultado = $controller->listarEstadoSociedad($_GET['id_sociedad']);
    echo json_encode($resultado);
}
    

?>