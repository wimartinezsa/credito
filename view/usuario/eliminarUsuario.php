<?php
require_once("../../controller/usuarioController.php");
require_once("../../controller/autenticacionController.php");
$controller_autenticacion = new autenticacionController();
$controller = new usuarioController();



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
     

// Verificamos método
if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
    echo "Método no permitido";
    exit;
}

// Capturamos desde la URL (query string)
$id = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

if($id === null){
    echo "Falta el id del registro";
    exit;
}

$result = $controller->eliminar($id);
echo $result;

?>
