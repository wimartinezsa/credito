<?php

   

    require_once("../../controller/reporteController.php");
    $controller = new reporteController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

   $resultado = $controller->listarGastosPorFechas($_GET['fecha_inicio'], $_GET['fecha_fin'],$_GET['sociedad']);
    echo json_encode($resultado);
}
    

?>