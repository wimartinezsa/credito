

<?php
require_once("../../controller/garantiaController.php");
require_once("../../controller/autenticacionController.php");
$controller_garantia = new garantiaController();
$controller_autenticacion = new autenticacionController();

    ini_set('session.cookie_path', '/');
   
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

        



// Token válido, procesar solicitud


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $id_garantia = $_GET['id_garantia'] ?? null;
    $resultado = $controller_garantia->eliminarGarantia( $id_garantia);
    echo json_encode($resultado);
}


?>