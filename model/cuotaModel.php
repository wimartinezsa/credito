<?php

class cuotaModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

       
        public function listarCuotas($id){
            $stament = $this->PDO->prepare("SELECT * FROM cuotas WHERE prestamo = ?");
            $stament->execute([$id]);
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }
        
public function pagarCuota($id_prestamo){
    try {

 $this->PDO->beginTransaction();

        $stament = $this->PDO->prepare("
            UPDATE cuotas SET estado = 'pagado' WHERE id_cuota = ? AND estado = 'pendiente'
        ");

        $stament->execute([$id_prestamo]);

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