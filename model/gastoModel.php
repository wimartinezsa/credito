<?php

class gastoModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

       
        public function listarGastos($id_sociedad){
            $stament = $this->PDO->prepare("SELECT * FROM gastos g
            join movimientos m ON m.id_movimiento=g.movimiento
             where m.sociedad=?");
            $stament->execute([$id_sociedad]);
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }


function registrarGasto($sociedad, $fecha, $detalle, $valor) {
    try {

        if ($valor <= 0) {
            throw new Exception("El valor debe ser mayor a 0");
        }

        $this->PDO->beginTransaction();

        // =========================
        // 1. OBTENER CAJA ACTUAL
        // =========================
        $stmt_sociedad = $this->PDO->prepare("
            SELECT caja FROM sociedades WHERE id_sociedad = ?
        ");
        $stmt_sociedad->execute([$sociedad]);
        $resultado = $stmt_sociedad->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            throw new Exception("Sociedad no encontrada");
        }

        $caja = $resultado['caja'];

        // Validar saldo
        if ($valor > $caja) {
            throw new Exception("Saldo insuficiente en la sociedad");
        }

        $nuevaCaja = $caja - $valor;

        // =========================
        // 2. REGISTRAR MOVIMIENTO
        // =========================
        $stmt_movimiento = $this->PDO->prepare("
            INSERT INTO movimientos 
            (fecha, sociedad, valor, caja, tipo,estado) 
            VALUES (?, ?, ?, ?, ?,?)
        ");

        $stmt_movimiento->execute([
            $fecha,
            $sociedad,
            $valor,
            $nuevaCaja,
            "gasto" ,// ✔ corregido
            "ejecutado"
        ]);

        
   $id_movimiento = $this->PDO->lastInsertId();
        // =========================
        // 3. REGISTRAR GASTO
        // =========================
        $stmt_gasto = $this->PDO->prepare("
            INSERT INTO gastos 
            (movimiento, fecha, detalle, valor,estado) 
            VALUES (?, ?, ?, ?,?)
        ");

        $stmt_gasto->execute([
           $id_movimiento,
            $fecha,
            $detalle,
            $valor,
            "ejecutado"
        ]);

        // =========================
        // 4. ACTUALIZAR CAJA
        // =========================
        $stmt_update = $this->PDO->prepare("
            UPDATE sociedades SET caja = ? WHERE id_sociedad = ?
        ");

        $stmt_update->execute([
            $nuevaCaja,
            $sociedad
        ]);

        $this->PDO->commit();
        return true;

    } catch (Exception $e) {

        $this->PDO->rollBack(); // ✔ importante
        return $e->getMessage(); // útil para debug
    }
}




 public function anularGasto($id) {
    try {

        $this->PDO->beginTransaction();

        // =========================
        // 1. VALIDAR GASTO
        // =========================
        $stmt_gasto = $this->PDO->prepare("
            SELECT estado, movimiento 
            FROM gastos 
            WHERE id_gasto = ?
        ");
        $stmt_gasto->execute([$id]);
        $gasto = $stmt_gasto->fetch(PDO::FETCH_ASSOC);

        if (!$gasto) {
            throw new Exception("El gasto no existe");
        }

        if ($gasto['estado'] == 'anulado') {
            throw new Exception("El gasto ya está anulado");
        }

        $id_movimiento = $gasto['movimiento'];

        // =========================
        // 2. OBTENER DATOS DEL MOVIMIENTO
        // =========================
        $stmt_mov = $this->PDO->prepare("
            SELECT sociedad, valor 
            FROM movimientos 
            WHERE id_movimiento = ?
        ");
        $stmt_mov->execute([$id_movimiento]);
        $movimiento = $stmt_mov->fetch(PDO::FETCH_ASSOC);

        if (!$movimiento) {
            throw new Exception("Movimiento no encontrado");
        }

        $id_sociedad = $movimiento['sociedad'];
        $valor = $movimiento['valor'];

        // =========================
        // 3. ANULAR GASTO
        // =========================
        $stmt_update_gasto = $this->PDO->prepare("
            UPDATE gastos SET estado='anulado' 
            WHERE id_gasto = ?
        ");
        $stmt_update_gasto->execute([$id]);

        // =========================
        // 4. ANULAR MOVIMIENTO
        // =========================
        $stmt_update_mov = $this->PDO->prepare("
            UPDATE movimientos SET estado='anulado' 
            WHERE id_movimiento = ?
        ");
        $stmt_update_mov->execute([$id_movimiento]);

        // =========================
        // 5. DEVOLVER DINERO A CAJA
        // =========================
        $stmt_update_soc = $this->PDO->prepare("
            UPDATE sociedades 
            SET caja = caja + ? 
            WHERE id_sociedad = ?
        ");
        $stmt_update_soc->execute([$valor, $id_sociedad]);

        $this->PDO->commit();

        return "Gasto anulado correctamente";

    } catch (Exception $e) {

        $this->PDO->rollBack(); // ✔ obligatorio
        return $e->getMessage();
    }
}





        
}

?>