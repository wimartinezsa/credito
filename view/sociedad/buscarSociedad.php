<?php

   

    require_once("../../controller/sociedadController.php");
    $controller = new sociedadController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_sociedad = $_GET['id_sociedad'] ?? null;
    if (!$id_sociedad) {
        http_response_code(400);
        echo json_encode(["error" => "ID de sociedad no proporcionado"]);
        exit;
    }

    $resultado = $controller->buscarSociedad($id_sociedad);
    echo json_encode($resultado);
}
    

?>