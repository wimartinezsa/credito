<?php


    require_once("../../controller/cuotaController.php");
    require_once("../../controller/autenticacionController.php");
    $controller_autenticacion = new autenticacionController();

    $controller = new cuotaController();
    session_start();
    if(isset($_SESSION["token"])){
            $usuario = $controller_autenticacion->validarToken($_SESSION['token']);
            if (!json_encode($usuario) && !strlen(json_encode($usuario)) > 0) {
        // echo json_encode($usuario );
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Token inválido o expirado']);
        exit;
    }
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Token no proporcionado']);
        exit;
    }


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    header('Content-Type: application/json');

    $id_cuota = isset($_GET['id_cuota']) ? (int) $_GET['id_cuota'] : 0;
    $valor_cuota = isset($_GET['valor_cuota']) ? (float) $_GET['valor_cuota'] : 0;

    // =========================
    // VALIDACIONES
    // =========================
    if ($id_cuota <= 0) {
        http_response_code(400);
        echo json_encode(["status" => false, "error" => "ID de cuota inválido"]);
        exit;
    }

    if ($valor_cuota <= 0) {
        http_response_code(400);
        echo json_encode(["status" => false, "error" => "Valor de cuota inválido"]);
        exit;
    }

    // =========================
    // EJECUTAR PROCESO
    // =========================
    $resultado = $controller->devolucionCuota($id_cuota, $valor_cuota);

    // =========================
    // RESPUESTA
    // =========================
    echo json_encode([
        "status" => true,
        "message" => $resultado
    ]);
}

    
?>