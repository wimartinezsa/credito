<?php

class gastoModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

       
        public function listarGastos(){
            $stament = $this->PDO->prepare("SELECT * FROM gastos");
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }


function registrarGasto($sociedad, $fecha, $detalle, $valor) {
    try {
        $statement = $this->PDO->prepare("INSERT INTO gastos (sociedad, fecha, detalle, valor) VALUES (:sociedad, :fecha, :detalle, :valor)");
        $statement->bindParam(':sociedad', $sociedad);
        $statement->bindParam(':fecha', $fecha);
        $statement->bindParam(':detalle', $detalle);
        $statement->bindParam(':valor', $valor);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

        public function eliminarGasto($id) {

    try {
        $statement = $this->PDO->prepare("DELETE FROM gastos WHERE id_gasto = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount(); // devuelve cuántas filas eliminó
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
        
        
}

?>