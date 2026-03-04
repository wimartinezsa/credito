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
        public function lstarTodos(){
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


public function login($identificacion, $password){
        $stmt = $this->PDO->prepare("SELECT * FROM personas WHERE identificacion = ?");
        if (!$stmt->execute([$identificacion])) {
            // executing failed, treat as no user
            return false;
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // fetch() returns false when no row matched
        if (!$user) {
            return false;
        }
        // compare passwords (plain equality; consider hashing in future)
        if ($password === $user['password']) {
            return $user;
        }
        return false;
    }


        public function guardarToken($id_persona, $token){
        $stmt = $this->PDO->prepare("UPDATE personas SET token = ? WHERE id_persona = ?");
        return $stmt->execute([$token, $id_persona]);
    }

     public function validarToken($token){
        $stmt = $this->PDO->prepare("SELECT * FROM personas WHERE token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function eliminarToken($id_usuario){
        $stmt = $this->PDO->prepare("UPDATE personas SET token = NULL WHERE id = ?");
        return $stmt->execute([$id_usuario]);
    }
}

?>