<?php

class gastoController{
 
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/gastoModel.php');
        $this->model = new gastoModel();
    }

    public function listarGastoSociedad($id_sociedad){
        return $this->model->listarGastos($id_sociedad);
    }
   
    public function anularGasto($id){
        return $this->model->anularGasto($id);
    }   

    public function registrarGasto($sociedad, $fecha, $detalle, $valor) {
        return $this->model->registrarGasto($sociedad, $fecha, $detalle, $valor);
    }   

    
}

?>
