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




public function pagarCuota($id_cuota_pago, $valor_pagado, $fecha_recaudo) {
    try {

        // =========================
        // 0. VALIDACIONES
        // =========================
        if ($id_cuota_pago <= 0 || $valor_pagado <= 0 || empty($fecha_recaudo)) {
            return "Datos inválidos";
        }

        // Iniciar transacción
        $this->PDO->beginTransaction();

        // =========================
        // 1. VALIDAR CUOTA PENDIENTE
        // =========================
        $stmt = $this->PDO->prepare("
            SELECT c.*, m.sociedad 
            FROM cuotas c
            JOIN prestamos p ON p.id_prestamo = c.prestamo
            JOIN movimientos m ON m.id_movimiento = p.movimiento
            WHERE c.id_cuota = ? AND c.estado = 'pendiente'
        ");
        $stmt->execute([$id_cuota_pago]);
        $cuota = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cuota) {
            throw new Exception("La cuota no existe o ya está pagada");
        }

        $id_sociedad = $cuota['sociedad'];

        // =========================
        // 2. OBTENER CAJA ACTUAL
        // =========================
        $stmt = $this->PDO->prepare("SELECT caja FROM sociedades WHERE id_sociedad = ?");
        $stmt->execute([$id_sociedad]);
        $soc = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$soc) {
            throw new Exception("Sociedad no encontrada");
        }

        $caja_actual = $soc['caja'];
        $nueva_caja = $caja_actual + $valor_pagado;

        // =========================
        // 3. REGISTRAR MOVIMIENTO
        // =========================
        $stmt = $this->PDO->prepare("
            INSERT INTO movimientos (fecha, sociedad, valor, caja, tipo,estado) 
            VALUES (?, ?, ?, ?, ?,?)
        ");

        $stmt->execute([
            date('Y-m-d'),      // fecha actual
            $id_sociedad,
            $valor_pagado,
            $nueva_caja,
            "cuota",
            "ejecutado"
        ]);

        $id_movimiento = $this->PDO->lastInsertId();

        // =========================
        // 4. ACTUALIZAR CUOTA
        // =========================
        $stmt = $this->PDO->prepare("
            UPDATE cuotas 
            SET estado = 'pagado', 
                valor= ?, 
                fecha_recaudo = ?, 
                movimiento = ?
            WHERE id_cuota = ?
        ");

        $stmt->execute([
            $valor_pagado,
            $fecha_recaudo,
            $id_movimiento,
            $id_cuota_pago
        ]);

        // =========================
        // 5. ACTUALIZAR CAJA
        // =========================
        $stmt = $this->PDO->prepare("
            UPDATE sociedades 
            SET caja = ? 
            WHERE id_sociedad = ?
        ");

        $stmt->execute([
            $nueva_caja,
            $id_sociedad
        ]);

        // =========================
        // 6. CONFIRMAR TRANSACCIÓN
        // =========================
        $this->PDO->commit();

        return "Cuota pagada correctamente";

    } catch (Exception $e) {

        // Revertir si hay error
        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return "Error: " . $e->getMessage();
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




public function devolucionCuota($id_cuota, $valor_cuota){
    try {

        // =========================
        // 0. VALIDACIONES
        // =========================
        if ($id_cuota <= 0 || $valor_cuota <= 0) {
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        // =========================
        // 1. VALIDAR CUOTA PAGADA
        // =========================
        $stmt = $this->PDO->prepare("
            SELECT c.*, m.sociedad 
            FROM cuotas c
            JOIN prestamos p ON p.id_prestamo = c.prestamo
            JOIN movimientos m ON m.id_movimiento = p.movimiento
            WHERE c.id_cuota = ? AND c.estado = 'pagado'
        ");
        $stmt->execute([$id_cuota]);
        $cuota = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cuota) {
            throw new Exception("La cuota no existe o no está pagada");
        }

        $id_sociedad = $cuota['sociedad'];

        // =========================
        // 2. OBTENER CAJA
        // =========================
        $stmt = $this->PDO->prepare("SELECT caja FROM sociedades WHERE id_sociedad = ?");
        $stmt->execute([$id_sociedad]);
        $soc = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$soc) {
            throw new Exception("Sociedad no encontrada");
        }

        $caja_actual = $soc['caja'];
        $nueva_caja = $caja_actual - $valor_cuota;

        // 🚨 Validación importante
        if ($nueva_caja < 0) {
            throw new Exception("La caja no puede quedar negativa");
        }

        // =========================
        // 3. REGISTRAR MOVIMIENTO (REVERSO)
        // =========================
        $stmt = $this->PDO->prepare("
            INSERT INTO movimientos (fecha, sociedad, valor, caja, tipo, estado) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            date('Y-m-d'),
            $id_sociedad,
            $valor_cuota,
            $nueva_caja,
            "devolucion",
            "anulado"
        ]);

        $id_movimiento = $this->PDO->lastInsertId();

        // =========================
        // 4. ACTUALIZAR CUOTA
        // =========================
        $stmt = $this->PDO->prepare("
            UPDATE cuotas 
            SET estado = 'pendiente',
                movimiento = NULL
            WHERE id_cuota = ?
        ");

        $stmt->execute([$id_cuota]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("No se pudo actualizar la cuota");
        }

        // =========================
        // 5. ACTUALIZAR CAJA
        // =========================
        $stmt = $this->PDO->prepare("
            UPDATE sociedades 
            SET caja = ? 
            WHERE id_sociedad = ?
        ");

        $stmt->execute([$nueva_caja, $id_sociedad]);

        // =========================
        // 6. COMMIT
        // =========================
        $this->PDO->commit();

        return "Devolucion realizada correctamente";

    } catch(Exception $e) {

        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return "Error: " . $e->getMessage();
    }
}








        
}

?>