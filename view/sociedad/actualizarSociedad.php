<?php

   

require_once("../../controller/sociedadController.php");
$controller = new sociedadController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sociedad = $_POST['sociedad'] ?? null;
    $valor = $_POST['valor'] ?? null;
    if ($sociedad === null) {
        echo json_encode(['success' => false, 'message' => 'El nombre de la sociedad es requerido']);
        exit;
    }
    if ($sociedad !== null && $valor !== null) {
        $id = $_POST['id_sociedad'] ?? null;
        if ($id === null) {
            echo json_encode(['success' => false, 'message' => 'El ID de la sociedad es requerido']);
            exit;
        }
        $resultado = $controller->actualizarSociedad($id, $sociedad, $valor);
        echo json_encode(['success' => $resultado]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
}
    

?>