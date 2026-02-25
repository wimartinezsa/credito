<?php


    require_once("../../controller/cuotaController.php");
    $controller = new cuotaController();



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id_prestamo']) ? $_GET['id_prestamo'] : null;
     if ($id === null) {
        http_response_code(400);
        echo json_encode(["error" => "ID no proporcionado"]);
        exit;
    }
    $resultado = $controller->listarCuotas($id);
    echo json_encode($resultado);
}

    
?>