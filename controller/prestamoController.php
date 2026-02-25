<?php

class prestamoController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/prestamoModel.php');
        $this->model = new prestamoModel();
    }

   public function registrarPrestamo($ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo){
        return $this->model->registrarPrestamo($ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo);
    }
    public function listarTodos(){
        return $this->model->listarTodos();
    }

    public function buscarPrestamo($id_prestamo){
        return $this->model->buscarPrestamo($id_prestamo);
    }

public function actualizarPrestamo($ficha,$id_prestamo,$cliente, $fecha, $tiempo, $valor,$interes,$tipo){
        return $this->model->actualizarPrestamo($ficha,$id_prestamo,$cliente, $fecha, $tiempo, $valor,$interes,$tipo);
    }   


}

?>