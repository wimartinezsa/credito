<?php

   

require_once("../../controller/gastoController.php");
$controller = new gastoController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sociedad = $_POST['sociedad'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $detalle = $_POST['detalle'] ?? null;
    $valor = $_POST['valor'] ?? null;

    if ($sociedad !== null && $fecha !== null && $detalle !== null && $valor !== null) {
        $resultado = $controller->registrarGasto($sociedad, $fecha, $detalle, $valor);
        echo json_encode(['success' => $resultado]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
}
    

?>