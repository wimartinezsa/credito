<?php

class gastoController{
 
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/gastoModel.php');
        $this->model = new gastoModel();
    }

    public function listarGastos(){
        return $this->model->listarGastos();
    }
   
    public function eliminarGasto($id){
        return $this->model->eliminarGasto($id);
    }   

    public function registrarGasto($sociedad, $fecha, $detalle, $valor) {
        return $this->model->registrarGasto($sociedad, $fecha, $detalle, $valor);
    }   

    
}

?>
