<?php

   

    require_once("../../controller/prestamoController.php");
    $controller = new prestamoController();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_prestamo = $_GET['id_prestamo'] ?? null;
    if (!$id_prestamo) {
        http_response_code(400);
        echo json_encode(["error" => "ID de préstamo no proporcionado"]);
        exit;
    }

    $resultado = $controller->buscarPrestamo($id_prestamo);
    echo json_encode($resultado);
}
    

?>