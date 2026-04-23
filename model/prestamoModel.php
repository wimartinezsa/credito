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
        $valor_futuro=(($valor*($interes/100))*$tiempo)+$valor;
        $stmt = $this->PDO->prepare("
            INSERT INTO prestamos 
            (ficha, persona, fecha_prestamo, tiempo, valor_prestado,valor_futuro, interes, tipo, fiador, estado, movimiento) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
        ");

        $stmt->execute([$ficha,$cliente,$fecha,$tiempo,$valor,$valor_futuro,$interes,$tipo,$fiador,$estado,$id_movimiento]);
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




 

        

public function listaPrestamoEncargado($usuario){




        $stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.tipo,p.ficha, per.identificacion, per.nombres, p.fecha_prestamo, p.tiempo, p.valor_prestado,p.estado, 
        p.valor_futuro as futuro,
        (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
        (p.valor_futuro - (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado')) AS pendiente
        FROM personas per
        JOIN prestamos p ON per.id_persona = p.persona
        JOIN   sociedades s ON s.encargado  = per.id_persona
        WHERE s.encargado=?
        ORDER BY p.id_prestamo ASC");
                    $stament->execute([$usuario['id_persona']]);
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




public function finalizarPrestamo($id_prestamo) {

    try {

        if ($id_prestamo <= 0) {
            return [
                "status" => "error",
                "message" => "ID inválido"
            ];
        }

        $this->PDO->beginTransaction();

        // =========================
        // 1. OBTENER DATOS
        // =========================
        $stmt = $this->PDO->prepare("
            SELECT 
                p.valor_futuro, 
                p.estado, 
                COALESCE(SUM(c.valor), 0) AS total_pagado
            FROM prestamos p
            LEFT JOIN cuotas c 
                ON c.prestamo = p.id_prestamo 
                AND c.estado = 'pagado'
            WHERE p.id_prestamo = ?
            GROUP BY p.id_prestamo
        ");

        $stmt->execute([$id_prestamo]);
        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prestamo) {
            throw new Exception("Préstamo no encontrado");
        }

        // =========================
        // 2. VALIDAR ESTADO
        // =========================
        if ($prestamo['estado'] === 'finalizado') {
            throw new Exception("El préstamo ya está finalizado");
        }

        $valor_futuro = (float)($prestamo['valor_futuro'] ?? 0);
        $total_pagado  = (float)($prestamo['total_pagado'] ?? 0);

        // =========================
        // 3. VALIDACIÓN FINANCIERA
        // =========================
        if (abs($valor_futuro - $total_pagado) > 0.01) {
            throw new Exception("El valor del préstamo no coincide con lo pagado");
        }

        // =========================
        // 4. VALIDAR CUOTAS PENDIENTES
        // =========================
        $stmt = $this->PDO->prepare("
            SELECT COUNT(*) as pendientes
            FROM cuotas
            WHERE prestamo = ? AND estado = 'pendiente'
        ");

        $stmt->execute([$id_prestamo]);
        $pendientes = $stmt->fetchColumn();

        if ($pendientes > 0) {
            throw new Exception("Aún existen cuotas pendientes");
        }

        // =========================
        // 5. FINALIZAR PRÉSTAMO
        // =========================
        $stmt = $this->PDO->prepare("
            UPDATE prestamos 
            SET estado = 'finalizado'  
            WHERE id_prestamo = ?
        ");

        $stmt->execute([$id_prestamo]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("No se pudo finalizar el préstamo");
        }

        $this->PDO->commit();

        return [
            "status" => "success",
            "message" => "Crédito finalizado con éxito",
            "data" => [
                "id_prestamo" => $id_prestamo
            ]
        ];

    } catch (Exception $e) {

        if ($this->PDO->inTransaction()) {
            $this->PDO->rollBack();
        }

        return [
            "status" => "error",
            "message" => $e->getMessage()
        ];
    }
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















        
        
}

?>