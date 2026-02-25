<?php

   

    require_once("../../controller/prestamoController.php");
    $controller = new prestamoController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $resultado = $controller->actualizarPrestamo(
        id_prestamo: $_POST["id_prestamo"],
        sociedad:$_POST["sociedad"],
        ficha: $_POST["ficha"],
        cliente: $_POST["cliente"], 
        fecha: $_POST["fecha"], 
        tiempo: $_POST["tiempo"], 
        valor: $_POST["valor"],
        interes: $_POST["interes"],
        tipo: $_POST["tipo"],
        fiador: $_POST["fiador"],
        
    );
    echo $resultado;
}

 

?>