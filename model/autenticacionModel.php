<?php

class autenticacionModel{
    private $PDO;
        public function __construct() 
    { 
        require_once(__DIR__ . '/../config/db.php');
        $con=new db();
        $this->PDO = $con->conexion();
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
        $stmt = $this->PDO->prepare("SELECT id_persona,nombres,token FROM personas WHERE token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function eliminarToken($token){
        $stmt = $this->PDO->prepare("UPDATE personas SET token = NULL WHERE token = ?");
        return $stmt->execute([$token]);
    }


}

?>