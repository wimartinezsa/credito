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
   


// Capturamos desde la URL (query string)
$id = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

// expected POST: id_persona, identificacion, nombres, direccion, telefono, calificacion, observacion

$ident = isset($_POST['identificacion']) ? $_POST['identificacion'] : null;
$nombre = isset($_POST['nombres']) ? $_POST['nombres'] : null;
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;
$calificacion = isset($_POST['calificacion']) ? $_POST['calificacion'] : null;
$observacion = isset($_POST['observacion']) ? $_POST['observacion'] : null;

if($id === null){
    echo "Falta el id del registro";
    exit;
}

$result = $controller->actualizarUsuario($id, $ident, $nombre, $direccion, $telefono, $calificacion, $observacion);
echo $result;

?>
