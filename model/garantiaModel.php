<?php

class garantiaModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

 

   public function registrarGarantia($tipo,$ruta,$prestamo){

        $sql = "INSERT INTO garantias
        (tipo,ruta,prestamo)
        VALUES (?,?,?)";

        $stmt = $this->PDO->prepare($sql);

        $stmt->execute([$tipo,$ruta,$prestamo]);
    }


    public function listarTipoGarantia(){
        $sql = "SELECT * FROM tipo_garantia";
        $stmt = $this->PDO->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     
    public function listarGarantiasPrestamo($prestamo_id){
        $sql = "SELECT g.id_garantia, tg.nombre_tipo, g.ruta FROM garantias g
                JOIN tipo_garantia tg ON g.tipo = tg.id_tipo_garantia
                WHERE g.prestamo = ?";
        $stmt = $this->PDO->prepare($sql);
        $stmt->execute([$prestamo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function eliminarGarantia($id_garantia){
    try {

    
        /* 3. Eliminar registro en la BD */
        $sql = "DELETE FROM garantias WHERE id_garantia = ?";
        $stmt = $this->PDO->prepare($sql);
        $stmt->execute([$id_garantia]);

        return "Garantía eliminada correctamente";

    } catch (Exception $e) {

        return "Error al eliminar garantía: " . $e->getMessage();

    }
}

     
}

?>