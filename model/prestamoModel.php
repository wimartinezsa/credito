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

        // Validar saldo disponible
        if ($valor > $caja) {
            throw new Exception("Saldo insuficiente en la sociedad");
        }

        // =========================
        // 2. REGISTRAR MOVIMIENTO
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
        // 3. REGISTRAR PRÉSTAMO
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
        // 5. CÁLCULO DEL PRÉSTAMO
        // =========================
        if ($tipo == "financiado") {

            $tipo_cuota = "cuota_fija";

            $interes_mensual = ($valor * $interes) / 100;
            $interes_total = $interes_mensual * $tiempo;
            $valor_total = $valor + $interes_total;
            $valor_cuota = $valor_total / $tiempo;

        } else {

            $tipo_cuota = "interes_mensual";
            $valor_cuota = ($valor * $interes) / 100;
        }

        $valor_cuota = round($valor_cuota, 2);

        // =========================
        // 6. GENERAR CUOTAS
        // =========================
        $fecha_base = new DateTime($fecha);
        $fecha_base->modify('+1 month');

        for($mes = 1; $mes <= $tiempo; $mes++){

            $fecha_cuota = clone $fecha_base;

            $dia_original = (int)(new DateTime($fecha))->format('d');
            $ultimo_dia_mes = (int)$fecha_cuota->format('t');

            if($dia_original > $ultimo_dia_mes){
                $fecha_cuota->setDate(
                    $fecha_cuota->format('Y'),
                    $fecha_cuota->format('m'),
                    $ultimo_dia_mes
                );
            } else {
                $fecha_cuota->setDate(
                    $fecha_cuota->format('Y'),
                    $fecha_cuota->format('m'),
                    $dia_original
                );
            }

            // Insertar cuota
            $stament_cuota = $this->PDO->prepare("
                INSERT INTO cuotas 
                (fecha_cuota, mes, valor, tipo, prestamo, estado) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stament_cuota->execute([
                $fecha_cuota->format('Y-m-d'),
                $mes,
                $valor_cuota,
                $tipo_cuota,
                $id_prestamo,
                "pendiente"
            ]);

            // Si es tipo mensual, última cuota incluye capital
            if($mes == $tiempo && $tipo == "mensual"){

                $stament_capital = $this->PDO->prepare("
                    INSERT INTO cuotas 
                    (fecha_cuota, mes, valor, tipo, prestamo, estado) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                $stament_capital->execute([
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

        // =========================
        // FINALIZAR TRANSACCIÓN
        // =========================
        $this->PDO->commit();

        return "Préstamo registrado correctamente";

    } catch (Exception $e) {

        $this->PDO->rollBack();
        return "Error: " . $e->getMessage();
    }
}


/*
public function registrarPrestamo($sociedad,$ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo,$fiador,$estado){
        return $this->insertarPrestamo($sociedad,$ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo,$fiador,$estado);
    }

*/

        

public function listaPrestamoEncargado(){


 $user = $_SESSION['usuario'];

        $stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.tipo,p.ficha, per.identificacion, per.nombres, p.fecha_prestamo, p.tiempo, p.valor_prestado,p.estado, 
        (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo ) AS futuro,
        (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
        (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pendiente') AS pendiente
        FROM personas per
        JOIN prestamos p ON per.id_persona = p.persona
        JOIN   sociedades s ON s.encargado  = per.id_persona
        WHERE s.encargado=?
        ORDER BY p.id_prestamo ASC");
                    $stament->execute([$user['id_persona']]);
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }




public function listarPrestamosId($id_sociedad){

$stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.tipo,p.ficha, per.identificacion, per.nombres, p.fecha_prestamo, p.tiempo, p.valor_prestado,p.estado, 
    (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo ) AS futuro,
    (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
    (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pendiente') AS pendiente
    FROM personas per
    JOIN prestamos p ON per.id_persona = p.persona
    JOIN   sociedades s ON s.encargado  = per.id_persona
    WHERE s.id_sociedad=?
    ORDER BY p.id_prestamo ASC");
    $stament->execute([$id_sociedad]);
    return $stament->fetchAll(PDO::FETCH_ASSOC);

}




public function buscarPrestamo($id_prestamo){
            $stament = $this->PDO->prepare("SELECT * FROM prestamos WHERE id_prestamo = ?");
            $stament->execute([$id_prestamo]);
            return $stament->fetch(PDO::FETCH_ASSOC);
        }




    public function actualizarPrestamo($sociedad,$ficha,$id_prestamo,$cliente, $fecha, $tiempo, $valor, $interes, $tipo, $fiador,$estado){

    try {

        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            UPDATE prestamos 
            SET sociedad=?, persona=?, fecha_prestamo=?, tiempo=?, valor_prestado=?, interes=?, tipo=?, ficha=? ,fiador=?,estado=?
            WHERE id_prestamo=?
        ");

        $stament->execute([$sociedad,$cliente, $fecha, $tiempo, $valor, $interes, $tipo,$ficha,$fiador,$estado,$id_prestamo]);


// se eliminan todos los pagos y se crean de nuevo con los nuevos datos del prestamo
        $stament_delete = $this->PDO->prepare("DELETE FROM cuotas WHERE prestamo = ?");
        $stament_delete->execute([$id_prestamo]);   
        $this->PDO->commit();
        $this->PDO->beginTransaction();
        // =========================
        // CÁLCULO DEL PRÉSTAMO
        // =========================

        

        if ($tipo == "financiado") {

            $tipo_cuota = "cuota_fija";

            $interes_mensual = ($valor * $interes) / 100;
            $interes_total = $interes_mensual * $tiempo;
            $valor_total = $valor + $interes_total;
            $valor_cuota = $valor_total / $tiempo;

        } else {

            $tipo_cuota = "interes_mensual";
            $valor_cuota = ($valor * $interes) / 100;
        }

        $valor_cuota = round($valor_cuota, 2);

        // =========================
        // GENERAR CUOTAS MENSUALES
        // =========================

        $fecha_base = new DateTime($fecha);

        // Primera cuota: un mes después del préstamo
        $fecha_base->modify('+1 month');

        for($mes = 1; $mes <= $tiempo; $mes++){

            $fecha_cuota = clone $fecha_base;

            // Ajuste para meses con menos días (ej: febrero)
            $dia_original = (int)(new DateTime($fecha))->format('d');
            $ultimo_dia_mes = (int)$fecha_cuota->format('t');

            if($dia_original > $ultimo_dia_mes){
                $fecha_cuota->setDate(
                    $fecha_cuota->format('Y'),
                    $fecha_cuota->format('m'),
                    $ultimo_dia_mes
                );
            } else {
                $fecha_cuota->setDate(
                    $fecha_cuota->format('Y'),
                    $fecha_cuota->format('m'),
                    $dia_original
                );
            }

            $stament_cuota = $this->PDO->prepare("
                INSERT INTO cuotas 
                (fecha_cuota, mes, valor, tipo, prestamo, estado) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stament_cuota->execute([
                $fecha_cuota->format('Y-m-d'),
                $mes,
                $valor_cuota,
                $tipo_cuota,
                $id_prestamo,
                "pendiente"
            ]);


            if($mes==$tiempo && $tipo == "mensual"){
                $stament_cuota = $this->PDO->prepare("
                INSERT INTO cuotas 
                (fecha_cuota, mes, valor, tipo, prestamo, estado) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stament_cuota->execute([
                $fecha_cuota->format('Y-m-d'),
                $mes,
                $valor,
                'capital',
                $id_prestamo,
                "pendiente"
            ]);

            }
            // Avanzar al siguiente mes
            $fecha_base->modify('+1 month');
        }

        $this->PDO->commit();

        return "Registro insertado correctamente";

    } catch (Exception $e) {

        $this->PDO->rollBack();
        return "Error: " . $e->getMessage();
    }
}




        
        
}

?>