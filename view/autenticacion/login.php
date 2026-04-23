
<?php

require_once '../../config.php';
require_once '../../controller/autenticacionController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identificacion = $_POST['identificacion'] ?? null;
    $password = $_POST['password'] ?? null;

    $controller = new autenticacionController();
    $resultado = $controller->login($identificacion, $password);

    echo json_encode($resultado ?? [
        "success" => false,
        "message" => "Error en login"
    ]);
    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Método no permitido"
]);