<?php


    require_once("../../controller/cuotaController.php");
    $controller = new cuotaController();



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id_cuota']) ? $_GET['id_cuota'] : null;
     if ($id === null) {
        http_response_code(400);
        echo json_encode(["error" => "ID no proporcionado"]);
        exit;
    }
    $resultado = $controller->pagarCuota($id);
    echo ($resultado);
}

    
?>