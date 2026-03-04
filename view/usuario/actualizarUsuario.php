<?php
require_once("../../controller/usuarioController.php");
$controller = new usuarioController();



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
