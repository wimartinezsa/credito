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
          
            $stament = $this->PDO->prepare("SELECT s.sociedad,
            (SELECT SUM(valor)  FROM sociedades so  WHERE  so.id_sociedad=s.id_sociedad)AS inicial,
            (SELECT SUM(p.valor_prestado)  FROM prestamos p   WHERE  p.sociedad=s.id_sociedad AND p.estado!='negado')AS prestado,

            (SELECT SUM(cu.valor)
             FROM prestamos p 
             JOIN cuotas cu ON cu.prestamo=p.id_prestamo  
             WHERE  p.sociedad=s.id_sociedad AND p.estado!='negado')AS futuro,
            
            (SELECT SUM(cu.valor)
            FROM prestamos p  
            JOIN cuotas cu ON cu.prestamo=p.id_prestamo
            WHERE  p.sociedad=s.id_sociedad AND p.estado!='negado' AND cu.estado='pagado')AS recaudado,

            (SELECT SUM(cu.valor)
            FROM prestamos p  
            JOIN cuotas cu ON cu.prestamo=p.id_prestamo
            WHERE  p.sociedad=s.id_sociedad AND p.estado!='negado' AND cu.estado='pendiente')AS pendiente,
            
            (SELECT SUM(g.valor)  FROM sociedades so  
            JOIN gastos g ON g.sociedad= so.`id_sociedad`
            WHERE  so.id_sociedad=s.id_sociedad) AS gastos
            
            FROM sociedades s  WHERE s.id_sociedad=:id_sociedad");

            $stament->bindParam(':id_sociedad', $id_sociedad);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }





        public function listarGastosPorFechas($fecha_inicio, $fecha_fin){
            $stament = $this->PDO->prepare("SELECT g.id_gasto,g.fecha,g.detalle,g.valor,s.sociedad FROM gastos g
            JOIN sociedades s ON g.sociedad = s.id_sociedad
            WHERE g .fecha BETWEEN :fecha_inicio AND :fecha_fin");
            $stament->bindParam(':fecha_inicio', $fecha_inicio);
            $stament->bindParam(':fecha_fin', $fecha_fin);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }
    
        
         public function listarPrestamosPorFechas($fecha_inicio, $fecha_fin){
            $stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo ) AS futuro,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pendiente') AS pendiente 
            FROM prestamos p
            JOIN sociedades s ON p.sociedad = s.id_sociedad
            JOIN personas pr ON pr.id_persona=p.persona
            WHERE p.fecha_prestamo BETWEEN :fecha_inicio AND :fecha_fin");
            $stament->bindParam(':fecha_inicio', $fecha_inicio);
            $stament->bindParam(':fecha_fin', $fecha_fin);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarReporteFicha($ficha){
            $stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo ) AS futuro,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pendiente') AS pendiente 
            FROM prestamos p
            JOIN sociedades s ON p.sociedad = s.id_sociedad
            JOIN personas pr ON pr.id_persona=p.persona
            WHERE p.ficha=:ficha");
            $stament->bindParam(':ficha', $ficha);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }   


        public function listarReporteCuotas($ficha){
            $stament = $this->PDO->prepare("SELECT c.id_cuota,c.fecha_cuota,c.mes,c.valor,c.tipo,c.estado FROM cuotas c
            JOIN prestamos p ON c.prestamo = p.id_prestamo
            WHERE p.ficha=:ficha");
            $stament->bindParam(':ficha', $ficha);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }
//reporte numero 5
        public function listarCuotasVencidas(){
            $stament = $this->PDO->prepare("SELECT p.ficha,pr.nombres,pr.telefono,p.valor_prestado,c.fecha_cuota,c.mes,c.valor,c.tipo,c.estado
                                                    FROM cuotas c
                                                    JOIN prestamos p ON c.prestamo = p.id_prestamo
                                                    JOIN personas pr ON pr.id_persona=p.persona
                                                    WHERE p.`estado`='aprobado' AND c.estado='pendiente' AND c.fecha_cuota < CURDATE() order by p.ficha asc") ;
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }

        //REPORTE NUMERO 6
         public function listarReportCliente($identificacion){
            $stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo ) AS futuro,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pagado') AS pagado,
            (SELECT SUM(c.valor) FROM cuotas c   WHERE c.prestamo=p.id_prestamo AND c.estado='pendiente') AS pendiente 
            FROM prestamos p
            JOIN sociedades s ON p.sociedad = s.id_sociedad
            JOIN personas pr ON pr.id_persona=p.persona
            WHERE pr.identificacion=:identificacion");
            $stament->bindParam(':identificacion', $identificacion);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        } 



        //REPORTE NUMERO 7
         public function listarCreditoNegado(){
            $stament = $this->PDO->prepare("SELECT p.id_prestamo,s.sociedad,p.ficha,p.fecha_prestamo,p.interes,p.tiempo,p.valor_prestado,p.tipo,p.estado,pr.nombres
            FROM prestamos p
            JOIN sociedades s ON p.sociedad = s.id_sociedad
            JOIN personas pr ON pr.id_persona=p.persona
            WHERE p.estado='negado'");
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        } 



        
        
}

?>