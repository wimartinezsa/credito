<?php

class reporteModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

       
//reporte numero 1
   public function listarEstadoSociedad($id_sociedad){
          
            $stament = $this->PDO->prepare("

            SELECT 
    s.id_sociedad,
    s.sociedad,

    -- Inicial (adiciones)
    IFNULL(a.inicial, 0) AS inicial,

    -- Prestado
    IFNULL(p.prestado, 0) AS prestado,

    -- Futuro
    IFNULL(p.futuro, 0) AS futuro,

    -- Recaudado
    IFNULL(c.recaudado, 0) AS recaudado,

    -- Pendiente
    IFNULL(c.pendiente, 0) AS pendiente,

    -- Gastos
    IFNULL(g.gastos, 0) AS gastos,

    -- Disponible real
    (
        IFNULL(s.caja, 0)
    ) AS disponible

FROM sociedades s

LEFT JOIN (
    SELECT 
        m.sociedad,
        SUM(m.valor) AS inicial
    FROM movimientos m
    WHERE m.tipo = 'adicion'
    GROUP BY m.sociedad
) a ON a.sociedad = s.id_sociedad

LEFT JOIN (
    SELECT 
        m.sociedad,
        SUM(p.valor_prestado) AS prestado,
        SUM(p.valor_futuro) AS futuro
    FROM prestamos p
    JOIN movimientos m ON m.id_movimiento = p.movimiento
    WHERE p.estado != 'negado'
    GROUP BY m.sociedad
) p ON p.sociedad = s.id_sociedad

LEFT JOIN (
    SELECT 
        m.sociedad,
        SUM(CASE WHEN c.estado = 'pagado' THEN c.valor ELSE 0 END) AS recaudado,
        SUM(CASE WHEN c.estado = 'pendiente' THEN c.valor ELSE 0 END) AS pendiente
    FROM cuotas c
    JOIN prestamos p ON p.id_prestamo = c.prestamo
    JOIN movimientos m ON m.id_movimiento = p.movimiento
    WHERE p.estado != 'negado'
    GROUP BY m.sociedad
) c ON c.sociedad = s.id_sociedad

LEFT JOIN (
    SELECT 
        m.sociedad,
        SUM(g.valor) AS gastos
    FROM gastos g
    JOIN movimientos m ON m.id_movimiento = g.movimiento
    GROUP BY m.sociedad
) g ON g.sociedad = s.id_sociedad

WHERE s.id_sociedad = :id_sociedad;"); 

            $stament->bindParam(':id_sociedad', $id_sociedad);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }





      public function listarGastosPorFechas($fecha_inicio, $fecha_fin,$sociedad){

    $stament = $this->PDO->prepare("
        SELECT 
            g.id_gasto,
            g.fecha,
            g.detalle,
            g.valor,
            s.sociedad
        FROM gastos g
        JOIN movimientos m ON m.id_movimiento = g.movimiento
        JOIN sociedades s ON s.id_sociedad = m.sociedad
        WHERE  s.id_sociedad = :sociedad AND g.fecha BETWEEN :fecha_inicio AND :fecha_fin
    ");

    $stament->bindParam(':fecha_inicio', $fecha_inicio);
    $stament->bindParam(':fecha_fin', $fecha_fin);
    $stament->bindParam(':sociedad', $sociedad);

    $stament->execute();

    return $stament->fetchAll(PDO::FETCH_ASSOC);
}
    
        public function listarPrestamosPorFechas($fecha_inicio, $fecha_fin,$sociedad){

    $stament = $this->PDO->prepare("
        SELECT 
            p.id_prestamo,
            s.sociedad,
            p.ficha,
            p.fecha_prestamo,
            p.interes,
            p.tiempo,
            p.valor_prestado,
            p.tipo,
            p.estado,
            pr.nombres,

            -- Total futuro
            IFNULL(SUM(c.valor), 0) AS futuro,

            -- Total pagado
            IFNULL(SUM(CASE 
                WHEN c.estado = 'pagado' THEN c.valor 
                ELSE 0 
            END), 0) AS pagado,

            -- Total pendiente
            IFNULL(SUM(CASE 
                WHEN c.estado = 'pendiente' THEN c.valor 
                ELSE 0 
            END), 0) AS pendiente

        FROM prestamos p

        JOIN movimientos m 
            ON m.id_movimiento = p.movimiento

        JOIN sociedades s 
            ON s.id_sociedad = m.sociedad

        JOIN personas pr 
            ON pr.id_persona = p.persona

        LEFT JOIN cuotas c 
            ON c.prestamo = p.id_prestamo

        WHERE  p.fecha_prestamo BETWEEN :fecha_inicio AND :fecha_fin AND s.id_sociedad = :sociedad

        GROUP BY p.id_prestamo
    ");

    $stament->bindParam(':fecha_inicio', $fecha_inicio);
    $stament->bindParam(':fecha_fin', $fecha_fin);
    $stament->bindParam(':sociedad', $sociedad);

    $stament->execute();

    return $stament->fetchAll(PDO::FETCH_ASSOC);
}

    
       public function listarReporteFicha($ficha){

    $stament = $this->PDO->prepare("
        SELECT 
            p.id_prestamo,
            s.sociedad,
            p.ficha,
            p.fecha_prestamo,
            p.interes,
            p.tiempo,
            p.valor_prestado,
            p.tipo,
            p.estado,
            pr.nombres,

            -- Total futuro
            IFNULL(SUM(c.valor), 0) AS futuro,

            -- Total pagado
            IFNULL(SUM(CASE 
                WHEN c.estado = 'pagado' THEN c.valor 
                ELSE 0 
            END), 0) AS pagado,

            -- Total pendiente
            IFNULL(SUM(CASE 
                WHEN c.estado = 'pendiente' THEN c.valor 
                ELSE 0 
            END), 0) AS pendiente

        FROM prestamos p

        JOIN movimientos m 
            ON m.id_movimiento = p.movimiento

        JOIN sociedades s 
            ON s.id_sociedad = m.sociedad

        JOIN personas pr 
            ON pr.id_persona = p.persona

        LEFT JOIN cuotas c 
            ON c.prestamo = p.id_prestamo

        WHERE p.ficha = :ficha

        GROUP BY p.id_prestamo
    ");

    $stament->bindParam(':ficha', $ficha);
    $stament->execute();

    return $stament->fetchAll(PDO::FETCH_ASSOC);
} 


     public function listarReporteCuotas($ficha){

    $stament = $this->PDO->prepare("
        SELECT 
            c.id_cuota,
            c.fecha_pago,
            c.nro_cuota,
            c.valor,
            c.tipo,
            c.estado,
            p.id_prestamo,
            s.sociedad

        FROM cuotas c

        JOIN prestamos p 
            ON p.id_prestamo = c.prestamo

        JOIN movimientos m 
            ON m.id_movimiento = p.movimiento

        JOIN sociedades s 
            ON s.id_sociedad = m.sociedad

        WHERE p.ficha = :ficha 

        ORDER BY c.nro_cuota ASC
    ");

    $stament->bindParam(':ficha', $ficha);
    $stament->execute();

    return $stament->fetchAll(PDO::FETCH_ASSOC);
}
//reporte numero 5
      public function listarCuotasVencidas($sociedad){

    $stament = $this->PDO->prepare("
        SELECT 
            p.ficha,
            pr.nombres,
            pr.telefono,
            s.sociedad,
            p.valor_prestado,
            c.fecha_pago,
            c.nro_cuota,
            c.valor,
            c.tipo,
            c.estado

        FROM cuotas c

        JOIN prestamos p 
            ON p.id_prestamo = c.prestamo

        JOIN movimientos m 
            ON m.id_movimiento = p.movimiento

        JOIN sociedades s 
            ON s.id_sociedad = m.sociedad

        JOIN personas pr 
            ON pr.id_persona = p.persona

        WHERE 
            p.estado != 'negado'
            AND c.estado = 'pendiente'
            AND c.fecha_pago < CURDATE()
            AND s.id_sociedad = :sociedad

        ORDER BY c.fecha_pago ASC, p.ficha ASC
    ");

    $stament->bindParam(':sociedad', $sociedad);
    $stament->execute();

    return $stament->fetchAll(PDO::FETCH_ASSOC);
}

        //REPORTE NUMERO 6
        public function listarReportCliente($identificacion){

    $stament = $this->PDO->prepare("
        SELECT 
            p.id_prestamo,
            s.sociedad,
            p.ficha,
            p.fecha_prestamo,
            p.interes,
            p.tiempo,
            p.valor_prestado,
            p.tipo,
            p.estado,
            pr.nombres,

            -- Total futuro
            IFNULL(SUM(c.valor), 0) AS futuro,

            -- Total pagado
            IFNULL(SUM(CASE 
                WHEN c.estado = 'pagado' THEN c.valor 
                ELSE 0 
            END), 0) AS pagado,

            -- Total pendiente
            IFNULL(SUM(CASE 
                WHEN c.estado = 'pendiente' THEN c.valor 
                ELSE 0 
            END), 0) AS pendiente

        FROM prestamos p

        JOIN movimientos m 
            ON m.id_movimiento = p.movimiento

        JOIN sociedades s 
            ON s.id_sociedad = m.sociedad

        JOIN personas pr 
            ON pr.id_persona = p.persona

        LEFT JOIN cuotas c 
            ON c.prestamo = p.id_prestamo

        WHERE pr.identificacion = :identificacion

        GROUP BY p.id_prestamo
    ");

    $stament->bindParam(':identificacion', $identificacion);
    $stament->execute();

    return $stament->fetchAll(PDO::FETCH_ASSOC);
}


        //REPORTE NUMERO 7 de movientos


public function listarMovimientosPorSociedad($id_sociedad){

    $stament = $this->PDO->prepare("
        SELECT 
            m.id_movimiento,
            m.fecha,
            m.tipo,
            m.valor,
            m.caja,
            s.sociedad,

            CASE 
                -- 🔹 PRÉSTAMO
                WHEN p.id_prestamo IS NOT NULL 
                    THEN CONCAT('Credito #', p.ficha, ' - ', pr.nombres)

                -- 🔹 CUOTA
                WHEN c.id_cuota IS NOT NULL 
                    THEN CONCAT('Pago cuota #', c.id_cuota, ' (Credito #', p2.ficha, ')')

                -- 🔹 GASTO
                WHEN g.id_gasto IS NOT NULL 
                    THEN CONCAT('Gasto: ', g.detalle)

                -- 🔹 ADICIÓN (no pertenece a nada)
                WHEN p.id_prestamo IS NULL 
                     AND c.id_cuota IS NULL 
                     AND g.id_gasto IS NULL
                    THEN CONCAT('Adicion a caja - Sociedad ', s.sociedad)

                ELSE 'Movimiento general'
            END AS detalle

        FROM movimientos m

        JOIN sociedades s 
            ON s.id_sociedad = m.sociedad

        LEFT JOIN prestamos p 
            ON p.movimiento = m.id_movimiento

        LEFT JOIN personas pr 
            ON pr.id_persona = p.persona

        LEFT JOIN cuotas c 
            ON c.movimiento = m.id_movimiento

        LEFT JOIN prestamos p2 
            ON p2.id_prestamo = c.prestamo

        LEFT JOIN gastos g 
            ON g.movimiento = m.id_movimiento

        WHERE m.sociedad = :id_sociedad

        ORDER BY m.fecha DESC, m.id_movimiento DESC
    ");

    $stament->bindParam(':id_sociedad', $id_sociedad);
    $stament->execute();

    return $stament->fetchAll(PDO::FETCH_ASSOC);
}

        
        
}

?>