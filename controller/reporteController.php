<?php

class reporteController{
 
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/reporteModel.php');
        $this->model = new reporteModel();
    }


    public function listarEstadoSociedad($id_sociedad){
        return $this->model->listarEstadoSociedad($id_sociedad);
    }
    public function listarGastosPorFechas($fecha_inicio, $fecha_fin,$sociedad){
        return $this->model->listarGastosPorFechas($fecha_inicio, $fecha_fin,$sociedad);
    }
    
    public function listarPrestamosPorFechas($fecha_inicio, $fecha_fin,$sociedad){
        return $this->model->listarPrestamosPorFechas($fecha_inicio, $fecha_fin,$sociedad);
    }   

    function listarReporteFicha($ficha){
        return $this->model->listarReporteFicha($ficha);
    }

    function listarReporteCuotas($ficha){
        return $this->model->listarReporteCuotas($ficha);
    }   

    function listarCuotasVencidas($sociedad){
        return $this->model->listarCuotasVencidas($sociedad);
    }   

    function listarReportCliente($identificacion){
        return $this->model->listarReportCliente($identificacion);
    }
    
    function listarCreditoNegado(){
        return $this->model->listarCreditoNegado();
    }



    function listarMovimientosPorSociedad($id_sociedad){
        return $this->model->listarMovimientosPorSociedad($id_sociedad);
    }




 }

?>