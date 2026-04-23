<?php

class prestamoController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/prestamoModel.php');
        $this->model = new prestamoModel();
    }

   public function registrarPrestamo($sociedad,$ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo, $fiador,$estado){
        return $this->model->registrarPrestamo($sociedad,   $ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo, $fiador,$estado);
    }
    public function listaPrestamoEncargado($usuario){
        return $this->model->listaPrestamoEncargado($usuario);
    }

    public function buscarPrestamo($id_prestamo){
        return $this->model->buscarPrestamo($id_prestamo);
    }

public function actualizarPrestamo($ficha,$sociedad,$id_prestamo,$cliente, $fecha, $tiempo, $valor,$interes,$tipo,$fiador,$estado){
        return $this->model->actualizarPrestamo($sociedad,$ficha,$id_prestamo,$cliente, $fecha, $tiempo, $valor,$interes,$tipo,$fiador,$estado);
    }

public function listarPrestamosId($id_sociedad){
    return $this->model->listarPrestamosId($id_sociedad);
}

public function finalizarPrestamo($id_prestamo){
    return $this->model->finalizarPrestamo($id_prestamo);
}



 

}

?>