<?php

   

require_once("../../controller/sociedadController.php");
require_once("../../controller/autenticacionController.php");
$controller_autenticacion = new autenticacionController();

$controller = new sociedadController();


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
     


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sociedad = $_POST['id_sociedad'] ?? null;
    $encargado = $_POST['encargado'] ?? null;
    $rol = $_POST['rol'] ?? null;
  
   
        $resultado = $controller->asignarEncargadoSociedad( $id_sociedad, $encargado, $rol);
         echo json_encode($resultado);
   
}

?>