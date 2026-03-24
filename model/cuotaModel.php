<?php

class cuotaModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

       
        public function listarCuotas($id_prestamo){
            $stament = $this->PDO->prepare("SELECT * FROM cuotas WHERE prestamo = ?");
            $stament->execute([$id_prestamo]);
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }
  
        


public function eliminarCuota($id_cuota){
    try {

 $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            DELETE FROM cuotas  WHERE id_cuota = ? 
        ");

        $stament->execute([$id_cuota]);

     if($stament->rowCount() > 0){
        $this->PDO->commit();
        return "Cuota eliminada correctamente";

        } else {
            $this->PDO->rollBack();
            return "No se pudo eliminar la cuota";
        }


    }catch(Exception $e) {
        $this->PDO->rollBack();
        return Err::err($e->getMessage());
    }
}


public function pagarCuota($id_cuota_pago,$valor_pagado,$fecha_recaudo){
    try {

 $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            UPDATE cuotas SET estado = 'pagado',valor=?,fecha_recauda=? WHERE id_cuota = ? AND estado = 'pendiente'
        ");

        $stament->execute([$valor_pagado,$fecha_recaudo,$id_cuota_pago]);

     if($stament->rowCount() > 0){
        $this->PDO->commit();
        return "Cuota pagada correctamente";

        } else {
            $this->PDO->rollBack();
            return "No se encontró la cuota o ya está pagada";
        }


    }catch(Exception $e) {
        $this->PDO->rollBack();
        return Err::err($e->getMessage());
    }
}
      


public function actualizarCuota($codigo_cuota, $nro_pago, $fecha_pago, $valor_pago, $tipo_pago) {

    try {

        // Validaciones básicas
        if ($codigo_cuota <= 0 || $nro_pago <= 0 || $valor_pago < 0) {
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            UPDATE cuotas 
            SET fecha_pago = ?, 
                nro_cuota = ?, 
                valor = ?, 
                tipo = ?, 
                estado = ?
            WHERE id_cuota = ?
        ");

        $stament->execute([
            $fecha_pago,
            $nro_pago,
            $valor_pago,
            $tipo_pago,
            'pendiente',
            $codigo_cuota
        ]);

        // No depender solo de rowCount
        if ($stament->rowCount() >= 0) {
            $this->PDO->commit();
            return "Proceso ejecutado correctamente";
        }

    } catch (Exception $e) {

        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return "Error: " . $e->getMessage();
    }
}



public function nuevaCuota($id_prestamo, $nro_pago, $fecha_pago, $valor_pago, $tipo_pago) {

    try {

        // Validaciones básicas
        if ($id_prestamo <= 0 || $nro_pago <= 0 || $valor_pago < 0 || empty($fecha_pago) || empty($tipo_pago)) {
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            INSERT INTO cuotas (prestamo, fecha_pago, nro_cuota, valor, tipo, estado) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stament->execute([
            $id_prestamo,
            $fecha_pago,
            $nro_pago,
            $valor_pago,
            $tipo_pago,
            'pendiente'
        ]);

        $this->PDO->commit();
        return "Cuota registrada correctamente";

    } catch (Exception $e) {

        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return "Error: " . $e->getMessage();
    }
}




public function devolucionCuota($id_prestamo){
    try {

 $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            UPDATE cuotas SET estado = 'pendiente' WHERE id_cuota = ? AND estado = 'pagado'
        ");

        $stament->execute([$id_prestamo]);

     if($stament->rowCount() > 0){
        $this->PDO->commit();
        return "Devolución realizada correctamente";

        } else {
            $this->PDO->rollBack();
            return "No se encontró la cuota";
        }


    }catch(Exception $e) {
        $this->PDO->rollBack();
        return Err::err($e->getMessage());
    }
}


        
}

?>