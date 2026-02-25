<?php

class prestamoModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

 

public function insertarPrestamo($ficha,$cliente, $fecha, $tiempo, $valor, $interes, $tipo){

    try {

        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            INSERT INTO prestamos 
            (ficha, persona, fecha_prestamo, tiempo, valor_prestado, interes, tipo) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stament->execute([$ficha,$cliente, $fecha, $tiempo, $valor, $interes, $tipo]);

        $id_prestamo = $this->PDO->lastInsertId();

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




public function registrarPrestamo($ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo){
        return $this->insertarPrestamo($ficha,$cliente, $fecha, $tiempo, $valor,$interes,$tipo);
    }



        

public function listarTodos(){
            $stament = $this->PDO->prepare("SELECT p.id_prestamo,p.ficha, per.identificacion, per.nombres, p.fecha_prestamo, p.tiempo, p.valor_prestado, 
(SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo ) AS futuro,
(SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
(SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pendiente') AS pendiente
 FROM personas per
 JOIN prestamos p ON per.id_persona = p.persona");
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }


public function buscarPrestamo($id_prestamo){
            $stament = $this->PDO->prepare("SELECT * FROM prestamos WHERE id_prestamo = ?");
            $stament->execute([$id_prestamo]);
            return $stament->fetch(PDO::FETCH_ASSOC);
        }




    public function actualizarPrestamo($ficha,$id_prestamo,$cliente, $fecha, $tiempo, $valor, $interes, $tipo){

    try {

        if($tiempo <= 0 || $valor <= 0 || $interes < 0){
            return "Datos inválidos";
        }

        $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            UPDATE prestamos 
            SET persona=?, fecha_prestamo=?, tiempo=?, valor_prestado=?, interes=?, tipo=?, ficha=? 
            WHERE id_prestamo=?
        ");

        $stament->execute([$cliente, $fecha, $tiempo, $valor, $interes, $tipo,$ficha,$id_prestamo]);


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