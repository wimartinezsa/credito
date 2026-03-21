<?php

class prestamoModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

public function registrarPrestamo($sociedad,$ficha,$cliente, $fecha, $tiempo, $valor, $interes, $tipo,$fiador,$estado){

    try {

        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        // =========================
        // 1. CAJA
        // =========================
        $stmt = $this->PDO->prepare("SELECT caja FROM sociedades WHERE id_sociedad=?");
        $stmt->execute([$sociedad]);
        $soc = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$soc) throw new Exception("Sociedad no encontrada");

        if ($valor > $soc['caja']) {
            throw new Exception("Saldo insuficiente");
        }

        // =========================
        // 2. MOVIMIENTO
        // =========================
        $stmt = $this->PDO->prepare("
            INSERT INTO movimientos (fecha, sociedad, valor, caja, tipo) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([$fecha,$sociedad,$valor,$soc['caja'] - $valor,"credito"]);
        $id_movimiento = $this->PDO->lastInsertId();

        // =========================
        // 3. PRÉSTAMO
        // =========================
        $stmt = $this->PDO->prepare("
            INSERT INTO prestamos 
            (ficha, persona, fecha_prestamo, tiempo, valor_prestado, interes, tipo, fiador, estado, movimiento) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([$ficha,$cliente,$fecha,$tiempo,$valor,$interes,$tipo,$fiador,$estado,$id_movimiento]);
        $id_prestamo = $this->PDO->lastInsertId();

        // =========================
        // 4. ACTUALIZAR CAJA
        // =========================
        $stmt = $this->PDO->prepare("UPDATE sociedades SET caja = caja - ? WHERE id_sociedad = ?");
        $stmt->execute([$valor,$sociedad]);

        // =========================
        // 5. CÁLCULO
        // =========================
        if ($tipo == "financiado") {

            $tipo_cuota = "cuota_fija";

            $total = $valor + (($valor * $interes / 100) * $tiempo);
            $valor_cuota = round($total / $tiempo, 2);

        } else {

            $tipo_cuota = "interes_mensual";
            $valor_cuota = round(($valor * $interes) / 100, 2);
        }

        // =========================
        // 6. GENERAR CUOTAS
        // =========================
        $stmt_cuota = $this->PDO->prepare("
            INSERT INTO cuotas 
            (fecha_pago, nro_cuota, valor, tipo, prestamo, estado) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $fecha_base = new DateTime($fecha);
        $fecha_base->modify('+1 month');

        $suma = 0;

        for($mes = 1; $mes <= $tiempo; $mes++){

            $fecha_cuota = clone $fecha_base;

            $valor_final = $valor_cuota;

            // Ajuste última cuota
            if($mes == $tiempo && $tipo == "financiado"){
                $valor_final = ($valor + (($valor * $interes / 100) * $tiempo)) - $suma;
            }

            $stmt_cuota->execute([
                $fecha_cuota->format('Y-m-d'),
                $mes,
                $valor_final,
                $tipo_cuota,
                $id_prestamo,
                "pendiente"
            ]);

            $suma += $valor_final;

            // Capital al final (tipo mensual)
            if($mes == $tiempo && $tipo != "financiado"){
                $stmt_cuota->execute([
                    $fecha_cuota->format('Y-m-d'),
                    $mes + 1,
                    $valor,
                    'capital',
                    $id_prestamo,
                    "pendiente"
                ]);
            }

            $fecha_base->modify('+1 month');
        }

        $this->PDO->commit();

        return "Préstamo registrado correctamente";

    } catch (Exception $e) {

        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return "Error: " . $e->getMessage();
    }
}


/*
public function registrarPrestamo($sociedad,$ficha,$cliente, $fecha, $tiempo, $valor, $interes, $tipo,$fiador,$estado){

    try {

        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        // ✅ INICIAR TRANSACCIÓN
        $this->PDO->beginTransaction();

        // =========================
        // 1. OBTENER CAJA ACTUAL
        // =========================
        $stament_sociedad = $this->PDO->prepare("
            SELECT caja FROM sociedades WHERE id_sociedad=?
        ");

        $stament_sociedad->execute([$sociedad]);
        $resultado = $stament_sociedad->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            throw new Exception("Sociedad no encontrada");
        }

        $caja = $resultado['caja'];

        if ($valor > $caja) {
            throw new Exception("Saldo insuficiente en la sociedad");
        }

        // =========================
        // 2. MOVIMIENTO
        // =========================
        $stament_movimiento = $this->PDO->prepare("
            INSERT INTO movimientos 
            (fecha, sociedad, valor, caja, tipo) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $stament_movimiento->execute([
            $fecha, 
            $sociedad, 
            $valor, 
            $caja - $valor, 
            "credito"
        ]);

        $id_movimiento = $this->PDO->lastInsertId();

        // =========================
        // 3. PRÉSTAMO
        // =========================
        $stament_prestamo = $this->PDO->prepare("
            INSERT INTO prestamos 
            (ficha, persona, fecha_prestamo, tiempo, valor_prestado,valor_futuro, interes, tipo, fiador, estado, movimiento) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
        ");
         $valor_futuro=(($valor * ($interes/100))*$tiempo)+$valor;
        $stament_prestamo->execute([
            $ficha,
            $cliente,
            $fecha,
            $tiempo,
            $valor,
            $valor_futuro,
            $interes,
            $tipo,
            $fiador,
            $estado,
            $id_movimiento
        ]);

       // $id_prestamo = $this->PDO->lastInsertId();

        // =========================
        // 4. ACTUALIZAR CAJA
        // =========================
        $stament_update = $this->PDO->prepare("
            UPDATE sociedades 
            SET caja = caja - ? 
            WHERE id_sociedad = ?
        ");

        $stament_update->execute([$valor, $sociedad]);

      
         // =========================
        // CÁLCULO
        // =========================
        if ($tipo == "financiado") {

            $tipo_cuota = "cuota_fija";
            $interes_mensual = ($valor * $interes) / 100;
            $interes_total = $interes_mensual * $tiempo;
            $valor_total = $valor + $interes_total;
            $valor_cuota = round($valor_total / $tiempo, 2);

        } else {

            $tipo_cuota = "interes_mensual";
            $valor_cuota = round(($valor * $interes) / 100, 2);
        }



        $this->PDO->commit();

        return "Préstamo registrado correctamente";

    } catch (Exception $e) {

        // ✅ PROTEGER ROLLBACK
        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return "Error: " . $e->getMessage();
    }
}

*/

 /*
public function registrarPrestamo($sociedad,$ficha,$cliente, $fecha, $tiempo, $valor, $interes, $tipo,$fiador,$estado){

    try {

        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        // ✅ INICIAR TRANSACCIÓN
        $this->PDO->beginTransaction();

        // =========================
        // 1. OBTENER CAJA ACTUAL
        // =========================
        $stament_sociedad = $this->PDO->prepare("
            SELECT caja FROM sociedades WHERE id_sociedad=?
        ");

        $stament_sociedad->execute([$sociedad]);
        $resultado = $stament_sociedad->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            throw new Exception("Sociedad no encontrada");
        }

        $caja = $resultado['caja'];

        if ($valor > $caja) {
            throw new Exception("Saldo insuficiente en la sociedad");
        }

        // =========================
        // 2. MOVIMIENTO
        // =========================
        $stament_movimiento = $this->PDO->prepare("
            INSERT INTO movimientos 
            (fecha, sociedad, valor, caja, tipo) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $stament_movimiento->execute([
            $fecha, 
            $sociedad, 
            $valor, 
            $caja - $valor, 
            "credito"
        ]);

        $id_movimiento = $this->PDO->lastInsertId();

        // =========================
        // 3. PRÉSTAMO
        // =========================
        $stament_prestamo = $this->PDO->prepare("
            INSERT INTO prestamos 
            (ficha, persona, fecha_prestamo, tiempo, valor_prestado, interes, tipo, fiador, estado, movimiento) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stament_prestamo->execute([
            $ficha,
            $cliente,
            $fecha,
            $tiempo,
            $valor,
            $interes,
            $tipo,
            $fiador,
            $estado,
            $id_movimiento
        ]);

        $id_prestamo = $this->PDO->lastInsertId();

        // =========================
        // 4. ACTUALIZAR CAJA
        // =========================
        $stament_update = $this->PDO->prepare("
            UPDATE sociedades 
            SET caja = caja - ? 
            WHERE id_sociedad = ?
        ");

        $stament_update->execute([$valor, $sociedad]);

        // =========================
        // (RESTO DE TU LÓGICA IGUAL)
        // =========================

        $this->PDO->commit();

        return "Préstamo registrado correctamente";

    } catch (Exception $e) {

        // ✅ PROTEGER ROLLBACK
        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return "Error: " . $e->getMessage();
    }
}
*/


        

public function listaPrestamoEncargado(){


 $user = $_SESSION['usuario'];

        $stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.tipo,p.ficha, per.identificacion, per.nombres, p.fecha_prestamo, p.tiempo, p.valor_prestado,p.estado, 
        p.valor_futuro as futuro,
        (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
        (p.valor_futuro - (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado')) AS pendiente
        FROM personas per
        JOIN prestamos p ON per.id_persona = p.persona
        JOIN   sociedades s ON s.encargado  = per.id_persona
        WHERE s.encargado=?
        ORDER BY p.id_prestamo ASC");
                    $stament->execute([$user['id_persona']]);
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }




public function listarPrestamosId($id_sociedad){

$stament = $this->PDO->prepare("
    SELECT p.id_prestamo,soc.sociedad,p.tipo,p.ficha,p.interes, per.identificacion, per.nombres, p.fecha_prestamo, p.tiempo, p.valor_prestado,p.estado,
    (p.valor_futuro) AS futuro,
    (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
    (p.valor_futuro-(SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado')) AS pendiente
    FROM  prestamos p  
    JOIN personas per ON p.persona=per.id_persona
    JOIN movimientos mov ON mov.id_movimiento=p.movimiento
    JOIN sociedades soc ON soc.id_sociedad= mov.sociedad
    WHERE soc.id_sociedad=?
    ORDER BY p.id_prestamo ASC");
    $stament->execute([$id_sociedad]);
    return $stament->fetchAll(PDO::FETCH_ASSOC);

}




public function buscarPrestamo($id_prestamo){
            $stament = $this->PDO->prepare("SELECT m.sociedad,p.id_prestamo,p.ficha,p.persona,p.fecha_prestamo,p.tiempo,p.valor_prestado,p.interes,p.tipo,p.fiador,p.estado FROM prestamos p JOIN movimientos m ON m.id_movimiento=p.movimiento WHERE p.id_prestamo = ?");
            $stament->execute([$id_prestamo]);
            return $stament->fetch(PDO::FETCH_ASSOC);
        }




public function actualizarPrestamo($sociedad, $ficha, $id_prestamo, $cliente, $fecha, $tiempo, $valor, $interes, $tipo, $fiador, $estado){

    try {

        // =========================
        // VALIDACIONES
        // =========================
        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        // =========================
        // ACTUALIZAR PRÉSTAMO
        // =========================
        $stm_update = $this->PDO->prepare("
            UPDATE prestamos 
            SET persona = ?, 
                fecha_prestamo = ?, 
                tiempo = ?, 
                valor_prestado = ?, 
                interes = ?, 
                tipo = ?, 
                ficha = ?, 
                fiador = ?, 
                estado = ?
            WHERE id_prestamo = ? ");

        $stm_update->execute([
            $cliente, $fecha, $tiempo, $valor, $interes,
            $tipo, $ficha, $fiador, $estado, $id_prestamo
        ]);

    
         // =========================
        // OBTENER EL ID_MOVIMIENTO
        // =========================
        $stmt_movimiento = $this->PDO->prepare("
            SELECT movimiento 
            FROM prestamos 
            WHERE id_prestamo = ?");
        $stmt_movimiento->execute([$id_prestamo]);

        $movimiento = (int)($stmt_movimiento->fetch(PDO::FETCH_ASSOC)['movimiento'] ?? 0);


        // =========================
        // SE ACTUALIzA EL MOVIMIENTO
        // =========================

             $stm_update_movimiento = $this->PDO->prepare("
            UPDATE movimientos 
            SET sociedad = ?
            WHERE id_movimiento = ?
        ");

        $stm_update_movimiento->execute([$sociedad,$movimiento ]);


        $this->PDO->commit();

        return "Préstamo actualizado correctamente";

    } catch (Exception $e) {

        $this->PDO->rollBack();
        return "Error: " . $e->getMessage();
    }
}













/*
public function actualizarPrestamo($sociedad, $ficha, $id_prestamo, $cliente, $fecha, $tiempo, $valor, $interes, $tipo, $fiador, $estado){

    try {

        // =========================
        // VALIDACIONES
        // =========================
        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        // =========================
        // ACTUALIZAR PRÉSTAMO
        // =========================
        $stm_update = $this->PDO->prepare("
            UPDATE prestamos 
            SET persona = ?, 
                fecha_prestamo = ?, 
                tiempo = ?, 
                valor_prestado = ?, 
                interes = ?, 
                tipo = ?, 
                ficha = ?, 
                fiador = ?, 
                estado = ?
            WHERE id_prestamo = ? ");

        $stm_update->execute([
            $cliente, $fecha, $tiempo, $valor, $interes,
            $tipo, $ficha, $fiador, $estado, $id_prestamo
        ]);

        // =========================
        // ÚLTIMO MES PAGADO
        // =========================
        $stmt_mes = $this->PDO->prepare("
            SELECT MAX(mes) as ultimo_mes 
            FROM cuotas 
            WHERE prestamo = ? AND estado = 'pagado'
        ");
        $stmt_mes->execute([$id_prestamo]);

        $ultimo_mes_pagado = (int)($stmt_mes->fetch(PDO::FETCH_ASSOC)['ultimo_mes'] ?? 0);

        // =========================
        // ELIMINAR SOLO FUTUROS
        // =========================
        $stmt_delete = $this->PDO->prepare("
            DELETE FROM cuotas 
            WHERE prestamo = ? AND mes > ?
        ");
        $stmt_delete->execute([$id_prestamo, $ultimo_mes_pagado]);

        // =========================
        // CÁLCULO
        // =========================
        if ($tipo == "financiado") {

            $tipo_cuota = "cuota_fija";
            $interes_mensual = ($valor * $interes) / 100;
            $interes_total = $interes_mensual * $tiempo;
            $valor_total = $valor + $interes_total;
            $valor_cuota = round($valor_total / $tiempo, 2);

        } else {

            $tipo_cuota = "interes_mensual";
            $valor_cuota = round(($valor * $interes) / 100, 2);
        }

        // =========================
        // FECHA BASE
        // =========================
        $fecha_base = new DateTime($fecha);
        $fecha_base->modify('+1 month');

        if($ultimo_mes_pagado > 0){
            $fecha_base->modify('+' . $ultimo_mes_pagado . ' month');
        }


         // =========================
        // OBTENER EL ID_MOVIMIENTO
        // =========================
        $stmt_movimiento = $this->PDO->prepare("
            SELECT movimiento 
            FROM prestamos 
            WHERE id_prestamo = ?");
        $stmt_movimiento->execute([$id_prestamo]);

        $movimiento = (int)($stmt_movimiento->fetch(PDO::FETCH_ASSOC)['movimiento'] ?? 0);


        // =========================
        // SE ACTUALIzA EL MOVIMIENTO
        // =========================

             $stm_update_movimiento = $this->PDO->prepare("
            UPDATE movimientos 
            SET sociedad = ?
            WHERE id_movimiento = ?
        ");

        $stm_update_movimiento->execute([$sociedad,$movimiento ]);


        // =========================
        // GENERAR CUOTAS
        // =========================
        for($mes = $ultimo_mes_pagado + 1; $mes <= $tiempo; $mes++){

            $fecha_cuota = clone $fecha_base;

            $dia_original = (int)(new DateTime($fecha))->format('d');
            $ultimo_dia_mes = (int)$fecha_cuota->format('t');

            $fecha_cuota->setDate(
                $fecha_cuota->format('Y'),
                $fecha_cuota->format('m'),
                min($dia_original, $ultimo_dia_mes)
            );

            // 🔒 INSERT SEGURO (NO DUPLICA NUNCA)
            $stmt_insert = $this->PDO->prepare("
                INSERT INTO cuotas 
                (fecha_cuota, mes, valor, tipo, prestamo, estado) 
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    valor = VALUES(valor),
                    tipo = VALUES(tipo)
            ");

            $stmt_insert->execute([
                $fecha_cuota->format('Y-m-d'),
                $mes,
                $valor_cuota,
                $tipo_cuota,
                $id_prestamo,
                "pendiente"
            ]);

            // CAPITAL FINAL (si aplica)
            if($mes == $tiempo && $tipo == "mensual"){

                $stmt_capital = $this->PDO->prepare("
                    INSERT INTO cuotas 
                    (fecha_cuota, mes, valor, tipo, prestamo, estado) 
                    VALUES (?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE valor = VALUES(valor)
                ");

                $stmt_capital->execute([
                    $fecha_cuota->format('Y-m-d'),
                    $mes,
                    $valor,
                    'capital',
                    $id_prestamo,
                    "pendiente"
                ]);
            }

            $fecha_base->modify('+1 month');
        }

        $this->PDO->commit();

        return "Préstamo actualizado correctamente";

    } catch (Exception $e) {

        $this->PDO->rollBack();
        return "Error: " . $e->getMessage();
    }
}
*/



        
        
}

?>