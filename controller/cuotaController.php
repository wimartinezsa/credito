<?php

class cuotaController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/cuotaModel.php');
        $this->model = new cuotaModel();
    }

    public function listarCuotas($id_prestamo){
        return $this->model->listarCuotas($id_prestamo);
    }
    public function pagarCuota($id_cuota_pago,$valor_pagado,$fecha_recaudo){
        return $this->model->pagarCuota($id_cuota_pago,$valor_pagado,$fecha_recaudo);
    }

    public function devolucionCuota($id){
        return $this->model->devolucionCuota($id);
    }

public function actualizarCuota($codigo_cuota,$nro_pago,$fecha_pago,$valor_pago,$tipo_pago){
        return $this->model->actualizarCuota($codigo_cuota,$nro_pago,$fecha_pago,$valor_pago,$tipo_pago);
    }




public function nuevaCuota($id_prestamo,$nro_pago,$fecha_pago,$valor_pago,$tipo_pago){
        return $this->model->nuevaCuota($id_prestamo,$nro_pago,$fecha_pago,$valor_pago,$tipo_pago);
    }



public function eliminarCuota($id_cuota){
        return $this->model->eliminarCuota($id_cuota);
    }






}

?>
