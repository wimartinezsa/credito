<?php

class cuotaController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/cuotaModel.php');
        $this->model = new cuotaModel();
    }

    public function listarCuotas($id){
        return $this->model->listarCuotas($id);
    }
    public function pagarCuota($id){
        return $this->model->pagarCuota($id);
    }

    public function devolucionCuota($id){
        return $this->model->devolucionCuota($id);
    }
}

?>
