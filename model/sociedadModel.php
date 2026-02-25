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
        

      


        
}

?>