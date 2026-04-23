<?php

require_once __DIR__ . '/../controller/sociedadController.php';

$controller = new sociedadController();

// Ajusta estos valores a tu base de datos de pruebas
$id_sociedad = 1;
$encargado = 2;
$rol = 'Socio';
$password = 'Prueba123';

try {
    $resultado = $controller->asignarEncargadoSociedad($id_sociedad, $encargado, $rol, $password);
    echo "Resultado: $resultado\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
