<?php

   

    require_once("../../controller/prestamoController.php");
    $controller = new prestamoController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $resultado = $controller->registrarPrestamo(
        ficha: $_POST["ficha"],
        cliente: $_POST["cliente"], 
        fecha: $_POST["fecha"], 
        tiempo: $_POST["tiempo"], 
        valor: $_POST["valor"],
        interes: $_POST["interes"],
        tipo: $_POST["tipo"],
    );
    echo $resultado;
}

 

?>