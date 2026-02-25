<?php

   

    require_once("../../controller/usuarioController.php");
    $controller = new usuarioController();





if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $resultado = $controller->insertar(
        $_POST["identificacion"], 
        $_POST["nombres"], 
        $_POST["direccion"], 
        $_POST["telefono"],
        isset($_POST["calificacion"]) ? $_POST["calificacion"] : null,
        isset($_POST["observacion"]) ? $_POST["observacion"] : null
    );
    echo $resultado;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $resultado = $controller->listarTodos();
    echo json_encode($resultado);
}
    

?>