<?php
require_once("../../controller/usuarioController.php");
$controller = new usuarioController();



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
