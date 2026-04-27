<?php

  

    require_once("../../controller/sociedadController.php");
    require_once("../../controller/autenticacionController.php");
    $controller_autenticacion = new autenticacionController();
    $controller = new sociedadController();

   

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_sociedad = $_GET['id_sociedad'] ?? null;
    if (!$id_sociedad) {
        http_response_code(400);
        echo json_encode(["error" => "ID de sociedad no proporcionado"]);
        exit;
    }

    $resultado = $controller->listarEncargadosSociedadesId($id_sociedad);
    echo json_encode($resultado);
}





    

?>