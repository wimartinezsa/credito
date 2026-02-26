<?php

   

    require_once("../../controller/gastoController.php");
    $controller = new gastoController();


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id !== null) {
        $resultado = $controller->eliminarGasto($id);
        echo json_encode(['success' => $resultado]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    }
}
    

?>