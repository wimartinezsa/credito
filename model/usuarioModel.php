<?php

class usuarioModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
        }
        public function insertar($identificacion, $nombres, $direccion, $telefono, $calificacion = null, $observacion = null){
            $stament = $this->PDO->prepare("INSERT INTO personas (identificacion,nombres,direccion,telefono,calificacion,observacion) VALUES(:identificacion,:nombres,:direccion,:telefono,:calificacion,:observacion)");
            $stament->bindParam(":identificacion",$identificacion);
            $stament->bindParam(":nombres",$nombres);
            $stament->bindParam(":direccion",$direccion);
            $stament->bindParam(":telefono",$telefono);
            $stament->bindParam(":calificacion",$calificacion);
            $stament->bindParam(":observacion",$observacion);
            
            if($stament->execute()){
                return "Registro insertado correctamente";
            }else{
                return "Error al insertar el registro";
            }
        }

        public function actualizar($id_persona, $identificacion, $nombres, $direccion, $telefono, $calificacion = null, $observacion = null){
            $stament = $this->PDO->prepare("UPDATE personas SET identificacion = :identificacion, nombres = :nombres, direccion = :direccion, telefono = :telefono, calificacion = :calificacion, observacion = :observacion WHERE id_persona = :id_persona");
            $stament->bindParam(":identificacion", $identificacion);
            $stament->bindParam(":nombres", $nombres);
            $stament->bindParam(":direccion", $direccion);
            $stament->bindParam(":telefono", $telefono);
            $stament->bindParam(":calificacion", $calificacion);
            $stament->bindParam(":observacion", $observacion);
            $stament->bindParam(":id_persona", $id_persona);

            if($stament->execute()){
                return "Registro actualizado correctamente";
            }else{
                return "Error al actualizar el registro";
            }
        }

        public function eliminar($id_persona){
            $stament = $this->PDO->prepare("UPDATE personas SET estado = 'Inactivo' WHERE id_persona = :id_persona");
            $stament->bindParam(":id_persona", $id_persona);

            if($stament->execute()){
                return "Registro eliminado correctamente";
            }else{
                return "Error al eliminar el registro";
            }
        }
        public function listarTodos(){
            $stament = $this->PDO->prepare("SELECT * FROM personas");
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }

        
        public function buscarId($id_persona){
            $stament = $this->PDO->prepare("SELECT * FROM personas WHERE id_persona = :id_persona");
            $stament->bindParam(":id_persona", $id_persona);
            $stament->execute();
            return $stament->fetchAll(PDO::FETCH_ASSOC);
        }


    
    
}

?>