<?php

class reporteController{
 
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/reporteModel.php');
        $this->model = new reporteModel();
    }

    public function listarGastosPorFechas($fecha_inicio, $fecha_fin){
        return $this->model->listarGastosPorFechas($fecha_inicio, $fecha_fin);
    }
    
    public function listarPrestamosPorFechas($fecha_inicio, $fecha_fin){
        return $this->model->listarPrestamosPorFechas($fecha_inicio, $fecha_fin);
    }   

    function listarReporteFicha($ficha){
        return $this->model->listarReporteFicha($ficha);
    }
}

?>