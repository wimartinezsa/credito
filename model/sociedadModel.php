<?php

class sociedadModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }

       
        public function listarSociedades(){
            $stament = $this->PDO->prepare("SELECT * FROM sociedades");
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function registrarSociedades($nombre, $valor){
            $stament = $this->PDO->prepare("INSERT INTO sociedades (sociedad,valor) VALUES (:nombre,:valor)");
            $stament->bindParam(':nombre', $nombre);
            $stament->bindParam(':valor', $valor);  
            $stament->execute();
            return true;
        }

        public function buscarSociedad($id){
            $stament = $this->PDO->prepare("SELECT * FROM sociedades WHERE id_sociedad = :id");
            $stament->bindParam(':id', $id);
            $stament->execute();
            return $stament->fetch(PDO::FETCH_ASSOC);
        }   

        public function actualizarSociedad($id, $nombre, $valor){
            $stament = $this->PDO->prepare("UPDATE sociedades SET sociedad = :nombre, valor = :valor WHERE id_sociedad = :id");
            $stament->bindParam(':id', $id);
            $stament->bindParam(':nombre', $nombre);
            $stament->bindParam(':valor', $valor);  
            $stament->execute();
            return true;
        }

        public function disponibleSociedad($id_sociedad){

        $stament = $this->PDO->prepare("SELECT 
(
    IFNULL((SELECT SUM(valor) 
        FROM sociedades 
        WHERE id_sociedad = s.id_sociedad),0)
    -
    IFNULL((SELECT SUM(p.valor_prestado)  
        FROM prestamos p   
        WHERE p.sociedad = s.id_sociedad 
        AND p.estado != 'negado'),0)
    +
    IFNULL((SELECT SUM(cu.valor)
        FROM prestamos p  
        JOIN cuotas cu ON cu.prestamo = p.id_prestamo
        WHERE p.sociedad = s.id_sociedad 
        AND p.estado != 'negado' 
        AND cu.estado = 'pagado'),0)
    -
    IFNULL((SELECT SUM(g.valor)  
        FROM gastos g
        WHERE g.sociedad = s.id_sociedad),0)
) AS disponible

FROM sociedades s
WHERE s.id_sociedad = :id_sociedad;");
        $stament->bindParam(':id_sociedad', $id_sociedad);
        $stament->execute();
        return $stament->fetch(PDO::FETCH_ASSOC);
        }
        
}




?>